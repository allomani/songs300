<? 
//-----------------------------
define("MEMBER_SQL","member_sql");
//------------------------------
define("GLOBAL_LOADED",true);
//----------------------------------
define("SCRIPT_NAME","songs"); 
define("SCRIPT_VER","3.0");   
//---------------------------------------------
//----------- current work dir definition -------
define('CWD', (($getcwd = getcwd()) ? str_replace("\\","/",$getcwd) : '.'));
//define('CFN',str_replace(CWD."/",'',$_SERVER['SCRIPT_FILENAME']));
define('CFN',basename($_SERVER['SCRIPT_FILENAME']));
//---------------------------------------------

require(CWD . "/config.php");


//---------- custom error handler --------//
if($custom_error_handler){
$old_error_handler = set_error_handler("error_handler");
}  


//----- remove slashes if magic quotes -----//
function stripslashes_deep($value){
   return (is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value));
}

if(get_magic_quotes_gpc()){ 
$_POST = array_map('stripslashes_deep',$_POST);
$_GET = array_map('stripslashes_deep',$_GET); 
$_COOKIE = array_map('stripslashes_deep',$_COOKIE); 
}

//--------- extract variabls -----------------------
 if (!empty($_POST)) {extract($_POST);}
if (!empty($_GET)) {extract($_GET);}
if (!empty($_ENV)) {extract($_ENV);}
//-----------------------------------------------------


//------ clean global vars ---------//
$_SERVER['QUERY_STRING'] = strip_tags($_SERVER['QUERY_STRING']);
$_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);
$_SERVER['REQUEST_URI'] = strip_tags($_SERVER['REQUEST_URI']);

define("CUR_FILENAME",$_SERVER['PHP_SELF']);

//---------------------- common variables types clean-----------------------
 if($id){if(is_array($id)){$id=array_map("intval",$id);}else{$id=(int) $id;}}
if($cat){if(is_array($cat)){$id=array_map("intval",$cat);}else{$cat=(int) $cat;}}
//-------------------------------------------------------------------

//---------------- Cache -------------------
if($cache_srv['engine'] == 'memcache'){
   require(CWD . "/includes/functions_memcache.php") ;
}elseif($cache_srv['engine'] == 'xcache'){
   require(CWD . "/includes/functions_xcache.php") ;  
}elseif($cache_srv['engine'] == 'filecache'){
   require(CWD . "/includes/functions_filecache.php") ;  
}else{
     require(CWD . "/includes/functions_nocache.php") ;
}

cache_init();

//------------------------------------------


require(CWD . "/includes/functions_db.php") ;
//---------------------------
db_connect($db_host,$db_username,$db_password,$db_name);




 

// ------------- lang dir -------------
if($global_lang=="arabic"){
$global_dir = "rtl" ;
$global_align = "right" ;
$global_align_x = "left" ;
}else{
$global_dir = "ltr" ;
$global_align = "left" ;
$global_align_x = "right" ;
}

//--------------- Load Phrases ---------------------
$phrases = array();
$qr = db_query("select * from songs_phrases");
while($data = db_fetch($qr)){

$phrases["$data[name]"] = $data['value'] ;
        }
 //------------------------------   
 
 $settings = array();

//--------------- Get Settings --------------------------
function load_settings(){
global  $settings ;
$qr = db_query("select * from songs_settings");
while($data = db_fetch($qr)){

$settings["$data[name]"] = $data['value'] ;
        }
}

 //------------------ Load Settings ---------
load_settings();

    
   
$actions_checks = array(
"$phrases[main_page]" => 'main' ,
"$phrases[the_cats]" => 'browse.php',
"$phrases[the_singer] : $phrases[overview]" => 'singer_overview.php',
"$phrases[the_singer] : $phrases[bio]" => 'singer_bio.php', 
"$phrases[the_singer] : $phrases[the_photos]" => 'singer_photos.php',
"$phrases[the_singer] : $phrases[the_videos]" => 'singer_videos.php',  
"$phrases[the_songs]" => 'songs.php', 
"$phrases[the_songs] : $phrases[listen]" => 'listen.php', 
"$phrases[lyrics]" => 'lyrics.php',  
"$phrases[the_albums]" => 'albums.php', 
"$phrases[the_videos]" => 'videos.php',
"$phrases[the_videos] : $phrases[watch]" => 'video_watch.php', 
"$phrases[the_news]" => 'news.php',
"$phrases[pages]" => 'pages',
"$phrases[the_search]" => 'search.php' ,
"$phrases[the_votes]" => 'votes.php', 
"$phrases[the_statics]" => 'statics',
"$phrases[register]" => 'register.php' , 
"$phrases[the_profile]" => 'profile.php' ,
"$phrases[contact_us]" => 'contactus.php'
);


$permissions_checks = array(
"$phrases[urls_fields]" => 'urls_fields',
"$phrases[singers_fields]" => 'singers_fields',
"$phrases[albums_fields]" => 'album_fields',
"$phrases[songs_custom_fields]" => 'songs_fields',
"$phrases[new_songs_menu]" => 'new_songs' ,
"$phrases[new_stores_menu]" => 'new_stores' , 
"$phrases[the_templates]" => 'templates' ,
"$phrases[the_news]" => 'news' ,
"$phrases[the_phrases]" => 'phrases' ,
"$phrases[the_banners]" => 'adv',
"$phrases[the_votes]" => 'votes',
"$phrases[the_members]" => 'members',
"$phrases[the_comments]" => 'comments',
"$phrases[the_reports]" => 'reports'   
);


//---- banners -----
$banners_places = array(
"$phrases[offers_menu]"=>'offer',
"$phrases[bnr_header]"=> 'header',
"$phrases[bnr_footer]"=> 'footer',
"$phrases[bnr_open]" => 'open',
"$phrases[bnr_close]" => 'close',
"$phrases[bnr_menu]"=> 'menu',
"$phrases[bnr_listen]"=> 'listen'

);

//---- order by -----  
$orderby_checks = array(
"$phrases[add_date]" => 'id',
"$phrases[the_name]" => 'name',
"$phrases[listens]" => "listens_{$settings['default_url_id']}",
"$phrases[downloads]" => "downloads_{$settings['default_url_id']}",
"$phrases[the_most_voted]" => 'rate'
);
        

//--- privacy -----
$privacy_settings_array = array(
"0" => "$phrases[for_any_one]",
"1" => "$phrases[for_friends_only]",
"2" => "$phrases[for_no_one]");


//---- comments --------
$comments_types_phrases = array(
"song"=>"$phrases[the_songs]",
"singer"=>"$phrases[the_singers]",
"album"=>"$phrases[the_albums]",
"singer_photo"=>"$phrases[singer_photos]",
"video"=>"$phrases[the_videos]",
"news"=>"$phrases[the_news]");

$comments_types = array_keys($comments_types_phrases);
 
 
//----- reports ----- 
$reports_types_phrases = array(
"comment"=>$phrases['the_comments'],
"song"=>$phrases['the_songs'],
"video"=>$phrases['the_videos'], 
"member"=>$phrases['the_members']);

$reports_types = array_keys($reports_types_phrases);
        
//----- reting ----------
$rating_types = array('news','singer_photo','song','singer','album','video');  




$sitename = $settings['sitename'] ;
$section_name = $settings['section_name'] ;
$siteurl = "http://$_SERVER[HTTP_HOST]" ;
if(!isset($script_path)){
$script_path = trim(str_replace(rtrim(str_replace('\\', '/',$_SERVER['DOCUMENT_ROOT']),"/"),"",CWD),"/");
}
$scripturl = $siteurl . iif($script_path,"/".$script_path,"");
$upload_types = explode(',',str_replace(" ","",$settings['uploader_types']));
$mailing_email = str_replace("{domain_name}",$_SERVER['HTTP_HOST'],$settings['mailing_email']);

//------------- timezone ------------------
if($settings['timezone']){date_default_timezone_set($settings['timezone']);} 
//-------------------------------------------


//------ validate styleid functon ------
function is_valid_styleid($styleid){
if(is_numeric($styleid)){
$data = db_qr_fetch("select count(id) as num from songs_templates_cats where id='$styleid' and selectable=1");
if($data['num']){
    return true;
}else{
    return false;
    }
}else{
    return false;
}
}
//----- check if valid styleid -------
$styleid=(isset($styleid) ? intval($styleid) : get_cookie("styleid"));
if(!is_valid_styleid($styleid)){
$styleid = $settings['default_styleid'];
if(!is_valid_styleid($styleid)){
$styleid = 1;
}
}
//----- get style settings ----//
$data_style = db_qr_fetch("select images from songs_templates_cats where id='$styleid'");
$style['images'] =  iif($data_style['images'],$data_style['images'],"images");

set_cookie('styleid', intval($styleid));


//----------- Load links -----------
 $qr=db_query("select * from songs_links");
 while($data=db_fetch($qr)){
 $links[$data['name']] = $data['value'];
 }
//------- theme file ---------
require(CWD . "/includes/functions_themes.php") ;


require(CWD . "/includes/functions_members.php") ; 


init_members_connector(); 

require(CWD . '/includes/class_tabs.php') ; 

require(CWD . '/includes/functions_comments.php') ;  






//--------- Sync URLs Sets ------- 
$urls_sets = sync_urls_sets();
//-------- Sync Songs Fields Sets ---------
$songs_fields_sets = sync_songs_fields_sets();
//--------------------------



require(CWD . "/includes/functions_main.php") ; 

function if_admin($dep="",$continue=0){
        global $user_info,$phrases ;

        if(!$dep){

        if($user_info['groupid'] != 1){



        if(!$continue){

        print_admin_table("<center>$phrases[access_denied]</center>");

         die();

         }
           return false;
         }else{
                 return true;
                 }
          }else{
           if($user_info['groupid'] != 1){

                  $data=db_qr_fetch("select * from songs_user where id='$user_info[id]'");
                  $prm_array = explode(",",$data['cp_permisions']);

                  if(!in_array($dep,$prm_array)){

        if(!$continue){
         print_admin_table("<center>$phrases[access_denied]</center>");
         die();
                           }
                            return false;
                          }else{
                          return true;
                                  }
                 }else{
                         return true;
                         }
            }
         }
//----------------- 
 
 function utf8_substr($str,$from,$len){
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $from .'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $len .'}).*#s','$1', $str);
}

require (CWD . "/includes/letters_groups.php");

//-------------------------------------------------------------
function get_image($src,$default=''){
         if($src){
              return $src ;
            }else{

    return iif($default,$default,"images/no_pic.gif") ;
    }
    }
//------------ copyrights text ---------------------
function print_copyrights(){
global $_SERVER,$settings,$copyrights_lang ;

if(COPYRIGHTS_TXT_MAIN){
if($copyrights_lang == "arabic"){
print "<p align=center>جميع الحقوق محفوظة لـ :
<a target=\"_blank\" href=\"http://$_SERVER[HTTP_HOST]\">$settings[copyrights_sitename]</a> © " . date('Y') . " <br>
برمجة <a target=\"_blank\" href=\"http://allomani.com/\"> اللوماني للخدمات البرمجية </a> © 2011";
}else{
print "<p align=center>Copyright © ". date('Y')." <a target=\"_blank\" href=\"http://$_SERVER[HTTP_HOST]\">$settings[copyrights_sitename]</a> - All rights reserved <br>
Programmed By <a target=\"_blank\" href=\"http://allomani.com/\"> Allomani </a> © 2011";
    }
}
        }

//---------------------- Read File ------------------------
function read_file($filename){
$fn = fopen($filename,"r");
$fdata = fread($fn,filesize($filename));
fclose($fn);
return $fdata ;
}


//------ singer url name ----------
function singer_url_name($page_name,$name){
    
$page_name=str_replace(" ","-",trim($page_name));
    
    if(!$page_name && $name){
    $page_name = str_replace(" ","-",trim($name));
    $page_name = convert2en($page_name);     
    }
 
 return $page_name;       
}

//-------- singer url ------------
function singer_url($id,$page_name="",$name="",$album_id="",$orderby="",$sort=""){
    global $links;
    
    
    $pname =  singer_url_name($page_name,$name);
  //  if($pname){
  
   if($orderby){
    return  str_replace(array('{id}','{album_id}','{orderby}','{sort}','{name}'),array($id,$album_id,$orderby,$sort,$pname),$links['singer_w_pages']);
   }else{
    return str_replace(array('{id}','{name}'),array($id,$pname),$links['singer']);
   }
  //  }else{
  //    return str_replace('{id}',$id,$links['singer']);    
  //  }
    
}
//-------- album url ------------
function album_url($id,$page_name,$name,$singer_id,$singer_page_name,$singer_name){
  
   global $links;
    
    $page_name=str_replace(" ","-",trim($page_name));
    
    if(!$page_name){
    $page_name = str_replace(" ","-",trim($name));
    $page_name = convert2en($page_name);     
    }
    
    
    $singer_page_name=str_replace(" ","-",trim($singer_page_name));
    
    if(!$singer_page_name){
    $singer_page_name = str_replace(" ","-",trim($singer_name));
    $singer_page_name = convert2en($singer_page_name);     
    }
    
    
    
    
    if($page_name){
    return str_replace(array('{id}','{name}','{singer_id}','{singer_name}'),array($id,$page_name,$singer_id,$singer_page_name),$links['album_w_name']);
    }else{
      return str_replace(array('{id}','{singer_id}'),array($id,$singer_id),$links['album']);    
    }
      
}

//----------- cat url ------------
function cat_url($id,$page_name="",$name=""){
     global $links;
    
    $page_name=str_replace(" ","-",trim($page_name));
    
    if(!$page_name){
    $page_name = str_replace(" ","-",trim($name));
    $page_name = convert2en($page_name);     
    }
    
  //  if($page_name){
    return str_replace(array('{id}','{name}'),array($id,$page_name),$links['cat']);
  //  }else{
  //    return str_replace('{id}',$id,$links['cat']);    
   // }
}
//---------- validate email --------
function check_email_address($email) {
if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
    return false;
}else{
    return true;
}
}
        
        
//---------------------------------------------
function execphp_fix_tag($match)
{
        // replacing WPs strange PHP tag handling with a functioning tag pair
        $output = '<?php'. $match[2]. '?>';
        return $output;
}
//------------------------------------------------------------
function run_php($content)
{

$content = str_replace(array("&#8216;", "&#8217;"), "'",$content);
$content = str_replace(array("&#8221;", "&#8220;"), '"', $content);
$content = str_replace("&Prime;", '"', $content);
$content = str_replace("&prime;", "'", $content);
        // for debugging also group unimportant components with ()
        // to check them with a print_r($matches)
        $pattern = '/'.
                '(?:(?:<)|(\[))[\s]*\?php'. // the opening of the <?php or [?php tag
                '(((([\'\"])([^\\\5]|\\.)*?\5)|(.*?))*)'. // ignore content of PHP quoted strings
                '\?(?(1)\]|>)'. // the closing ? > or ?] tag
                '/is';
      $content = preg_replace_callback($pattern, 'execphp_fix_tag', $content);
        // to be compatible with older PHP4 installations
        // don't use fancy ob_XXX shortcut functions
        ob_start();
       $eval_result =   eval(" ?> $content ");


        $output = ob_get_contents();
        ob_end_clean();
        print $output;
        return $eval_result;
}
//---------------------------- Admin Login Function ---------------------------------
$user_info = array();
function check_admin_login(){
      global $user_info;

$user_info['username'] = get_cookie('admin_username');
$user_info['password'] = get_cookie('admin_password');
$user_info['id'] = intval(get_cookie('admin_id'));
 
    
   if($user_info['id']){
     
   $qr = db_query("select * from songs_user where id='$user_info[id]'");
         if(db_num($qr)){
           $data = db_fetch($qr);
           if($data['username'] == $user_info['username'] && md5($data['password']) == $user_info['password']){
                   $user_info['email'] = $data['email'];
           $user_info['groupid'] = $data['group_id'];
                   return true ;
                   }else{
                           return false ;
                           }

                 }else{
                         return false ;
                         }

           }else{
                   return false ;
                   }

        }
 

//--------- Generate Random String -----------
function rand_string($length = 8){

  // start with a blank password
  $password = "";

  // define possible characters
  $possible = "0123456789bcdfghjkmnpqrstvwxyz";

  // set up a counter
  $i = 0;

  // add random characters to $password until $length is reached
  while ($i < $length) {

    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) {
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;

}



//---------------------- Send Email Function -------------------
function send_email($from_name,$from_email,$to_email,$subject,$msg,$html=0,$encoding=""){
        global $PHP_SELF,$smtp_settings,$settings ;
//  $from_name = utf8_encode($from_name);
    //$from_email = htmlspecialchars($from_email);
   // $to_email = htmlspecialchars($to_email);
  //  $subject = utf8_encode($subject);
   // $msg=htmlspecialchars($msg);

if(!$encoding){$encoding =  $settings['site_pages_encoding'];}

 //   $from = $from_name." <".$from_email.">" ;
 $from = "=?".$encoding."?B?".base64_encode($from_name)."?= <$from_email>" ;
 $subject = "=?".$encoding."?B?".base64_encode($subject)."?=";     

    $mailHeader  = 'From: '.$from.' '."\r\n";
    $mailHeader .= "Reply-To: $from_email\r\n";
    $mailHeader .= "Return-Path: $from_email\r\n";
    
    
     if($smtp_settings['enable']){  
    $mailHeader .= "To: $to_email\r\n";
     }
     
     
    $mailheader.="MIME-Version: 1.0\r\n";
    $mailHeader .= "Content-Type: ".iif($html,"text/html","text/plain")."; charset=".($encoding ? $encoding : $settings['site_pages_encoding'])."\r\n";
    
     if($smtp_settings['enable']){  
    $mailHeader .= "Subject: $subject\r\n";
     }
     
     
    $mailHeader .= "Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")."\r\n";
    $mailHeader .= "X-EWESITE: Allomani\r\n";
    $mailHeader .= "X-Mailer: PHP/".phpversion()."\r\n";
    $mailHeader .= "X-Mailer-File: "."http://".$_SERVER['HTTP_HOST'].($script_path ? "/".$script_path:"").$PHP_SELF."\r\n";
    $mailHeader .= "X-Sender-IP: {$_SERVER['REMOTE_ADDR']}\r\n";



    
    if($smtp_settings['enable']){

   if(!class_exists("smtp_class")){
   require_once(CWD ."/includes/class_smtp.php");
   }

   $smtp=new smtp_class;

    $smtp->host_name=$smtp_settings['host_name'];
    $smtp->host_port=$smtp_settings['host_port'];
    $smtp->ssl=$smtp_settings['ssl'];
    $smtp->localhost="localhost";       /* Your computer address */
    $smtp->direct_delivery=0;           /* Set to 1 to deliver directly to the recepient SMTP server */
    $smtp->timeout=$smtp_settings['timeout'];    /* Set to the number of seconds wait for a successful connection to the SMTP server */
    $smtp->data_timeout=0;              /* Set to the number seconds wait for sending or retrieving data from the SMTP server.
                                           Set to 0 to use the same defined in the timeout variable */
    $smtp->debug=$smtp_settings['debug'];                     /* Set to 1 to output the communication with the SMTP server */
    $smtp->html_debug=1;                /* Set to 1 to format the debug output as HTML */

    if($smtp_settings['username'] && $smtp_settings['password']){
    $smtp->pop3_auth_host=$smtp_settings['host_name'];           /* Set to the POP3 authentication host if your SMTP server requires prior POP3 authentication */
    $smtp->user=$smtp_settings['username'];                     /* Set to the user name if the server requires authetication */
     $smtp->password=$smtp_settings['password'];                 /* Set to the authetication password */
    $smtp->realm="";                    /* Set to the authetication realm, usually the authentication user e-mail domain */
    }

    $smtp->workstation="";              /* Workstation name for NTLM authentication */
    $smtp->authentication_mechanism=""; /* Specify a SASL authentication method like LOGIN, PLAIN, CRAM-MD5, NTLM, etc..
                                           Leave it empty to make the class negotiate if necessary */

   $mailResult =  $smtp->SendMessage(
        $from_email,
        array(
            $to_email
        ),
        array(
            $mailHeader
        ),
        $msg,0);

        if($mailResult){
              return true ;
                }else{
                    if($smtp_settings['show_errors']){
                    print "<b>SMTP Error: </b> ".$smtp->error ."<br>";
                    }
               return false;
               }

    }else{
    $mailResult = @mail($to_email,$subject,$msg,$mailHeader);

               if($mailResult){
              return true ;
                }else{
               return false;
               }
    }
        }

//----------- Get Hooks ------------
function get_plugins_hooks(){

$hooklocations = array();
    require_once(CWD . '/includes/class_xml.php');
    $handle = opendir(CWD . '/xml/');
    while (($file = readdir($handle)) !== false)
    {
        if (!preg_match('#^hooks_(.*).xml$#i', $file, $matches))
        {
            continue;
        }
        $product = $matches[1];

        $phrased_product = $products[($product ? $product : 'allomani')];
        if (!$phrased_product)
        {
            $phrased_product = $product;
        }

        $xmlobj = new XMLparser(false, CWD . "/xml/$file");
        $xml = $xmlobj->parse();

        if (!is_array($xml['hooktype'][0]))
        {
            // ugly kludge but it works...
            $xml['hooktype'] = array($xml['hooktype']);
        }

        foreach ($xml['hooktype'] AS $key => $hooks)
        {
            if (!is_numeric($key))
            {
                continue;
            }
            //$phrased_type = isset($vbphrase["hooktype_$hooks[type]"]) ? $vbphrase["hooktype_$hooks[type]"] : $hooks['type'];
            $phrased_type =  $hooks['type'];
            $hooktype = $phrased_product . ' : ' . $phrased_type;

            $hooklocations["$hooktype"] = array();

            if (!is_array($hooks['hook']))
            {
                $hooks['hook'] = array($hooks['hook']);
            }

            foreach ($hooks['hook'] AS $hook)
            {
                $hookid = (is_string($hook) ? $hook : $hook['value']);
                $hooklocations["$hooktype"]["$hookid"] = $hookid;
            }
        }
    }
    ksort($hooklocations);
    return $hooklocations ;
    }

//--------- Get used hooks List -----------
$qr = db_query("select hookid from songs_hooks where active='1'");
while($data = db_fetch($qr)){
$used_hooks[] = $data['hookid'];
}
unset($qr,$data);
//-------------- compile hook --------------
function compile_hook($hookid){
global $used_hooks;
if(is_array($used_hooks)){
if(in_array($hookid,$used_hooks)){
$qr = db_query("select code from songs_hooks where hookid='".db_escape($hookid)."' and active='1' order by ord asc");
if(db_num($qr)){
while($data=db_fetch($qr)){
run_php($data['code']);
    }
}else{
 return false;
 }
 }else{
     return false;
     }
     }else{
         return false;
         }
}

//--------- iif expression ------------
function iif($expression, $returntrue, $returnfalse = '')
{
    return ($expression ? $returntrue : $returnfalse);
}

//------- set cookies function -----------
function set_cookie($name,$value="",$expire=0){
global $cookies_prefix,$cookies_timemout,$cookies_path,$cookies_domain;
$name = $cookies_prefix . $name;
if($expire){
    $k_timeout = $expire;
}else{
$k_timeout = time() + (60 * 60 * 24 * intval($cookies_timemout));
}

setcookie($name, $value, $k_timeout,$cookies_path,$cookies_domain);
}
//--------- get cookies funtion ---------
function get_cookie($name){
global $cookies_prefix,$_COOKIE;
$name = $cookies_prefix . $name;
return $_COOKIE[$name];
}


//--------- array replace --------
if(!function_exists('array_replace')){
function array_replace($tofind, $toreplace,$a){

if(!is_array($a)){$a = array($a);}

for($i=0;$i<count($a);$i++){
$a[$i] = str_replace($tofind,$toreplace,$a[$i]);
}

return $a ;
}
}

//---------- Flush Function -------------
function data_flush()
{
    static $output_handler = null;
    if ($output_handler === null)
    {
        $output_handler = @ini_get('output_handler');
    }

    if ($output_handler == 'ob_gzhandler')
    {
        // forcing a flush with this is very bad
        return;
    }

    flush();
    if (PHP_VERSION  >= '4.2.0' AND function_exists('ob_flush') AND function_exists('ob_get_length') AND ob_get_length() !== false)
    {
        @ob_flush();
    }
    else if (function_exists('ob_end_flush') AND function_exists('ob_start') AND function_exists('ob_get_length') AND ob_get_length() !== FALSE)
    {
        @ob_end_flush();
        @ob_start();
    }
}

//----------- select row ------------
function print_select_row($name, $array, $selected = '', $options="" , $size = 0, $multiple = false,$same_values=false)
{
    global $vbulletin;

    $select = "<select name=\"$name\" id=\"sel_$name\"" . iif($size, " size=\"$size\"") . iif($multiple, ' multiple="multiple"') . iif($options , " $options").">\n";
    $select .= construct_select_options($array, $selected,$same_values);
    $select .= "</select>\n";

    print $select;
}


function construct_select_options($array, $selectedid = '',$same_values=false)
{
    if (is_array($array))
    {
        $options = '';
        foreach($array AS $key => $val)
        {
            if (is_array($val))
            {
                $options .= "\t\t<optgroup label=\"" . $key . "\">\n";
                $options .= construct_select_options($val, $selectedid, $tabindex, $htmlise);
                $options .= "\t\t</optgroup>\n";
            }
            else
            {
                if (is_array($selectedid))
                {
                    $selected = iif(in_array($key, $selectedid), ' selected="selected"', '');
                }
                else
                {
                    $selected = iif($key == $selectedid, ' selected="selected"', '');
                }
                $options .= "\t\t<option value=\"".($same_values ? $val : $key). "\"$selected>" . $val . "</option>\n";
            }
        }
    }
    return $options;
}
//---------- print text row ----------
function print_text_row($name,$value="",$size="",$dir="",$options=""){
print "<input type=text name=\"$name\"".iif($value," value=\"$value\"").iif($size," size=\"$size\"").iif($dir," dir=\"$dir\"").iif($options," $options").">";
}




 //--------------- Get file Extension ----------
 function file_extension($filename)
{
    return substr(strrchr($filename, '.'), 1);
}


//----------- Number Format --------------------
function convert_number_format($number, $decimals = 0, $bytesize = false, $decimalsep = null, $thousandsep = null)
{

    $type = '';

    if (empty($number))
    {
        return 0;
    }
    else if (preg_match('#^(\d+(?:\.\d+)?)(?>\s*)([mkg])b?$#i', trim($number), $matches))
    {
        switch(strtolower($matches[2]))
        {
            case 'g':
                $number = $matches[1] * 1073741824;
                break;
            case 'm':
                $number = $matches[1] * 1048576;
                break;
            case 'k':
                $number = $matches[1] * 1024;
                break;
            default:
                $number = $matches[1] * 1;
        }
    }

    if ($bytesize)
    {
        if ($number >= 1073741824)
        {
            $number = $number / 1073741824;
            $decimals = 2;
            $type = " GB";
        }
        else if ($number >= 1048576)
        {
            $number = $number / 1048576;
            $decimals = 2;
            $type = " MB";
        }
        else if ($number >= 1024)
        {
            $number = $number / 1024;
            $decimals = 1;
            $type = " KB";
        }
        else
        {
            $decimals = 0;
            $type = " Byte";
        }
    }

    if ($decimalsep === null)
    {
     //   $decimalsep = ".";
    }
    if ($thousandsep === null)
    {
    //    $thousandsep = ",";
    }

    if($decimalsep && $thousandsep){
    return str_replace('_', '&nbsp;', number_format($number, $decimals, $decimalsep, $thousandsep)) . $type;
    }else{
         return str_replace('_', '&nbsp;', round($number,$decimals)) . $type;
    }
}



//------------- convert ar 2 en ------------------

function convert2en($filename){
    
if(!preg_match("/^([-a-zA-Z0-9_.!@#$&*+=|~%^()\/\\'])*$/", $filename)){
$filename= str_replace("'","",$filename);
$filename= str_replace(" ","_",$filename);
$filename= str_replace("ء","a",$filename); 
$filename= str_replace("ا","a",$filename);
$filename= str_replace("أ","a",$filename);
$filename= str_replace("آ","a",$filename); 
$filename= str_replace("ـ","",$filename);      
$filename= str_replace("إ","i",$filename);
$filename= str_replace("ب","b",$filename);
$filename= str_replace("ت","t",$filename);
$filename= str_replace("ث","th",$filename);
$filename= str_replace("ج","g",$filename);
$filename= str_replace("ح","7",$filename);
$filename= str_replace("خ","k",$filename);
$filename= str_replace("د","d",$filename);
$filename= str_replace("ذ","d",$filename);
$filename= str_replace("ر","r",$filename);
$filename= str_replace("ز","z",$filename);
$filename= str_replace("س","s",$filename);
$filename= str_replace("ش","sh",$filename);
$filename= str_replace("ص","s",$filename);
$filename= str_replace("ض","5",$filename);
$filename= str_replace("ع","a",$filename);
$filename= str_replace("غ","gh",$filename);
$filename= str_replace("ف","f",$filename);
$filename= str_replace("ق","k",$filename);
$filename= str_replace("ك","k",$filename);
$filename= str_replace("ل","l",$filename);
$filename= str_replace("ن","n",$filename);
$filename= str_replace("ه","h",$filename);
$filename= str_replace("ي","y",$filename);
$filename= str_replace("ط","6",$filename);
$filename= str_replace("ظ","d",$filename);
$filename= str_replace("و","w",$filename);
$filename= str_replace("ؤ","o",$filename);
$filename= str_replace("ئ","i",$filename);
$filename= str_replace("لا","la",$filename);
$filename= str_replace("لأ","la",$filename);
$filename= str_replace("ى","a",$filename);
$filename= str_replace("ة","t",$filename);
$filename= str_replace("م","m",$filename);
}


return $filename ;

}

   

//---------- Pages Links ---------//
function print_pages_links($start,$items_count,$items_perpage,$page_string){
     global $f_page_string,$nextpag,$prevpag,$f_items_perpage,$f_start,$f_end,$start,$phrases,$f_cur_page,$f_pages;
 
  
  
  
$pages=intval($items_count/$items_perpage);
if ($items_count%$items_perpage){$pages++;}


$pages_line_limit = 8;
$pages_line_min = $pages_line_limit / 2;

$f_cur_page = iif($start,($start/$items_perpage)+1,1);
$f_start =  iif($f_cur_page <= $pages_line_min,1,$f_cur_page-$pages_line_min);
$f_end = iif($pages < $pages_line_min,$pages,iif($f_start+$pages_line_limit <=$pages,$f_start+$pages_line_limit ,$pages)) ;


if ($items_count>$items_perpage){
    
  $f_page_string = $page_string;
  $f_items_perpage =  $items_perpage;
  $f_pages = $pages;
  
  
run_template('pages_links');
}
}


//------- array remove empty values --------//
function array_remove_empty_values($arr){
       for($i=0;$i<count($arr);$i++){
         $key = key($arr);
            $value = current($arr);
           if($value){
               $new_arr[$key] = $value;
           }
            next($arr);    
       }
       return  $new_arr;
   } 
   
   
   
   // ---- Date / Time -------
   function datetime($format="",$time=""){
       return date(iif($format,$format,"Y-m-d h:i:s"),iif($time,$time,time()));
   }
   
   
   //------- Error Handler ----------//
   function error_handler($errno, $errstr, $errfile, $errline,$vars) {
        global $display_errors,$log_errors;
       
       switch ($errno)
    {
        case E_WARNING:
        case E_USER_WARNING:
            /* Don't log warnings due to to the false bug reports about valid warnings that we suppress, but still appear in the log
            */

            if($log_errors){ 
            $message = "Warning: $errstr in $errfile on line $errline";
            do_error_log($message, 'php');
            }
           

            if (!$display_errors || !error_reporting())
            {
                return;
            }
            
            $errfile = str_replace(CWD.DIRECTORY_SEPARATOR, '', $errfile);
            echo "<br /><strong>Warning</strong>: $errstr in <strong>$errfile</strong> on line <strong>$errline</strong><br />";
        break;

        case E_USER_ERROR:  
            
            if($log_errors){ 
            $message = "Fatal error: $errstr in $errfile on line $errline";
            do_error_log($message, 'php');
            }
            
            
            if ($display_errors)
            {
                $errfile = str_replace(CWD.DIRECTORY_SEPARATOR, '', $errfile);
                echo "<br /><strong>Fatal error:</strong> $errstr in <strong>$errfile</strong> on line <strong>$errline</strong><br />";
            }
            exit;
        break;
    }
}

//--------- Error Log ---------//
function do_error_log($msg , $type='php'){ 
global $logs_path,$log_max_size,$custom_error_handler;

$trace =  @debug_backtrace() ;
 
 //$args = (array) $trace[1]['args'];
  //".implode(",",$args)."
       
$dt = date("Y-m-d H:i:s (T)");
$err = $dt." : ".$msg."\r\n";
if($trace[1]['function']){
$err .=$trace[1]['function']."() in : ".$trace[1]['file'].":".$trace[1]['line']."\r\n";
}
$err .= "-------------- \r\n";

 if($custom_error_handler){
if(!file_exists($logs_path)){@mkdir($logs_path);}
 
  if($type=="db"){
  $log_file =  "$logs_path/error_db.log" ;
  $log_file_new  = "$logs_path/error_db_".date("Y_m_d_h_i_s").".log" ;  
  }else{
       $log_file =  "$logs_path/error.log" ;
  $log_file_new  = "$logs_path/error_".date("Y_m_d_h_i_s").".log" ;              
  }  
                
    if(@filesize($log_file) >= $log_max_size){
    @rename($log_file,$log_file_new);   
    }
    
      error_log($err, 3, $log_file);  
 }else{
      error_log($err);   
 } 
  
}
                  
//------- print block banners -----------//
  function print_block_banners($data_array,$pos="block"){
         global $data;
              foreach($data_array as $data){
              
                  
                  $ids[] = $data['id'] ;
                  
              if($pos=="block"){
                print "<tr>
                <td  width=\"100%\" valign=\"top\">";
              }
              
     if($data['c_type']=="code"){
    run_php($data['content']);
    }else{              
   $template = iif($pos=="center","center_banners","blocks_banners");
    run_template($template);
   }
    if($pos=="block"){  
                print "</td>
        </tr>";
    }
        }
        
        if(is_array($ids)){
        db_query("update songs_banners set views=views+1 where id IN (".implode(",",$ids).")");
        
        }        
       }
  
//--------- Sync URLs Sets ---------
function sync_urls_sets(){
$qr_links = db_query("select * from songs_urls_fields where active=1 order by ord");
$x = 0;
while($data_links = db_fetch($qr_links)){ 
    $urls_sets[$x] = $data_links;
/*$urls_sets[$x]['id'] = $data_links['id'] ;
$urls_sets[$x]['show_listen'] = $data_links['show_listen'];
$urls_sets[$x]['show_download'] = $data_links['show_download'];
$urls_sets[$x]['download_icon'] = $data_links['download_icon'];
$urls_sets[$x]['listen_icon'] = $data_links['listen_icon'];
$urls_sets[$x]['download_alt'] = $data_links['download_alt'];  
$urls_sets[$x]['listen_alt'] = $data_links['listen_alt']; 
$urls_sets[$x]['listen_for_members'] = $data_links['listen_for_members'];
$urls_sets[$x]['listen_alt'] = $data_links['listen_alt'];
$urls_sets[$x]['listen_alt'] = $data_links['listen_alt'];*/

$x++;  
} 
unset($qr_links,$data_links,$x);
return $urls_sets;
}
//--------- Sync Songs Fields Sets ---------
/*
function sync_songs_fields_sets(){
$qr_fields = db_query("select * from songs_custom_sets where active=1 order by ord");
$x = 0;
while($data_fields = db_fetch($qr_fields)){ 
$songs_fields_sets[$x]['id'] = $data_fields['id'] ;
$songs_fields_sets[$x]['name'] = $data_fields['name'];
$x++;  
} 
unset($data_fields,$qr_fields,$x);
return $songs_fields_sets;
} */

function sync_songs_fields_sets(){
$qrf = db_query("select * from songs_songs_fields where active=1 order by ord");
   if(db_num($qrf)){

while($dataf = db_fetch($qrf)){
    
 $custom_fields[] = array("id"=>$dataf['id'] ,"name"=> $dataf['name'],"search"=>$dataf['enable_search']); 

}
   }
   $custom_fields = (array) $custom_fields;
   
return $custom_fields;
}

//--------- Get Singers Fields Sets ---------
function get_singers_fields_sets(){
$qrf = db_query("select * from songs_singers_fields where active=1 order by ord");
   if(db_num($qrf)){

while($dataf = db_fetch($qrf)){
    
 $custom_fields[] = array("id"=>$dataf['id'] ,"name"=> $dataf['name'],"search"=>$dataf['enable_search']); 

}
   }
   $custom_fields = (array) $custom_fields;
   
return $custom_fields;
}


//--------- Get albums Fields Sets ---------
function get_albums_fields_sets(){
$qrf = db_query("select * from songs_albums_fields where active=1 order by ord");
   if(db_num($qrf)){

while($dataf = db_fetch($qrf)){
    
 $custom_fields[] = array("id"=>$dataf['id'] ,"name"=> $dataf['name'],"search"=>$dataf['enable_search']); 

}
   }
   $custom_fields = (array) $custom_fields;
   
return $custom_fields;
}

//------------ song listen permission -------------------//
function song_listen_permission($id,$cat=1){
    return song_download_permission($id,$cat,'listen_for_members');
}
//----------- Song download permission -----------
function song_download_permission($id,$cat=1,$field_name='download_for_members'){
global $urls_sets ;

               foreach($urls_sets as $set){
                   if($set['id'] == $cat){
                   $set_data = $set;
                   break;
                   }    
               }
               
               
if($set_data[$field_name]==2){
return true;
}elseif($settings[$field_name]==0){

$data_user = db_qr_fetch("select songs_cats.{$field_name} from songs_cats,songs_singers,songs_songs where songs_cats.id=songs_singers.cat and songs_singers.id=songs_songs.album and songs_songs.id='$id'");
  
if($data_user[$field_name]){

if(check_member_login()){
return true;
}else{
return false;
}

    }else{
    return true;
        }
}elseif($settings[$field_name]==1){
if(check_member_login()){
return true;
}else{
return false;
}
}
}
//----------- Video download permission -----------
function video_download_permission($id){
global $settings ;

if($settings['videos_member_download_only']==1){
if(check_member_login()){
return true;
}else{
return false;
}
}elseif($settings['videos_member_download_only']==2){

$data_user = db_qr_fetch("select songs_videos_cats.download_limit from songs_videos_cats,songs_videos_data where songs_videos_cats.id=songs_videos_data.cat and songs_videos_data.id='$id'");

if($data_user['download_limit']){

if(check_member_login()){
return true;
}else{
return false;
}

    }else{
    return true;
        }
}else{
return true;
}
//---------------------------------
}

 //----------- get playlist Item --------------//
 function get_playlist_item($id,$song_id=0,$with_div=1){
     global $member_data;
 if(check_member_login()){
       $id = intval($id);
       $song_id = intval($song_id);
       
 if(!$song_id){
 $data = db_qr_fetch("select song_id from songs_playlists_data where id='$id' and member_id='$member_data[id]'");
 $song_id = intval($data['song_id']);
 unset($data);
 } 
      
 $data = db_qr_fetch("select songs_songs.name,songs_singers.name as singer_name from songs_songs,songs_singers where songs_singers.id=songs_songs.album and songs_songs.id='$song_id'");
 
if($with_div){
    print "<div id=\"playlist_item_$id\">";
}

print "
<input type=hidden name=\"song_id[]\" value=\"$song_id\">
<table width=100%>
<tr><td width=10><span style=\"cursor: move;\" class=\"handle\"><img src='images/move_small.gif'></span></td>
<td>".iif($data['name'],$data['singer_name']." - ".$data['name'],"-")."</td>
<td width=10><a href=\"javascript:playlist_delete_song($id);\"><img src='images/del_small.gif' border=0></a></td>
</tr></table>";

if($with_div){
print "</div>";
}
 }
 } 
 
 
//---------------- print link ---------------
function print_link($name,$href,$title="",$blank=false){
    return "<a href=\"$href\" title=\"".iif($title,$title,$name)."\"".iif($blank," target=_blank").">$name</a>";
}
//------------ print image ----------------
function print_image($src,$title="",$options=""){
    return "<img src=\"$src\" title=\"$title\" alt=\"$title\" border=0".iif($options," ".$options).">";
}

  //--------------------- Delete File ---------------
function delete_file($filename){
    
 if(file_exists($filename)){
         @unlink($filename);
 }
        }
        
  //----- rating stars --------
  function print_rating($type,$id,$rating=0,$readonly=false){
      global $style;
      
      print "
       <div id=\"".$type.$id."_rating_div\" dir=ltr></div> 
         <div id=\"".$type.$id."_rating_status_div\" dir=ltr></div>
         <div id='".$type.$id."_rating_loading_div' style=\"display:none;\"><img src='images/loading.gif'></div>";
        
      ?>     
    <script>
 jQuery('#<?=$type.$id."_"?>rating_div').raty({
     start:     <?=$rating?>,
     showHalf:  true,
     readOnly: <?=iif($readonly,"true","false")?>,
     hintList:        ['1/5', '2/5', '3/5', '4/5', '5/5'],
     path: '<?=$style['images']?>/',
     onClick: function(score) {
    rating_send('<?=$type?>',<?=$id?>,score);
  }
 });   
 
</script>
<?
}

//---- time duration ----       
function time_duration($seconds, $use = null, $zeros = false)
{
    global $phrases;
    // Define time periods
    $periods = array (
        'years'     => 31556926,
        'Months'    => 2629743,
        'weeks'     => 604800,
        'days'      => 86400,
        'hours'     => 3600,
        'minutes'   => 60,
        'seconds'   => 1
        );
        
        $periods_names = array (
        'years'     => $phrases['year_ago'],
        'Months'    => $phrases['months_ago'],
        'weeks'     => $phrases['weeks_ago'],
        'days'      => $phrases['days_ago'],
        'hours'     => $phrases['hours_ago'],
        'minutes'   => $phrases['minutes_ago'],
        'seconds'   => $phrases['seconds_ago']
        );
        
        

    // Break into periods
    $seconds = (float) $seconds;
    $segments = array();
    foreach ($periods as $period => $value) {
        if ($use && strpos($use, $period[0]) === false) {
            continue;
        }
        $count = floor($seconds / $value);
        if ($count == 0 && !$zeros) {
            continue;
        }
        $segments[$period] = $count;
        $seconds = $seconds % $value;
    }

     if(count($segments)==0){$segments['seconds']=1;}
    // Build the string
    $string = array();
    
    foreach ($segments as $key => $value) {
        
  
    
        $segment = $value . ' ' . $periods_names[$key];
      
        $string[] = $segment;
        break;
    }

    return "$phrases[since] ".implode(', ', $string);  
}


//---- Js Redirect -----//
function js_redirect($url,$with_body=false){
    global $phrases;
    
if($with_body){
print "<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$settings[site_pages_encoding]\" />
<head>
</head><body>";
}

print "<script>
var url = \"$url\";
 var a = document.createElement(\"a\");
 if(!a.click) { 
  window.location = url;
 }else{
 a.setAttribute(\"href\", url);
 a.style.display = \"none\";
 document.body.appendChild(a);
 a.click();
 }
 </script>
 
 <center> $phrases[redirection_msg] <a href=\"$url\">$phrases[click_here]</a></center>";
   
 }
 
 
//---------- get  players  --------------
function get_players($id=""){
    $qr=db_query("select * from songs_players ".iif($id,"where id='$id'")." order by id asc");
    $i=0;
    while($data=db_fetch($qr)){
        $players[$i] = $data;
            
        $players[$i]['exts'] = explode(",",strtolower($data['exts']));
         
        $i++;
    }
    
    return $players;
}

//------------ get file player -----------
function get_player_data($url,$players = array()){

$players = (array) $players;

if(!count($players)){
    $players = get_players();
}


    if(count($players)){
      
        foreach($players as $player){
            if($player['id'] != 1){
            if(is_array($player['exts'])){
                
              foreach($player['exts'] as $ext){     
                  if(strchr(strtolower($url),$ext)){
                   $player_data=$player;
                   $found=true;
                   break;  
                  }
              }  
            }    
            }
        if($found){break;}
        }
    
    if($found){
        return $player_data; 
    }else{
        return $players[0];
    }  
    }else{
        return false;
    }
}


function run_player($url,$handler='int_content',$playes=array()){
global $scripturl,$settings,$phrases,$data;

if(!$handler){$handler='int_content';}
$player_data = get_player_data($url); 
$content = iif($player_data[$handler],$player_data[$handler],$player_data['int_content']);



$url = iif(!strchr($url,"://"),$scripturl."/".$url,$url); 
run_php(str_replace("{url}",$url,$content)); 
      
}

//----- valueof -------------//
function valueof($data,$index){
    return $data[$index];
}

 //------ video cat path str -----
function get_videos_cat_path_str($cat){
             $dir_data['cat'] = intval($cat) ;
               $path_arr[] = $dir_data['cat'];
while($dir_data['cat']!=0){
   
   $dir_data = db_qr_fetch("select id,cat from songs_videos_cats where id='$dir_data[cat]'");
   $path_arr[] = $dir_data['cat'];
}
return implode(",",$path_arr);
}

  





//--------------- date -------------
function get_date($time=0,$format=""){
    global $arabic_date_months,$arabic_date_days,$settings;
    
    if(!$time){$time = time();}
    if(!$format){$format = $settings['date_format'];}
    $date =  date($format,$time);
    if($arabic_date_months){
        $date = str_ireplace(array("jan","feb","mar","apr","may","jun","Jul","aug","sep","oct","nov","dec"),
        array("يناير","فبراير","مارس","ابريل","مايو","يونيو","يوليو","اغسطس","سبتمبر","اكتوبر","نوفمبر","ديسمبر"),
        $date);
    }
    
   if($arabic_date_days){
        $date = str_ireplace(array("sat","sun","mon","tue","wed","thu","fri"),
        array("السبت","الاحد","الاثنين","الثلاثاء","الأربعاء","الخميس","الجمعة"),
        $date);
   }
    
    return $date;
}



function create_thumb($filename,$width=65,$height=65,$fixed=false,$suffix='',$replace_exists=false,$save_filename=''){
  require_once(CWD .'/includes/class_img_resize.php');

  if(function_exists("ImageCreateTrueColor") && file_exists(CWD . "/$filename")){

  
      
   if($fixed){$option = 'crop';}else{$option='auto';}
   
    $resizeObj = new resize(CWD . "/". $filename);
    if($resizeObj){
    // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
    $resizeObj -> resizeImage($width, $height, $option);


    
$imtype = file_extension(CWD . "/$filename");
if($save_filename){
 $save_name  =  $save_filename ;
 $save_path = str_replace("/".basename($filename),'',$filename);   
   
}else{
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
 }

    // *** 3) Save image
  
    $resizeObj -> saveImage(CWD . "/" .$save_path."/".$save_name, 100);
    return ($save_path."/".$save_name) ; 
    }else{
        return false;
    }
  }else{
      return false;
  }
 }
//--------- load plugins function --------     
   function load_plugins($file){
       $dhx = @opendir(CWD ."/plugins");
while ($rdx = @readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/".$file ;
        if(@file_exists($cur_fl)){
             $pl_files[] =     $cur_fl ; 
                }
          }

    }
@closedir($dhx);

return $pl_files;
   }
   
   

 //--------------- Load Global Plugins --------------------------
  $pls = load_plugins("global.php");
  if(is_array($pls)){foreach($pls as $pl){include($pl);}}

?>