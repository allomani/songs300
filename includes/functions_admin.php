<?php
/**
 *  Allomani Audio & Video (Songs) v3.0
 * 
 * @package Allomani.Songs
 * @version 3.0
 * @copyright (c) 2006-2018 Allomani , All rights reserved.
 * @author Ali Allomani <info@allomani.com>
 * @link http://allomani.com
 * @license GNU General Public License version 3.0 (GPLv3)
 * 
 */

//-------------- Get Remote filesize --------
function fetch_remote_filesize($url)
    {
        // since cURL supports any protocol we should check its http(s)
        preg_match('#^((http|ftp)s?):\/\/#i', $url, $check);
        
     /*   else if (false AND !empty($check) AND function_exists('curl_init') AND $ch = curl_init())
        { */
        if(function_exists('curl_init') AND $ch = curl_init()){
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
            /* Need to enable this for self signed certs, do we want to do that?
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            */

            $header = curl_exec($ch);
            curl_close($ch);

            if ($header !== false)
            {
                preg_match('#Content-Length: (\d+)#i', $header, $matches);
                return sprintf('%u', $matches[1]);
            }
       }elseif (ini_get('allow_url_fopen') != 0 AND $check[1] == 'http')
        {
            $urlinfo = @parse_url($url);

            if (empty($urlinfo['port']))
            {
                $urlinfo['port'] = 80;
            }

            if ($fp = @fsockopen($urlinfo['host'], $urlinfo['port'], $errno, $errstr, 30))
            {
                fwrite($fp, 'HEAD ' . $url . " HTTP/1.1\r\n");
                fwrite($fp, 'HOST: ' . $urlinfo['host'] . "\r\n");
                fwrite($fp, "Connection: close\r\n\r\n");

                while (!feof($fp))
                {
                    $headers .= fgets($fp, 4096);
                }
                fclose ($fp);

                $headersarray = explode("\n", $headers);
                foreach($headersarray as $header)
                {
                    if (stristr($header, 'Content-Length') !== false)
                    {
                        $matches = array();
                        preg_match('#(\d+)#', $header, $matches);
                        return sprintf('%u', $matches[0]);
                    }
                }
            }
        }
        return false;   
    }
    
    //-------------- Get Dir Files List ----------
function get_files($dir,$allowed_types="",$subdirs_search=1) {
      $dir = (substr($dir,-1,1)=="/" ? substr($dir,0,strlen($dir)-1) : $dir);

    if($dh = opendir($dir)) {

        $files = Array();
        $inner_files = Array();

        while($file = readdir($dh)) {
            if($file != "." && $file != ".." && $file[0] != '.') {
                if(is_dir($dir . "/" . $file) && $subdirs_search) {
                    $inner_files = get_files($dir . "/" . $file,$allowed_types);
                    if(is_array($inner_files)) $files = array_merge($files, $inner_files);
                }else{
                  $fileinfo= pathinfo($dir . "/" . $file);
                $imtype = $fileinfo["extension"];
          if(is_array($allowed_types)){
          if(in_array($imtype,$allowed_types)){
               $files[] =  $dir . "/" . $file;
           }
          }else{
               $files[] =  $dir . "/" . $file;
          }
                }
            }
        }

        closedir($dh);
        return $files;
    }
}

/*
//--------------------------- Create Thumb ----------------------------
function create_thumb($filename , $width , $height,$fixed,$suffix=''){
    
require_once(CWD .'/includes/class_thumb.php');
if(function_exists("ImageCreateTrueColor")){
 if(file_exists(CWD . "/$filename")){ 
 $img_info = @getimagesize(CWD . "/$filename"); 
 $thumb=new thumbnail(CWD . "/$filename");

 if($fixed){
 $thumb->size_fixed($width,$height);
 }else{    
 if($img_info[0] < $width){
 $width = $img_info[0];
 }
 if($img_info[1] < $height){
$height = $img_info[1];
  }
  
 if($height > $width){
  $thumb->size_height($height); 
 }else{
 $thumb->size_width($width);
 } 
 }        
           

   $imtype = file_extension(CWD . "/$filename");


$thumb->jpeg_quality(100); 

$save_name  =  basename($filename);
$save_path = str_replace("/".$save_name,'',$filename);

$imtype = file_extension($save_name);
$save_name = convert2en($save_name);
$save_name = strtolower($save_name);
$save_name= str_replace(" ","_",$save_name);

if($suffix){
$save_name = str_replace(".$imtype","",$save_name)."_".$suffix.".$imtype";
}

    
while(file_exists(CWD . "/" .$save_path."/".$save_name)){
$save_name = str_replace(".$imtype","",$save_name)."_".rand(0,999).".$imtype";    
}
    
    
$thumb->save(CWD . "/" .$save_path."/".$save_name);           
return ($save_path."/".$save_name) ;
 }else{
     return false;
 }
 }else{
return $false;     
 }
        }   */
        
  //-------------------------------------------- 
 
//---------- Delete song -------//
function delete_song($id){
db_query("delete from songs_songs where id='$id'");
} 

//-------- Delete Singer -------//
function delete_singer($id){
    
$qr = db_query("select id from songs_songs where album='$id'");
while($data = db_fetch($qr)){
delete_song($data['id']); 
}

db_query("delete from songs_singers where id='$id'");
db_query("delete from songs_albums where cat='$id'");
db_query("delete from songs_singers_photos where cat='$id'");
db_query("delete from songs_singers_photos_tags where singer_id='$id'");

}   


//---------- Get Videos Cats --------//
function get_videos_cats($id){
  $cats_arr = array();
   $cats_arr[]=$id;

         $qr1 = db_query("select id from songs_videos_cats where cat='$id'");
         while($data1 = db_fetch($qr1)){
          $nxx = get_videos_cats($data1['id']);
          if(is_array($nxx)){
              $cats_arr = array_merge($nxx,$cats_arr);
          }
           unset($nxx);
          }

          return  $cats_arr ;
         }
         
//---------- delete video --------------

function delete_video($id,$cat=0){
    db_query("delete from songs_videos_data where id='$id'".iif($cat," and cat='$cat'"));    
//db_query("delete from songs_videos_tags where video_id='$iid'");

//----delete from songs ----//
$qrs = db_query("select * from songs_songs where video_id='$id'");
while($datas = db_fetch($qrs)){
    db_query("update songs_songs set video_id=0 where id='$datas[id]'");
    update_singer_counters($datas['album'],'videos');
}
//-------------------------
}



//------------ videos admin path ---------------
function print_admin_videos_path($cat,$filename=""){
     global $phrases;

            $dir_data['cat'] =$cat;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from songs_videos_cats where id='$dir_data[cat]'");


        $dir_content = "<a href='index.php?action=videos&cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;

        }
   print "<p align=$global_align><img src='images/link.gif'> <a href='index.php?action=videos&cat=0'>$phrases[the_videos]  </a> / $dir_content  $filename</p>";

}

//------------ Access Log ------------
   function access_log_record($username,$status){
       global $access_log_expire ;
        
       $expire_date  = datetime("",time()-(24*60*60*$access_log_expire));
       db_query("delete from songs_access_log where date < '$expire_date'");
       db_query("insert into songs_access_log (username,date,status,ip) values ('".db_escape($username)."','".datetime()."','$status','".db_escape(getenv("REMOTE_ADDR"))."')");
   } 

//---------------- get timezones --------------------
function get_timezones(){
require_once(CWD . '/includes/class_xml.php');     
  $xmlobj = new XMLparser(false, CWD . "/xml/time_zones.xml");
  $xml = $xmlobj->parse();
  return (array) $xml['zone'];
  
}

//--------------------------------- Check Functions ---------------------------------
function check_safe_functions($condition_value){

  global $phrases ;
                            
                         
  //------ get safe functions ----------
  require_once(CWD . '/includes/class_xml.php');     
  $xmlobj = new XMLparser(false, CWD . "/xml/safe_functions.xml");
  $xml = $xmlobj->parse();
$safe_functions =  (array) $xml['func'];
if(!count($safe_functions)){ return "Error : Please check safe functions XML File";}
//------------------------------------------


      if (preg_match_all('#([a-z0-9_{}$>-]+)(\s|/\*.*\*/|(\#|//)[^\r\n]*(\r|\n))*\(#si', $condition_value, $matches))
                        {

                                $functions = array();
                                foreach($matches[1] AS $key => $match)
                                {
                                        if (!in_array(strtolower($match), $safe_functions) && function_exists(strtolower($match)))
                                        {
                                                $funcpos = strpos($condition_value, $matches[0]["$key"]);
                                                $functions[] = array(
                                                        'func' => stripslashes($match),
                                                    //    'usage' => substr($condition_value, $funcpos, (strpos($condition_value, ')', $funcpos) - $funcpos + 1)),
                                                );
                                        }
                                }
                                if (!empty($functions))
                                {
                                        unset($safe_functions[0], $safe_functions[1], $safe_functions[2]);



                                        foreach($functions AS $error)
                                        {
                                                $errormsg .= "$phrases[err_function_usage_denied]: <code>" . htmlspecialchars($error['func']) . "</code>
                                                <br>\n";
                                        }

                                        return "$errormsg";
                                     //   return false ;
                                }else{
                                      //   return true ;
                                      return false;
                                          }
                        }
                     //   return true ;
                     return false;
                        }
                        
//--------- if cat admin ----------------
function if_cat_admin($id){
    global $user_info,$phrases; 
  if($user_info['groupid'] != 1){
     $prm_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'"); 
   
   if($id){
      $songs_permisions = explode(",",$prm_data['permisions']);
         if(!in_array($id,$songs_permisions)){
   print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
   die();
    }
    }
   
   if(!$prm_data['permisions']){
      print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
      die();
     }  
  }   
}
                        
//--------------- if singer admin ------------------
function if_singer_admin($id){
     global $user_info,$phrases;
     


 if($user_info['groupid'] != 1){
     $prm_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'"); 
  if($id){
  $singer_cat = db_qr_fetch("select cat from songs_singers where id='$id'");

         $songs_permisions = explode(",",$prm_data['permisions']);
         if(!in_array($singer_cat['cat'],$songs_permisions)){
   print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
   die();
    }
    }

if(!$prm_data['permisions']){
      print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
      die();
     }
      }
}


//------------ update singer counters -----------

function update_singer_counters($id,$op='songs'){
    switch($op){
        case "songs" :
        $count = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$id'"),"count"); 
        db_query("update songs_singers set songs_count = '$count' where id='$id'");
        break;
        
        case "albums" :
        $count = valueof(db_qr_fetch("select count(*) as count from songs_albums where cat='$id'"),"count");    
        db_query("update songs_singers set albums_count = '$count' where id='$id'");  
        break;
        
        case "photos" :
        $count = valueof(db_qr_fetch("select count(*) as count from songs_singers_photos where cat='$id'"),"count");
        db_query("update songs_singers set photos_count = '$count' where id='$id'");    
        break;
        
        
        case "videos" :
        $count = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$id' and video_id > 0"),"count");
        db_query("update songs_singers set videos_count = '$count' where id='$id'"); 
        break;
    }
}


//-------- Songs Custom Fields ----------

function get_song_field($name,$data,$value=""){
    global $phrases;
      $cntx = "" ;

if($data['type']=="text"){

        $cntx .= "<input type=text name=\"$name\" value=\"$value\" $data[style]>";
     
//---------- text area -------------
}elseif($data['type']=="textarea"){

$cntx .= "<textarea name=\"$name\" $data[style]>$value</textarea>";

//-------- select -----------------
}elseif($data['type']=="select"){

        $cntx .= "<select name=\"$name\" $data[style]>";
   //     if($action=="search"){ $cntx .= "<option value=\"\">$phrases[without_selection]</option>";}

        $vx  = explode("\n",$data['value']);
        foreach($vx as $dvalue){

        $cntx .= "<option value=\"$dvalue\"".iif($dvalue==$value," selected").">$dvalue</option>";
            }
        $cntx .= "</select>";

//--------- radio ------------
}elseif($data['type']=="radio"){

    //    if($action=="search"){ $cntx .= "<input type=\"radio\" name=\"$name\" value=\"\" $data[style] checked>$phrases[without_selection]<br>";}
        $vx  = explode("\n",$data['value']);
        foreach($vx as $dvalue){
        $cntx .= "<input type=\"radio\" name=\"$name\" value=\"$dvalue\" $data[style]".iif($dvalue==$value," selected")."> $dvalue<br>";
            }

//-------- checkbox -------------
}elseif($data['type']=="checkbox"){
  $vx  = explode("\n",$data['value']);
        foreach($vx as $dvalue){
        $cntx .= "<input type=\"checkbox\" name=\"$name\" value=\"$dvalue\" ".iif($dvalue==$value, " selected")."> $dvalue<br>";
            }

}
return $cntx;
}


//--------------------- preview Text ------------------------------------
function getPreviewText($text) {
             global $preview_text_limit ;
    // Strip all tags
    $desc = strip_tags(html_entity_decode($text), "<a><em>");
    $charlen = 0; $crs = 0;
    if(strlen_HTML($desc) == 0)
        $preview = substr($desc, 0, $preview_text_limit);
    else
    {
        $i = 0;
        while($charlen < 80)
        {
            $crs = strpos($desc, " ", $crs)+1;
            $lastopen = strrpos(substr($desc, 0, $crs), "<");
            $lastclose = strrpos(substr($desc, 0, $crs), ">");
            if($lastclose > $lastopen)
            {
                // we are not in a tag
                $preview = substr($desc, 0, $crs);
                $charlen = strlen_noHTML($preview);
            }
            $i++;
        }
    }
    return trim($preview)  ;

}


function strlen_noHtml($string){
    $crs = 0;
    $charlen = 0;
    $len = strlen($string);
    while($crs < $len)
    {
        $offset = $crs;
        $crs = strpos($string, "<", $offset);
        if($crs === false)
        {
           $crs = $len;
           $charlen += $crs - $offset;
        }
        else
        {
            $charlen += $crs - $offset;
            $crs = strpos($string, ">", $crs)+1;
        }
    }
    return $charlen;
}


function strlen_Html($string){
    $crs = 0;
    $charlen = 0;
    $len = strlen($string);
    while($crs < $len)
    {
        $scrs = strpos($string, "<", $crs);
        if($scrs === false)
        {
           $crs = $len;
        }
        else
        {
            $crs = strpos($string, ">", $scrs)+1;
            if($crs === false)
                $crs = $len;
            $charlen += $crs - $scrs;
        }
    }
    return $charlen;
}
//--------- print admin table -------------
function print_admin_table($content,$width="50%",$align="center"){
    print "<center><table class=grid width='$width'><tr><td align='$align'>$content</td></tr></table></center>";
    }
    
    
//------------- if Videos Cat Admin ---------
/*function if_videos_cat_admin($cat,$skip_zero_id=true){
 global $user_info,$phrases ;

 if($user_info['groupid'] != 1){
     $prm_data = db_qr_fetch("select permisions_videos from songs_user where id='$user_info[id]'");


  if($cat){

  $cats_permisions = explode(",",$prm_data['permisions_videos']);
         if(!in_array($cat,$cats_permisions)){
              print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
         die();
    }
    }else{
        if(!$skip_zero_id){
          print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
         die();
        }
    }
      }
}*/
function if_videos_cat_admin($cat,$skip_zero_id=true){
 global $user_info,$phrases ;

 if($user_info['groupid'] != 1){
  

  if($cat){
          $cat_users =get_videos_cat_users($cat,true);
              
  
         if(!in_array($user_info['id'],$cat_users)){
              print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
         die();
    }
    }else{
        if(!$skip_zero_id){
          print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
         die();
        }
    }
      }
}
//-------- Get video Cat users  -------
function get_videos_cat_users($id,$fields_only=false,$type=''){

  
   
         $fields_array = array();
         $dir_data['cat'] = intval($id) ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select id,cat from songs_videos_cats where id='$dir_data[cat]'");


   
    $data = db_qr_fetch("select `users` from songs_videos_cats where id='".$dir_data['id']."'");
    if(trim($data['users'])){
       $cat_fields = explode(",",$data['users']);
    
    for($z=0;$z<count($cat_fields);$z++){  
    if($fields_only){ 
    if(!in_array($cat_fields[$z],$fields_array)){$fields_array[]=$cat_fields[$z];}
    }else{
    $fields_array[$cat_fields[$z]]=$dir_data['id'];  
    }
    }      
    } 

        }
        
     
    

          return  $fields_array ;
}
