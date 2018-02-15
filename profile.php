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

require("global.php");

require(CWD . "/includes/framework_start.php");  
check_member_login();  
//---------------------------------------------------------
$qr = db_query("select * from ".members_table_replace('songs_members')." where ".members_fields_replace('id')."='$id'",MEMBER_SQL);
if(db_num($qr)){
    
    $data = db_fetch($qr);
    
    unset($prv_data);
    $prv_data = unserialize($data['privacy_settings']);
    

$continue = get_privacy_settings($id,$member_data['id'],$prv_data['profile'],$phrases['profile_access_denied'],true);

  
if($continue){
       

  open_table($data[members_fields_replace('username')]);
  print "<table width=100%><tr><td valign=top width=\"".($settings['profile_pic_width']+10)."\">
  <img src=\"".get_image($data['img'],$style['images']."/profile_no_pic".iif($data['gender'],"_".$data['gender']).".gif")."\">
  </td><td>";
  
  if($data['gender']){
  if(get_privacy_settings($id,$member_data['id'],$prv_data['gender'])){
      print "<b>$phrases[gender] : </b> ".$phrases[$data['gender']]." <br>";
  }
  }
  
       
    if($data[members_fields_replace('birth')]){
    $birth_array = connector_get_date($data[members_fields_replace('birth')],"member_birth_array");
    $birth = $birth_array['day']."-".$birth_array['month']."-".$birth_array['year'];
    
        $birth_time =  strtotime($birth);

    if($birth_time){
  if(get_privacy_settings($id,$member_data['id'],$prv_data['birth'])){
      print "<b>$phrases[birth] : </b> ".get_date($birth_time)." <br>";
  }
  }
  }
  
  
      if($data['country']){
  if(get_privacy_settings($id,$member_data['id'],$prv_data['country'])){
      print "<b>$phrases[country] : </b> ".$data['country']." <br>";
  }
  }
  
  


  
  
  
  
  
  $qrf = db_query("select * from songs_members_sets order by ord");
   if(db_num($qrf)){
   while($dataf=db_fetch($qrf)){
       if($data["field_$dataf[id]"]){
       if(get_privacy_settings($id,$member_data['id'],$prv_data["field_$dataf[id]"])){
       print "<b> $dataf[name] : </b> ".$data["field_$dataf[id]"]."<br>";
       }
       }
   }
   }
   
   
     if(get_privacy_settings($id,$member_data['id'],$prv_data['last_login'])){
      print "<b>$phrases[last_login] : </b> ".get_date($data[members_fields_replace('last_login')])." <br>";
  }
  
                                  
  
    

  if(get_privacy_settings($id,$member_data['id'],$prv_data['online'])){
      print "<b>$phrases[online_status] : </b> ".iif($data[members_fields_replace('last_login')]>= time()-$online_visitor_timeout,"<span class='online'>$phrases[online]</span>","<span class='offline'>$phrases[offline]")." <br>";
  }
  
  
  print "<br>
  <a href='messages.php?action=new&id=$id'><img src='$style[images]/send.gif' border=0> &nbsp; $phrases[send_msg_to_member]</a><br>
  <a href='usercp.php?action=friends_add&id=$id' onClick=\"return confirm('".$phrases['are_you_sure']."');\"><img src='$style[images]/friends_list.gif' border=0> &nbsp; $phrases[add_to_friends_list]</a><br>
  <a href='usercp.php?action=black_list_add&id=$id' onClick=\"return confirm('".$phrases['are_you_sure']."');\"><img src='$style[images]/black_list.gif' border=0> &nbsp; $phrases[add_to_black_list]</a>
  </td></tr></table>";
  
       
if($settings['reports_enabled']){
 print "<div align='$global_align_x'>
<a href=\"javascript:;\" onClick=\"report($id,'member');\"><img src=\"$style[images]/report.gif\" title=\"$phrases[report_do]\" border=0></a>
</div>";
}



  close_table();  

  
//------ fav videos --------//

$continue2 = get_privacy_settings($id,$member_data['id'],$prv_data['fav_videos']);
if($continue2){
$qr = db_query("select songs_videos_data.* from songs_videos_data,songs_members_favorites where songs_videos_data.id=songs_members_favorites.fid and songs_members_favorites.uid='$id'");
if(db_num($qr)){
          
open_table("$phrases[the_favorite]");       
           
   run_template('browse_videos_header');

$c=0 ;


while ($data =db_fetch($qr)){

   
if ($c==$settings['songs_cells']) {
run_template('browse_videos_sep');
$c = 0 ;
}
 ++$c ;

run_template('browse_videos');
              
}
run_template('browse_videos_footer');
  close_table();        
}
 
} 

        
}


}else{
    open_table();
    print "<center>$phrases[err_wrong_url]</center>";
    close_table();
    
}
//---------------------------------------------
require(CWD . "/includes/framework_end.php"); 
