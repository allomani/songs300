<?
include_once("global.php") ;
header("Content-Type: text/html;charset=$settings[site_pages_encoding]");
//------------------------------------------
if($action=="check_register_username"){
if(strlen($str) >= $settings['register_username_min_letters']){
$exclude_list = explode(",",$settings['register_username_exclude_list']) ;

     if(!in_array($str,$exclude_list)){
//$num = db_num(member_query("select","id",array("username"=>"='$str'")));
$num = db_qr_num("select ".members_fields_replace("id")." from ".members_table_replace("songs_members")." where ".members_fields_replace("username")." like '".db_escape($str)."'",MEMBER_SQL);
  
if(!$num){
print "<img src='images/true.gif'> ";
}else{
print "<img src='images/false.gif' title=\"".str_replace("{username}",$str,"$phrases[register_user_exists]")."\">".str_replace("{username}",$str,"$phrases[register_user_exists]")." ";
    }
    }else{
    print "<img src='images/false.gif' title=\"$phrases[err_username_not_allowed]\"> $phrases[err_username_not_allowed]";
        }
    }else{
    print "<img src='images/false.gif' title=\"$phrases[err_username_min_letters]\"> $phrases[err_username_min_letters]";
        }
}


//------------------------------------------
if($action=="check_register_email"){
if(check_email_address($str)){
$num = db_qr_num("select ".members_fields_replace("id")." from ".members_table_replace("songs_members")." where ".members_fields_replace("email")." like '".db_escape($str)."'",MEMBER_SQL);
if(!$num){
print "<img src='images/true.gif'>";
}else{
print "<img src='images/false.gif' title=\"$phrases[register_email_exists]\"> $phrases[register_email_exists]";
    }
    }else{
    print "<img src='images/false.gif' title=\"$phrases[err_email_not_valid]\"> $phrases[err_email_not_valid]";
        }
}
//---------------------------------
if($action=="get_playlist_items"){
if(check_member_login()){
  $id= intval($id) ;
  
 set_cookie('last_list_id',$id);
 
$qr_list = db_query("select * from songs_playlists_data where member_id='$member_data[id]' and cat='$id' order by ord");

if(db_num($qr_list)){

while($data_list =db_fetch($qr_list)){

get_playlist_item($data_list['id'],$data_list['song_id'],1);

}
}else{
print "---";
}
}
}
//-------------------------------
if($action=="get_playlists"){
  if(check_member_login()){   
 $last_list_id = intval(get_cookie('last_list_id'));
    
$qr_lists = db_query("select * from songs_playlists where member_id='$member_data[id]'");
print "<center><select name='playlist_id' id='playlist_id' onchange=\"get_playlist_items(this.value);\">
<option value=\"0\">$phrases[default_playlist]</option>";
while($data_lists = db_fetch($qr_lists)){
print "<option value=\"$data_lists[id]\"".iif($last_list_id==$data_lists['id']," selected").">$data_lists[name]</option>";
}
print "</select><br><br>
<a href=\"javascript:playlists_add();\"><img src='images/add_small.gif' border=0 title=\"$phrases[playlists_add]\"></a>
&nbsp; <a href=\"javascript:playlists_del($('playlist_id').value);\"><img src='images/delete_small.gif' border=0 title=\"$phrases[playlists_del]\"></a></center><br>";
  }
  }
//-------------------------------
if($action=="playlists_add"){
if(check_member_login()){
     
 db_query("insert into songs_playlists (name,member_id) values('".db_escape($name)."','$member_data[id]')");
  $id = mysql_insert_id();
  set_cookie('last_list_id',intval($id));
  print $id;
} 
}
//-------------------------------
if($action=="playlists_del"){
if(check_member_login()){
     
 db_query("delete from songs_playlists where id='$id' and member_id='$member_data[id]'");
  set_cookie('last_list_id','0');
  print "0";
} 
}
//---------------------------------------
if($action=="playlist_add_song"){
if(check_member_login()){
    $last_list_id = intval(get_cookie('last_list_id')); 
    $song_id = intval($song_id);
      
 db_query("insert into songs_playlists_data (song_id,member_id,cat) values('$song_id','$member_data[id]','$last_list_id')");
  $id = mysql_insert_id();
print $id;
}
}
//----------------------------
if($action=="playlist_get_item"){
get_playlist_item($id,0,0);
}
//---------------------------------------
if($action=="playlist_delete_song"){
if(check_member_login()){
    $id=intval($id);
 db_query("delete from songs_playlists_data where id='$id' and member_id='$member_data[id]'");
}
}
//----------------------------------
if($action=="set_playlist_sort"){
    if(check_member_login()){   
  if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {
    db_query("UPDATE songs_playlists_data SET ord = '$i' WHERE `id` = '".intval($sort_list[$i])."' and member_id='$member_data[id]'");
 }
}
    }
}
//-----------------------------------------

//---------------------  Comments ---------------------------

if($action=="comments_add"){
if(check_member_login()){

if(in_array($type,$comments_types)){
    
$content = trim($content);

if($content){  

    
    /*
$bad_words = explode(",",$settings['comments_bad_words']);
if(count($bad_words)){    
foreach($bad_words as $word){
    $word=trim($word);
    if($word){
    $bad_words_str .= "\b".$word."\b|";
    }
}
if($bad_words_str){
$bad_words_str = substr($bad_words_str,0,strlen($bad_words_str)-1); 
//$bad_words_str = "\bحمار\b|\bكلب\b|\bسكس\b|\bfuck\b|\bsex\b";
$content = preg_replace("/$bad_words_str/u", $settings['comments_bad_words_replacement'],$content); 
}
}       */


 


db_query("insert into songs_comments (uid,fid,comment_type,content,time,active) values ('".intval($member_data['id'])."','".intval($id)."','".db_escape($type)."','".db_escape($content)."','".time()."','".iif($settings['comments_auto_activate'],1,0)."')");

   $new_id = mysql_insert_id();
   
if($settings['comments_auto_activate']){
  //  print $content;   
  $data_member = db_qr_fetch("select ".members_fields_replace('id')." as uid,".members_fields_replace('username')." as username,gender,thumb from ".members_table_replace('songs_members')." where ".members_fields_replace('id')."='".intval($member_data['id'])."'",MEMBER_SQL);
   
  $data = $data_member;
  $data['id'] = $new_id;
  $data['time'] = time()-1;
  $data['content'] = htmlspecialchars($content);
  
  
  $rcontent =  get_comment($data);   
   
       print json_encode(array("status"=>1,"content"=>$rcontent));
}else{
     print json_encode(array("status"=>1,"content"=>"","msg"=>"$phrases[comment_is_waiting_admin_review]")); 
}


}else{
    print json_encode(array("status"=>0,"msg"=>"$phrases[err_empty_comment]"));
}
}else{
      print json_encode(array("status"=>0,"msg"=>"$phrases[err_wrong_url]")); 
}
        
//print "<img src=\"images/success_small.gif\">&nbsp; تم الاضافة";
}else{
  //  print "<img src=\"images/failed_small.gif\">&nbsp; يرجى تسجيل الدخول"; 
  print json_encode(array("status"=>0,"msg"=>"$phrases[please_login]"));
    
}

 
}

//--------------------------
if($action=="comments_delete"){
    
check_member_login();
db_query("delete from songs_comments where id='".intval($id)."'".iif(!check_admin_login()," and uid='".$member_data['id']."'"));    
    
}

//------------------------------


if($action=="comments_get"){

      $offset = (int) $offset;
      if(!$offset){$offset=1;}
      $perpage =  intval($settings['commets_per_request']);
      if(!$perpage){$perpage=10;}
      $start = (($offset-1) * $perpage) ;
      
 
  $check_admin_login = check_admin_login();
  $check_member_login =  check_member_login();
   
  $members_cache = array();
   

$qr = db_query("select * from songs_comments where fid='".db_escape($id)."' and comment_type like '".db_escape($type)."' and active=1 order by id desc limit $start,$perpage");
    
//$qr = db_query("select songs_comments.*,songs_members.id as member_id,songs_members.username,songs_members.thumb,songs_members.gender  from songs_comments,songs_members where songs_comments.fid='".db_escape($id)."' and songs_comments.comment_type like '".db_escape($type)."' and songs_comments.active=1 and songs_members.id=songs_comments.uid order by songs_comments.id desc limit $start,$perpage");
if(db_num($qr)){
   // print $offset;
   if($offset = 1){
  print "<div id='no_comments'></div>";
   }
  
 
   
    $c=0;
    while($data=db_fetch($qr)){                                                                    
        $data_arr[$c] = $data;
    
    if($members_cache[$data['uid']]['username']){
    $udata = $members_cache[$data['uid']];
    }else{
    $udata = db_qr_fetch("select ".members_fields_replace('username')." as username ,thumb,gender from ".members_table_replace('songs_members')." where ".members_fields_replace('id')."='$data[uid]'",MEMBER_SQL);
    $members_cache[$data['uid']] =  $udata ;
    }                                             
    
    $data_arr[$c]['username'] = $udata['username'];
    $data_arr[$c]['gender'] = $udata['gender'];
    $data_arr[$c]['thumb'] = $udata['thumb'];
    
    
    $c++;
    }
    
   
    
    //--- first row id ----
    $first_index = count($data_arr)-1;
     $data_first_row = db_qr_fetch("select id from songs_comments where fid='".db_escape($id)."' and comment_type like '".db_escape($type)."' and active=1 order by id limit 1");
     if($data_arr[$first_index]['id'] != $data_first_row['id']){
          print " <div id='comments_older_div' class='older_comments_div'><a href='javascript:;' onClick=\"comments_get('".$type."','".$id."');\"><img src=\"$style[images]/older_comments.gif\">&nbsp; $phrases[older_comments]</a></div> ";
     }                                                        
    //---------------------
    
    
    
    unset($data);
    for($i=count($data_arr)-1;$i>=0;$i--){
           //    print $i;
        $data= $data_arr[$i];
        
    if($tr_class=="row_2"){
        $tr_class="row_1";
    }else{
        $tr_class="row_2";
    }
           
    print get_comment($data);
    }
   
    
}else{
    if($offset == 1){ 
    print "<div id='no_comments'>$phrases[no_comments]</div>";
    }
}


 
}

//----------  Report -------
if($action=="report"){

if($settings['report_sec_code']){      
require(CWD . '/includes/class_security_img.php');
$sec_img = new sec_img_verification();
}


    $id=intval($id);
 if($settings['reports_enabled']){
 
  $member_login = check_member_login();  
  
 if(!$settings['reports_for_visitors'] &&  !$member_login){
  open_table();
  print "<center>$phrases[please_login_first]</center>";
  close_table();
    
 }else{
 
    
    open_table($phrases['report_do']);
    print "<form action='ajax.php' method='post' name='report_submit' id='report_submit'>
    <input type='hidden' name='action' value='report_submit'>
     <input type='hidden' name='id' value='$id'> 
     <input type='hidden' name='report_type' value=\"".htmlspecialchars($report_type)."\"> 
      
      
    <table width=100%>";
    
    if(!$member_login){
print "<tr><td>
    
<b>$phrases[your_name] </b> </td>
<td><input type=text name=name id='name' value=\"$member_data[username]\"></td>
</td>
<tr><td>
<b>$phrases[your_email]</b> </td>
<td><input type=text name=email dir=ltr id='email'  value=\"$member_data[email]\"></td></tr>";
    }



   print "
    <tr><td><b>$phrases[the_explanation]</b></td><td>
    <textarea cols=30 rows=5 name='content'></textarea></td></tr>";
    
    if($settings['report_sec_code']){
           print "<tr><td><b>$phrases[security_code]</b></td>
           <td>".$sec_img->output_input_box('sec_string','size=7')."
           <img src=\"sec_image.php\" title=\"$phrases[security_code]\" /></td></tr>";
           }
           
           
    print 
    "<tr><td colspan=2 align=center><input type=button id='send_button' name='send_button' value='$phrases[send]' style=\"height:70;width:60;\" onClick=\"report_send();\"></td>
    </tr></table>
    </form>";
    
    close_table();
 }
 }
}

//--- report submit ----//
if($action=="report_submit"){
    $id= (int) $id;
    
 if($settings['reports_enabled']){ 
     
   $member_login = check_member_login();  
  
 if(!$settings['reports_for_visitors'] &&  !$member_login){
  open_table();
  print "<center>$phrases[please_login_first]</center>";
  close_table();
    
 }else{
     
     
    if(in_array($report_type,$reports_types)){       
 
    if($settings['report_sec_code']){
        
 require(CWD . '/includes/class_security_img.php');
$sec_img = new sec_img_verification();


   if($sec_img->verify_string($sec_string)){
       $security_code_check = 1;
   }else{
       $security_code_check = 0;
   }
    }else{
        $security_code_check = 1;
    }
        

    
    
 
 if($security_code_check){       
    if($member_login){
    $uid = $member_data['id'];
    $name = $member_data['username'];
    $email = $member_data['email']; 
    }else{
      $uid = 0 ;  
    }
    
    
 db_query("insert into songs_reports(fid,uid,name,email,content,date,report_type) values ('$id','$uid','".db_escape($name)."','".db_escape($email)."','".db_escape($content)."','".time()."','".db_escape($report_type)."')");

 open_table();
print "<center>  $phrases[report_sent] </center>";
 close_table();
 
 
 }else{
     open_table();
        print  "<center>$phrases[err_sec_code_not_valid]</center>";
        close_table(); 

 }
    
    }else{     
 open_table();
print "<center>  $phrases[err_wrong_url] </center>";
 close_table();
 
 
    }
 }
 }  
}



//-------- Rating ---------------
if($action=="rating_send"){

$id = (int) $id;
$score = (int) $score;



    if(in_array($type,$rating_types)){
   
   if($score > 0){
   $cookie_name = 'rating_'.$type.'_'.$id;
    
   $settings['rating_expire_hours'] = intval($settings['rating_expire_hours']);
   $settings['rating_expire_hours'] = iif($settings['rating_expire_hours'],$settings['rating_expire_hours'],1);
        
  if(get_cookie($cookie_name)){
        print "<center>".str_replace('{hours}',$settings['rating_expire_hours'],$phrases['rating_expire_msg'])."</center>" ;                
  }else{
     
       if($type=='news'){
   db_query("update songs_news set votes=votes+$score , votes_total=votes_total+1 where id='$id'");
   db_query("update songs_news set rate = (votes/votes_total) where id='$id'");
   
       }elseif($type=='song'){
   db_query("update songs_songs set votes=votes+$score , votes_total=votes_total+1 where id='$id'"); 
    db_query("update songs_songs set rate = (votes/votes_total) where id='$id'");
    
     }elseif($type=='singer'){
   db_query("update songs_singers set votes=votes+$score , votes_total=votes_total+1 where id='$id'"); 
   db_query("update songs_singers set rate = (votes/votes_total) where id='$id'"); 
   
     }elseif($type=='album'){
   db_query("update songs_albums set votes=votes+$score , votes_total=votes_total+1 where id='$id'"); 
   db_query("update songs_albums set rate = (votes/votes_total) where id='$id'"); 
   
  }elseif($type=='singer_photo'){ 
   db_query("update songs_singers_photos set votes=votes+$score , votes_total=votes_total+1 where id='$id'"); 
   db_query("update songs_singers_photos set rate = (votes/votes_total) where id='$id'"); 
  
  }elseif($type=='video'){ 
   db_query("update songs_videos_data set votes=votes+$score , votes_total=votes_total+1 where id='$id'"); 
   db_query("update songs_videos_data set rate = (votes/votes_total) where id='$id'"); 
      
  } 
       
  
  
  
  
      
  set_cookie($cookie_name,1,time()+(60*60*$settings['rating_expire_hours']));    
         print "$phrases[rating_done]"; 
         
  }
         
    }else{
        print "Wrong Rating Value !";
    }       
    }else{
    print "Wrong Reference !";    
    }
    
   
}



//-------- add to fav ------------
if($action=="add_to_fav"){
    open_table($phrases['add2favorite']);
    if($type=="video"){
    if(check_member_login()){
    if($confirm){ 
    $qr = db_query("select id from songs_members_favorites where fid='$id'");
    if(db_num($qr)){
     print "<center>  $phrases[add2fav_already_exists]  </center>";      
    }else{
    db_query("insert into songs_members_favorites (uid,fid,`type`) values('$member_data[id]','$id','".db_escape($type)."')");
    print "<center>  $phrases[add2fav_success]  </center>";
    }
    }else{  
        
    if($type=="video"){
    $data = db_qr_fetch("select name from songs_videos_data where id='$id'");
    }
    print "<center>".str_replace("{name}",$data['name'],$phrases['add2fav_confirm_msg']);
    print "<br><br><input type=button value='$phrases[yes]' onClick=\"add_to_fav_confirm($id,'".htmlspecialchars($type)."');\"> &nbsp;&nbsp;<input type=button value='$phrases[no]' onClick=\"setContent.hide();\">
    </center> "; 
    }
    }else{
        print "<center>$phrases[please_login_first]</center>";
    }
    }else{
        print "<center> Wrong Type </center>";
    }
    close_table();
    
}
//----- send to friend action --------
if($action=="send2friend_submit"){ 
   
if($name_from && $email_from && $email_to){

if(check_email_address($email_from) && check_email_address($email_to)){


 if($settings['send_sec_code']){
        
 require(CWD . '/includes/class_security_img.php');
$sec_img = new sec_img_verification();


   if($sec_img->verify_string($sec_string)){
       $security_code_check = 1;
   }else{
       $security_code_check = 0;
   }
    }else{
        $security_code_check = 1;
    }
        
        
        
if($security_code_check){    

   
if($type=="video"){                 
$url = "$scripturl/".str_replace("{id}",$id,$links['video_watch'])  ;

$data = db_qr_fetch("select name,img,id from songs_videos_data where id='$id'");
$file_title = "$data[name]" ;

$org_msg = get_template("friend_msg_clip");
     
$img_url = get_image($data['img']);                                                                                    
$img_url =   iif(strchr($data['img'],"://"),$img_url,$scripturl."/$img_url");    

}else{
    
    $url = "$scripturl/".str_replace(array("{id}","{cat}"),array($id,$settings['default_url_id']),$links['song_listen'])  ;

$data = db_qr_fetch("select songs_songs.name,songs_singers.name as singer_name from  songs_songs,songs_singers  where songs_singers.id = songs_songs.album and songs_songs.id='$id'");
$file_title = "$data[singer_name] - $data[name]" ;

$org_msg = get_template("friend_msg");
     
$img_url = "";
}


       
$msg = str_replace(
array("{name_from}","{email_from}","{email_to}","{url}","{name}","{img}","{sitename}","{siteurl}"),
array($name_from,$email_from,$name_to,$url,$file_title,$img_url,$sitename,$siteurl),$org_msg);

 


    open_table();

    $email_result = send_email($name_from,$mailing_email,$email_to,$phrases['send2friend_subject'],$msg);
if($email_result)  {
print "<center>  $phrases[send2friend_done] </center>";
}else{
    print "<center> $phrases[send2friend_failed] </center>";
        }
    close_table();
    
    
}else{
     open_table();
        print  "<center>$phrases[err_sec_code_not_valid]</center>";
        close_table();     
}


}else{
   open_table();
    print "<center> $phrases[invalid_from_or_to_email] </center>";
    close_table();   
}

}else{
    open_table();
    print "<center> $phrases[please_fill_all_fields] </center>";
    close_table();
}    
}

//----------- send2friend form -----------------
if($action=="send2friend_form"){
    
if($settings['send_sec_code']){      
require(CWD . '/includes/class_security_img.php');
$sec_img = new sec_img_verification();
}


    $id= (int) $id;
    check_member_login();
   // print_r($member_data);
    open_table($phrases['send2friend']);
   
    print "
<form name='send_submit_form' method=post action='ajax.php' id='send_submit_form'>
<input type=hidden name='action' value='send2friend_submit'>
<input type=hidden name='type' value='".htmlspecialchars($type)."'> 
<input type=hidden name='id' value='$id'> ";

print "<table>
<tr><td >
$phrases[your_name] : </td>
<td><input type=text name=name_from id='name_from' value=\"$member_data[username]\"></td></tr>

<tr><td>
$phrases[your_email] : </td>
<td><input type=text name=email_from dir=ltr id='email_from'  value=\"$member_data[email]\"></td></tr>

<tr><td>
$phrases[your_friend_email] : </td>
<td colspan=2><input type=text name=email_to dir=ltr id='email_to'></td></tr>";  


    if($settings['send_sec_code']){
           print "<tr><td>$phrases[security_code] :</td>
           <td>".$sec_img->output_input_box('sec_string','size=7')."
           <img src=\"sec_image.php\" alt=\"$phrases[security_code]\" /></td></tr>";
           } 

print "<tr><td colspan=2 align=center>
<input type=button id='send_button' name='send_button' value='$phrases[send]' style=\"height:70;width:60;\" onClick=\"send_submit();\"></td></tr> 

</table></form>";
close_table();
}


//--------------------------------------------
if($action=="get_playlist_player"){   
   
   $player_data = get_player_data($url);

$url = iif(!strchr($url,"://"),$scripturl."/".$url,$url); 
run_php(str_replace(array("{url}","{current_index}","{next_index}"),array($url,$cur_index,($cur_index+1)),$player_data['playlist_content']));
 
}

  
?>