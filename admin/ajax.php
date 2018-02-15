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

chdir('./../');
define('CWD', (($getcwd = str_replace("\\","/",getcwd())) ? $getcwd : '.'));
define('IS_ADMIN', 1);
$is_admin =1 ;

include_once(CWD . "/global.php") ;
header("Content-Type: text/html;charset=$settings[site_pages_encoding]");

if(!check_admin_login()){die("<center> $phrases[access_denied] </center>");}  


//----- Set Blocks Sort ---------//
if($action=="set_blocks_sort"){
 //   file_put_contents("x.txt","d".$data[0]); 
 if_admin();
if(is_array($blocks_list_r)){
$sort_list = $blocks_list_r ;
$pos="r";
}elseif(is_array($blocks_list_c)){
$sort_list = $blocks_list_c ;
$pos="c";
}else{
$sort_list = $blocks_list_l ;
$pos="l";
}
 
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_blocks SET ord = '$i',pos='$pos' WHERE `id` = $sort_list[$i]");
 }
}
 }
 
 //------------ Set Banners Sort ---------------
if($action=="set_banners_sort"){
    if_admin("adv");
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_banners SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}

 //------------- Set Cats Sort -----------------
if($action=="set_cats_sort"){
if_admin();
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_cats SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}
 
 
 //--------- New Stores Menu Sort ------------
if($action=="set_new_stores_sort"){
    if_admin("new_stores");
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_new_menu SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
 cache_del("newmenu"); 
}
}

 //--------- New Songs Menu Sort ------------
if($action=="set_new_songs_sort"){
    if_admin("new_songs");
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_new_songs_menu SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}

 //--------- Videos Cats  Sort ------------
if($action=="set_videos_cats_sort"){
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
 //   if_videos_cat_admin($sort_list[$i],false); 
    db_query("UPDATE songs_videos_cats SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}
 //--------- Songs Fields  Sort ------------
if($action=="set_songs_fields_sort"){
     if_admin("songs_fields"); 
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
   
    db_query("UPDATE songs_songs_fields SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}

 //--------- singers Fields  Sort ------------
if($action=="set_singers_fields_sort"){
     if_admin("singers_fields"); 
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
   
    db_query("UPDATE songs_singers_fields SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}

 //--------- singers photos  Sort ------------
if($action=="set_singer_photos_sort"){
     
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
   
    db_query("UPDATE songs_singers_photos SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}



//--------- albums Fields  Sort ------------
if($action=="set_albums_fields_sort"){
     if_admin("albums_fields"); 
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
   
    db_query("UPDATE songs_albums_fields SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}


 //--------- Songs Fields  Sort ------------
if($action=="set_urls_fields_sort"){
     if_admin("urls_fields"); 
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
   
    db_query("UPDATE songs_urls_fields SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}
 
//------ singers  get ------
if($action=="get_singers_json"){


    $qr=db_query("select id,name from songs_singers");
 
    if(db_num($qr)){
     
             //"<img src=\"$scripturl/".get_image($data['img'])."\" width=20 height=20>".
    $c=0;
    while ($data=db_fetch($qr)){
    $values[$c] = array("caption"=>$data['name'],"value"=>intval($data['id']));
    $c++;
    }
   print json_encode($values);
    }
     
 
    
}

//---- new menu add form -----------
if($action=="get_new_menu_add_form"){
    
$cat = (int) $cat;
$singer = (int) $singer;
$album = (int) $album ;

 print "<form action=index.php method='post' name=sender>
  <input type=hidden name=action value='new_menu_add'>
  
  <table width=100%>
 <tr>
 <td width=33%> <b> $phrases[the_cat]  :</b>
<select name='cat' id='cat' onChange=\"get_new_menu_add_form(this.value,0);\">
<option value=0>-- $phrases[select_from_menu] --</option>";
$qrc=db_query("select name,id from songs_cats where active=1 order by ord");
while($datac=db_fetch($qrc)){
    print "<option value='$datac[id]'".iif($cat==$datac['id']," selected").">$datac[name]</option>";
}
print "
</select>
  </td>
  <td width=33%>";
if($cat){
print "
<b>$phrases[the_singer] : </b><select name='singer' id='singer' onChange=\"get_new_menu_add_form(\$('cat').value,this.value);\">
<option value=0>-- $phrases[select_from_menu] --</option>";
$qrc=db_query("select name,id from songs_singers where cat='$cat' and active=1 order by binary name asc");
while($datac=db_fetch($qrc)){
    print "<option value='$datac[id]'".iif($singer==$datac['id']," selected").">$datac[name]</option>";
}
print "
</select>
<input type=submit name='add_singer' value='$phrases[add_button]'> ";
}
 print " 
  </td>
<td width=33%>";
if($singer){
$qrc=db_query("select name,id,year from songs_albums where cat='$singer' order by year desc,id desc");
if(db_num($qrc)){
    print "
<b>$phrases[the_album] : </b> <select name='album' id='album'>
<option value=0>-- $phrases[select_from_menu] --</option>";
while($datac=db_fetch($qrc)){
    print "<option value='$datac[id]'>$datac[name]".iif($datac['year']," ($datac[year])")."</option>";
}
print "
</select>
<input type=submit name='add_album' value='$phrases[add_button]'>";
}
}
  
  print "</td>
  </tr></table>
  </form>";
}
//----------------------------------------------    




//---- new songs menu add form -----------
if($action=="get_songs_new_menu_add_form"){
    
$cat = (int) $cat;
$singer = (int) $singer;
$album = (int) $album ;
$song_id = (int) $song_id ;  


 print "<form action=index.php method='post' name=sender>
  <input type=hidden name=action value='new_songs_menu_add'>
  
  <table width=100%>
 <tr>
 <td width=25%> <b> $phrases[the_cat]  :</b>
<select name='cat' id='cat' onChange=\"get_songs_new_menu_add_form(this.value,0,0);\">
<option value=0>-- $phrases[select_from_menu] --</option>";
$qrc=db_query("select name,id from songs_cats where active=1 order by ord");
while($datac=db_fetch($qrc)){
    print "<option value='$datac[id]'".iif($cat==$datac['id']," selected").">$datac[name]</option>";
}
print "
</select>
  </td>
  <td width=25%>";
if($cat){
print "
<b>$phrases[the_singer] : </b><select name='singer' id='singer' onChange=\"get_songs_new_menu_add_form(\$('cat').value,this.value,0);\">
<option value=0>-- $phrases[select_from_menu] --</option>";
$qrc=db_query("select name,id from songs_singers where cat='$cat' and active=1 order by binary name asc");
while($datac=db_fetch($qrc)){
    print "<option value='$datac[id]'".iif($singer==$datac['id']," selected").">$datac[name]</option>";
}
print "
</select>";
}
 print " 
  </td>";
if($singer){
    
    
$qrc=db_query("select name,id,year from songs_albums where cat='$singer' order by year desc,id desc");
if(db_num($qrc)){
    print "
    <td width=25%>
<b>$phrases[the_album] : </b> <select name='album' id='album' onChange=\"get_songs_new_menu_add_form(\$('cat').value,\$('singer').value,this.value);\">
<option value=0>-- جميع الألبومات --</option>";
while($datac=db_fetch($qrc)){
    print "<option value='$datac[id]'".iif($album==$datac['id']," selected").">$datac[name]".iif($datac['year']," ($datac[year])")."</option>";
}
print "
</select>
</td>
";
}


print "
<td width=25%>";

$qr = db_query("select * from songs_songs where album='$singer'".iif($album," and album_id='$album'")." order by name asc");
if(db_num($qr)){
print "<b>$phrases[song] : </b> <select name='song_id' id='song_id'>";
while($data=db_fetch($qr)){
    print "<option value='$data[id]'>$data[name]</option>";
}

  
 print " </select>
  <input type=submit value='$phrases[add_button]'>";
}else{
    print $phrases['err_no_songs'];
}

 } 
  print "</td>
  </tr></table>
  </form>";
}




//---- new songs menu add form -----------
if($action=="get_select_song"){
    
$cat = (int) $cat;
$singer = (int) $singer;
$album = (int) $album ;
$song_id = (int) $song_id ;  


 print "
  <table width=100%>
 <tr>
 <td width=25%>
<select name='cat' id='cat' onChange=\"get_select_song('$div_name','$field_name',this.value,0,0);\">
<option value=0>-- $phrases[select_from_menu] --</option>";
$qrc=db_query("select name,id from songs_cats where active=1 order by ord");
while($datac=db_fetch($qrc)){
    print "<option value='$datac[id]'".iif($cat==$datac['id']," selected").">$datac[name]</option>";
}
print "
</select>
  </td>
  <td width=25%>";
if($cat){
print "
<select name='singer' id='singer' onChange=\"get_select_song('$div_name','$field_name',\$('cat').value,this.value,0);\">
<option value=0>-- اختر المغني --</option>";
$qrc=db_query("select name,id from songs_singers where cat='$cat' and active=1 order by binary name asc");
while($datac=db_fetch($qrc)){
    print "<option value='$datac[id]'".iif($singer==$datac['id']," selected").">$datac[name]</option>";
}
print "
</select>";
}
 print " 
  </td>";
if($singer){
    
    
$qrc=db_query("select name,id,year from songs_albums where cat='$singer' order by year desc,id desc");
if(db_num($qrc)){
    print "
    <td width=25%>
<b>$phrases[the_album] : </b> <select name='album' id='album' onChange=\"get_select_song('$div_name','$field_name',\$('cat').value,\$('singer').value,this.value);\">
<option value=0>-- جميع الألبومات --</option>";
while($datac=db_fetch($qrc)){
    print "<option value='$datac[id]'".iif($album==$datac['id']," selected").">$datac[name]".iif($datac['year']," ($datac[year])")."</option>";
}
print "
</select>
</td>
";
}


print "
<td width=25%>";

$qr = db_query("select * from songs_songs where album='$singer'".iif($album," and album_id='$album'")." order by name asc");
if(db_num($qr)){
print "<b>$phrases[song] : </b> <select name='$field_name' id='$field_name'>";
while($data=db_fetch($qr)){
    print "<option value='$data[id]'>$data[name]</option>";
}

  
 print " </select>";
}else{
    print $phrases['err_no_songs'];
}

 } 
  print "</td>
  </tr></table>
  </form>";
}


//----------- get song name --------------
if($action=="get_song_name"){
    $data = db_qr_fetch("select songs_songs.name ,songs_singers.name as singer_name from songs_songs,songs_singers where songs_singers.id=songs_songs.album and songs_songs.id='$id'");
    if($data['name']){
        print "$data[singer_name] - $data[name]";
    }else{
        print $phrases['not_available'];
    }
}



//----------- get video name --------------
if($action=="get_video_name"){
    $data = db_qr_fetch("select songs_videos_data.name ,songs_videos_cats.name as cat_name from songs_videos_data,songs_videos_cats where songs_videos_cats.id=songs_videos_data.cat and songs_videos_data.id='$id'");
    if($data['name']){
        print "$data[cat_name] - $data[name]";
    }else{
        print $phrases['not_available'];
    }
}

//---------------------------------------------- 

//---------- Uplaoder ----------
if($action=="upload"){
 
 if($settings['uploader']){  
                  
     if($_FILES['datafile']['name'] && $upload_folder_suffix){
      
        $upload_folder = $settings['uploader_path']."/$upload_folder_suffix" ;

                           
     if($upload_folder && file_exists(CWD ."/$upload_folder")){
 
         require_once(CWD. "/includes/class_save_file.php");  
         
         $imtype = file_extension($_FILES['datafile']['name']);

if(in_array($imtype,$upload_types)){
                   
if($_FILES['datafile']['error']==UPLOAD_ERR_OK){
       
                  
  if(!file_exists($upload_folder."/".$_FILES['datafile']['name'])){$replace_exists=1;}    
      
$fl = new save_file($_FILES['datafile']['tmp_name'],$upload_folder,$_FILES['datafile']['name']);

if($fl->status){
$saveto_filename =  $fl->saved_filename;

$response =  array("status"=>1,"file"=>$saveto_filename); 
 
 if(in_array($imtype,array("jpg","png","gif","jpeg","bmp"))){
     $response['show_resize'] = 1;
 }else{
     $response['show_resize'] =0;  
 }
 
  
}else{
$err = $fl->last_error_description;
$response =  array("status"=>0,"msg"=>$err);    
}


  }else{
$upload_max = convert_number_format(ini_get('upload_max_filesize'));
$post_max = (convert_number_format(ini_get('post_max_size'))/2) ;
$max_size = iif($upload_max < $post_max,$upload_max,$post_max);

   $mx_rs = "Uploading Error , Make Sure that file size is under ".convert_number_format($max_size,2,ture);
  $response =  array("status"=>0,"msg"=>$mx_rs);   
  
  }
}else{
    $response =  array("status"=>0,"msg"=>$phrases['this_filetype_not_allowed']); 
}

         
         
      }else{
           $response = array("status"=>0,"msg"=>$phrases['err_wrong_uploader_folder']); 
      }
      
      
         
         
     }else{
         $response = array("status"=>0,"msg"=>"Missing Data");
     }
     
 }else{
   $response = array("status"=>0,"msg"=>$settings['uploader_msg']);
 }
 
  print json_encode($response);
     
    
}
