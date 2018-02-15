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

 if(!defined('IS_ADMIN')){die('No Access');}  

 //----------------------- Settings --------------------------------
 if($action == "settings" || $action=="settings_edit"){
 
     if_admin();


if($action=="settings_edit"){
     
 
 
  if(is_array($stng)){
 for($i=0;$i<count($stng);$i++) {

        $keyvalue = current($stng);

       db_query("update songs_settings set value='".db_escape($keyvalue,false)."' where name like '".db_escape(key($stng))."'");

      /*$f = db_qr_fetch("select count(*) as count from songs_settings where name like '".db_escape(key($stng))."'"); 
      if(!$f['count']){
          print "<li>". key($stng)."  not foound</li>";
      }  */
      
 next($stng);
}
}

$stng_prv = array_map('intval',$stng_prv);
$default_prv = serialize($stng_prv);
db_query("update songs_settings set value='".db_escape($default_prv,false)."' where name like 'default_privacy_settings'"); 
 
 

         }


  load_settings();
 unset($prv_data);
 $prv_data = unserialize($settings['default_privacy_settings']);
 

 print "<center>
 <p align=center class=title>  $phrases[the_settings] </p>
 <form action=index.php method=post>
 <input type=hidden name=action value='settings_edit'>
  
 <fieldset style=\"width:70%\">  
   <table width=100%>
  
 <tr><td>  $phrases[site_name] : </td><td><input type=text name=stng[sitename] size=30 value='$settings[sitename]'> &nbsp; </td></tr>
  <tr><td> $phrases[show_sitename_in_subpages] </td><td>";
  print_select_row("stng[sitename_in_subpages]",array($phrases['no'],$phrases['yes']),$settings['sitename_in_subpages']);
  print "</td></tr>
 
 
 <tr><td>  $phrases[section_name] : </td><td><input type=text name=stng[section_name] size=30 value='$settings[section_name]'></td></tr>
 <tr><td> $phrases[show_section_name_in_subpages] </td><td>";
  print_select_row("stng[section_name_in_subpages]",array($phrases['no'],$phrases['yes']),$settings['section_name_in_subpages']);
  print "</td></tr>
 
  <tr><td>  $phrases[copyrights_sitename] : </td><td><input type=text name=stng[copyrights_sitename] size=30 value='$settings[copyrights_sitename]'></td></tr>
   <tr><td>  $phrases[admin_email] : </td><td><input type=text dir=ltr name=stng[admin_email] size=30 value='$settings[admin_email]'></td></tr> 

 <tr><td> $phrases[page_dir] : </td><td><select name=stng[html_dir]>" ;
 if($settings['html_dir'] == "rtl"){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value='rtl' $chk1>$phrases[right_to_left]</option>
 <option value='ltr' $chk2>$phrases[left_to_right]</option>
 </select>
 </td></tr>
  <tr><td>  $phrases[pages_lang] : </td><td><input type=text name=stng[site_pages_lang] size=30 value='$settings[site_pages_lang]'></td></tr>
    <tr><td>  $phrases[pages_encoding] : </td><td><input type=text name=stng[site_pages_encoding] size=30 value='$settings[site_pages_encoding]'></td></tr>
  <tr><td> $phrases[page_keywords] : </td><td><input type=text name=stng[header_keywords] size=30 value='$settings[header_keywords]'></td></tr>
     <tr><td> $phrases[page_description] : </td><td><input type=text name=stng[header_description] size=30 value='$settings[header_description]'></td></tr>

     
     
  </table>
  </fieldset>
  
   <br>
<fieldset style=\"width:70%\">  
   <table width=100%>
  <tr><td>  $phrases[cp_enable_browsing]</td><td><select name=stng[enable_browsing]>";
  if($settings['enable_browsing']=="1"){$chk1="selected";$chk2="";}else{$chk1="";$chk2="selected";}
  print "<option value='1' $chk1>$phrases[cp_opened]</option>
  <option value='0' $chk2>$phrases[cp_closed]</option>
  </select></td></tr>
  <tr><td>$phrases[cp_browsing_closing_msg]</td><td><textarea cols=30 rows=5 name=stng[disable_browsing_msg]>$settings[disable_browsing_msg]</textarea>
  </td></tr>
   </table>
   </fieldset>
   
       <br>
       
 <fieldset style=\"width:70%\">  
   <table width=100%>
  <tr><td>$phrases[default_style]</td><td><select name=stng[default_styleid]>";
  $qrt=db_query("select * from songs_templates_cats order by id asc");
while($datat =db_fetch($qrt)){
print "<option value=\"$datat[id]\"".iif($settings['default_styleid']==$datat['id']," selected").">$datat[name]</option>";
}
  print "</select>
  </td>
 </table> 
 </fieldset>
 
    <br>
 
  <fieldset style=\"width:70%\">
 <legend><b>$phrases[time_and_date]</b></legend>
 <table width=100%>
 <tr><td>$phrases[timezone]</td><td>
 <select name='stng[timezone]'> ";
  $zones = get_timezones();
  foreach($zones as $zone){
  print "<option value=\"$zone[value]\"".iif($zone[value]==$settings['timezone'], " selected").">$zone[name]</option>";           
  }
  
 print "</select></td></tr>
    <tr><td>  $phrases[date_format] </td><td><input type=text dir=ltr name=stng[date_format] size=30 value=\"$settings[date_format]\"></td></tr>
</table>
 
 </fieldset>
   <br>
<fieldset style=\"width:70%\">  
   <table width=100%>
 <tr><td>  $phrases[adding_songs_fields_count] : </td><td><input type=text name=stng[songs_add_limit] size=5 value='$settings[songs_add_limit]'></td></tr>
  <tr><td>  $phrases[songs_perpage] : </td><td><input type=text name=stng[songs_perpage] size=5 value='$settings[songs_perpage]'></td></tr>
  <tr><td>  $phrases[singers_perpage] : </td><td><input type=text name=stng[singers_perpage] size=5 value='$settings[singers_perpage]'></td></tr>
  
  <tr><td>  $phrases[albums_perpage] : </td><td><input type=text name=stng[albums_perpage] size=5 value='$settings[albums_perpage]'></td></tr>
  
  
    <tr><td>  $phrases[videos_perpage] : </td><td><input type=text name=stng[videos_perpage] size=5 value='$settings[videos_perpage]'></td></tr>
        <tr><td>  $phrases[photos_perpage] : </td><td><input type=text name=stng[photos_perpage] size=5 value='$settings[photos_perpage]'></td></tr>
 
 
  <tr><td>  $phrases[news_perpage] : </td><td><input type=text name=stng[news_perpage] size=5 value='$settings[news_perpage]'></td></tr>

 
 <tr><td>  $phrases[images_cells_count] : </td><td><input type=text name=stng[songs_cells] size=5 value='$settings[songs_cells]'></td></tr> 
 <tr><td>  $phrases[votes_expire_time] : </td><td><input type=text name=stng[votes_expire_hours] size=5 value='$settings[votes_expire_hours]'> $phrases[hour] </td></tr>
<tr><td>  $phrases[rating_exire_time] : </td><td><input type=text name=stng[rating_expire_hours] size=5 value='$settings[rating_expire_hours]'> $phrases[hour] </td></tr>";

//<tr><td> $phrases[vote_files_expire_time] : </td><td><input type=text name=stng[vote_file_expire_hours] size=5 value='$settings[vote_file_expire_hours]'> $phrases[hour] </td></tr>

   print"
    </table>
    </fieldset>
     <br>
     
<fieldset style=\"width:70%\">  
   <table width=100%>
   <tr><td> $phrases[visitors_can_sort_songs] : </td><td>" ;
 print_select_row("stng[visitors_can_sort_songs]",array($phrases['no'],$phrases['yes']),$settings['visitors_can_sort_songs']);
 print "</td></tr>
 
 <tr><td>$phrases[songs_default_orderby] : </td><td>
<select size=\"1\" name=\"stng[songs_default_orderby]\">";
for($i=0; $i < count($orderby_checks);$i++) {

$keyvalue = current($orderby_checks);
if($keyvalue==$settings['songs_default_orderby']){$chk="selected";}else{$chk="";}

print "<option value=\"$keyvalue\" $chk>".key($orderby_checks)."</option>";;

 next($orderby_checks);
}
print "</select>&nbsp;&nbsp;  ";
print_select_row("stng[songs_default_sort]",array("asc"=>$phrases['asc'],"desc"=>$phrases['desc']),$settings['songs_default_sort']);
print "</td></tr>


<tr><td>$phrases[albums_orderby] : </td><td>";
print_select_row("stng[albums_orderby]",array("year"=>$phrases['release_year'],"id"=>$phrases['add_date']),$settings['albums_orderby']);
print "</select>&nbsp;&nbsp;  ";
print_select_row("stng[albums_sort]",array("asc"=>$phrases['asc'],"desc"=>$phrases['desc']),$settings['albums_sort']);
print "</td></tr>


   </table>
   </fieldset>
    <br>
  
  <fieldset style=\"width:70%\">  
   <table width=100%>
   
 <tr><td>  $phrases[cp_show_singer_img] : </td><td>";
     print_select_row("stng[cp_singer_img]",array("0"=>"$phrases[no]","1"=>"$phrases[yes]"),$settings['cp_singer_img']);
   print " </td></tr>
    
     <tr><td>  $phrases[stng_singers_letters] : </td><td>";
     print_select_row("stng[letters_singers]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['letters_singers']);
   print " </td></tr> 
   
        <tr><td>  $phrases[stng_songs_letters] : </td><td>";
     print_select_row("stng[letters_songs]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['letters_songs']);
   print " </td></tr> 
   
           <tr><td>  $phrases[stng_songs_multi_select] : </td><td>";
     print_select_row("stng[songs_multi_select]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['songs_multi_select']);
   print " </td></tr> 
   
 
 <tr><td>  $phrases[stng_vote_songs] : </td><td><select name=stng[vote_song]>" ;
 if($settings['vote_song']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 
 
 <tr><td> $phrases[stng_send_song] : </td><td><select name=stng[snd2friend]>" ;
 if($settings['snd2friend']){$chk3 = "selected" ; $chk4 ="" ;}else{ $chk4 = "selected" ; $chk3 ="" ;}
 print "<option value=1 $chk3>$phrases[enabled]</option>
 <option value=0 $chk4>$phrases[disabled]</option>
 </select>
 </td></tr>
 
   <tr><td>$phrases[security_code_in_send] : </td><td>";
 print_select_row("stng[send_sec_code]",array("0"=>$phrases['disabled'],"1"=>$phrases['enabled']),$settings['send_sec_code']);
 print "
 </td></tr>
 
 

 <tr><td>$phrases[stng_group_singers_by_letters] : </td><td><select name=stng[singers_groups]>" ;
 if($settings['singers_groups']){$chk3 = "selected" ; $chk4 ="" ;}else{ $chk4 = "selected" ; $chk3 ="" ;}
 print "<option value=1 $chk3>$phrases[enabled]</option>
 <option value=0 $chk4>$phrases[disabled]</option>
 </select>
 </td></tr>
     </table>
     </fieldset>
     
     
    <br>
 <fieldset style=\"width:70%\">  
   <table width=100%>
   <tr><td>  $phrases[stng_vote_videos] : </td><td><select name=stng[vote_clip]>" ;
 if($settings['vote_clip']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td> $phrases[stng_send_videos] : </td><td><select name=stng[snd2friend_clip]>" ;
 if($settings['snd2friend_clip']){$chk3 = "selected" ;  $chk4 ="" ;}else{ $chk4 = "selected" ; $chk3 ="" ;}
 print "<option value=1 $chk3>$phrases[enabled]</option>
 <option value=0 $chk4>$phrases[disabled]</option>
 </select>
 </td></tr>
 
 <tr><td>$phrases[videos_orderby] : </td><td>";
print_select_row("stng[videos_orderby]",array("id"=>$phrases['add_date'],"views"=>$phrases['views'],"downloads"=>$phrases['downloads']),$settings['videos_orderby']);
print "</select>&nbsp;&nbsp;  ";
print_select_row("stng[videos_sort]",array("asc"=>$phrases['asc'],"desc"=>$phrases['desc']),$settings['videos_sort']);
print "</td></tr>


 </table>
 </fieldset>
 <br>
 
 <fieldset style=\"width:70%\">  
 <table width=100%>
  <tr><td>  $phrases[prev_next_singer] : </td><td>";
     print_select_row("stng[prev_next_singer]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['prev_next_singer']);
   print " </td></tr> 
   
   
     <tr><td>  $phrases[prev_next_song] : </td><td>";
     print_select_row("stng[prev_next_song]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['prev_next_song']);
   print " </td></tr> 
   
     <tr><td>  $phrases[prev_next_cat] : </td><td>";
     print_select_row("stng[prev_next_cat]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['prev_next_cat']);
   print " </td></tr> 
   
        <tr><td>  $phrases[prev_next_album] : </td><td>";
     print_select_row("stng[prev_next_album]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['prev_next_album']);
   print " </td></tr> 
   
        <tr><td>  $phrases[prev_next_video] : </td><td>";
     print_select_row("stng[prev_next_video]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['prev_next_video']);
   print " </td></tr> 
   
           <tr><td>  $phrases[prev_next_video_cat] : </td><td>";
     print_select_row("stng[prev_next_video_cat]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['prev_next_video_cat']);
   print " </td></tr> 
   </table>
   </fieldset>
   
 <br>
             
 <fieldset style=\"width:70%\">  
   <table width=100%>

 <tr><td>$phrases[the_search] : </td><td><select name=stng[enable_search]>" ;
 if($settings['enable_search']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

<tr><td>  $phrases[search_min_letters] : </td><td><input type=text name=stng[search_min_letters] size=5 value='$settings[search_min_letters]'>  </td></tr>

   </table>
</fieldset>   

 
 
 <br>
 <fieldset style=\"width:70%\">
<legend><b>$phrases[singer_overview_page]</b></legend> 
<table width=100%> 
     <tr><td>$phrases[photos_count] : </td><td><input type=text name=stng[overview_photos_limit] size=5 value='$settings[overview_photos_limit]'>  </td></tr>
 <tr><td>$phrases[videos_count] : </td><td><input type=text name=stng[overview_videos_limit] size=5 value='$settings[overview_videos_limit]'>  </td></tr>
 </table>
 </fieldset>

 
     <br>
<fieldset style=\"width:70%\">
<legend><b>$phrases[the_photos]</b></legend> 
<table width=100%>
 
   <tr><td> $phrases[pic_max_width] : </td><td><input type=text name=stng[photo_resized_width] size=5 value='$settings[photo_resized_width]'>  $phrases[pixel] </td></tr>

<tr><td> $phrases[pic_max_height] : </td><td><input type=text name=stng[photo_resized_height] size=5 value='$settings[photo_resized_height]'> $phrases[pixel] </td></tr>



     <tr><td> $phrases[thumb_width] : </td><td><input type=text name=stng[photo_thumb_width] size=5 value='$settings[photo_thumb_width]'>  $phrases[pixel] </td></tr>

<tr><td> $phrases[thumb_height] : </td><td><input type=text name=stng[photo_thumb_height] size=5 value='$settings[photo_thumb_height]'> $phrases[pixel] </td></tr>

</table>
 </fieldset>
 
       
 
    <br> 
<fieldset style=\"width:70%\">
<legend><b>$phrases[the_comments]</b></legend> 
<table width=100%> 
     <tr><td> $phrases[max_letters] : </td><td><input type=text name=stng[comments_max_letters] size=5 value='$settings[comments_max_letters]'>  </td></tr>
 <tr><td> $phrases[commets_per_request] : </td><td><input type=text name=stng[commets_per_request] size=5 value='$settings[commets_per_request]'>  </td></tr>

 <tr><td>  $phrases[news_comments] : </td><td>";
     print_select_row("stng[enable_news_comments]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['enable_news_comments']);
   print " </td></tr>
   
         <tr><td>  $phrases[the_singer] : </td><td>";
     print_select_row("stng[enable_singer_comments]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['enable_singer_comments']);
   print " </td></tr>
   
            <tr><td>  $phrases[the_albums] : </td><td>";
     print_select_row("stng[enable_album_comments]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['enable_album_comments']);
   print " </td></tr>
   
   
      <tr><td>  $phrases[singer_photos] : </td><td>";
     print_select_row("stng[enable_singer_photo_comments]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['enable_singer_photo_comments']);
   print " </td></tr>
   
   
   <tr><td>  $phrases[songs_comments] : </td><td>";
     print_select_row("stng[enable_songs_comments]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['enable_songs_comments']);
   print " </td></tr>
   
   <tr><td>  $phrases[the_videos] : </td><td>";
     print_select_row("stng[enable_video_comments]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['enable_video_comments']);
   print " </td></tr>
   
   
   
   
         <tr><td>  $phrases[comments_auto_activate]  : </td><td>" ;
         print_select_row("stng[comments_auto_activate]",array("0"=>"$phrases[no]","1"=>"$phrases[yes]"),$settings['comments_auto_activate']);
print "</td></tr>  

 
   
   
 </table>
 </fieldset>   
     <br>
  <fieldset style=\"width:70%\">
<legend><b>$phrases[the_votes]</b></legend> 
  <table width=100%>                
                   
 <tr><td>$phrases[show_prev_votes] : </td><td><select name=stng[other_votes_show]>" ;
 if($settings['other_votes_show']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td> $phrases[max_count] : </td><td><input type=text name=stng[other_votes_limit] dir=ltr size=4 value='$settings[other_votes_limit]'> </td></tr>

  <tr><td>$phrases[orderby] : </td><td> ";
  print_select_row("stng[other_votes_orderby]",array("rand()"=>"$phrases[random]","id asc"=>"$phrases[the_date] $phrases[asc]","id desc"=>"$phrases[the_date] $phrases[desc]"),$settings['other_votes_orderby']);
  print "</td></tr>
 </table>
 </fieldset>
                   <br>
<fieldset style=\"width:70%\">
<legend><b>$phrases[cp_statics]</b></legend> 

 <table width=100%>


 <tr><td>$phrases[os_and_browsers_statics] : </td><td><select name=stng[count_visitors_info]>" ;
 if($settings['count_visitors_info']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

  <tr><td>$phrases[visitors_hits_statics] : </td><td><select name=stng[count_visitors_hits]>" ;
 if($settings['count_visitors_hits']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

  <tr><td>$phrases[online_visitors_statics] : </td><td><select name=stng[count_online_visitors]>" ;
 if($settings['count_online_visitors']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 
 <tr><td> $phrases[show_online_members_count] : </td><td>";
 print_select_row("stng[online_members_count]",array("0"=>$phrases['no'],"1"=>$phrases['yes']),$settings['online_members_count']);
 print "</td></tr>


    </table>
    
    </fieldset>
                     

                     <br>

 <fieldset style=\"width:70%\">
 <legend><b>$phrases[the_reports]</b></legend>
  <table width=100%>

  <tr><td> $phrases[report_do] : </td><td>" ;
  print_select_row("stng[reports_enabled]",array("0"=>$phrases['not_activated'],"1"=>$phrases['activated']),$settings['reports_enabled']);
 print "</td></tr>
 
 <tr><td>$phrases[visitors_can_send_reports] : </td><td>";
 print_select_row("stng[reports_for_visitors]",array("0"=>$phrases['no'],"1"=>$phrases['yes']),$settings['reports_for_visitors']);
 print "
 </td></tr>
 
 <tr><td>$phrases[security_code_in_report] : </td><td>";
 print_select_row("stng[report_sec_code]",array("0"=>$phrases['disabled'],"1"=>$phrases['enabled']),$settings['report_sec_code']);
 print "
 </td></tr>
 
 </table>
 </fieldset>
 
 <br>

<fieldset style=\"width:70%;\">
 <legend><b>$phrases[mailing_settings]</b></legend>
  <table width=100%>
 <tr><td>$phrases[emails_msgs_default_type] : </td><td><select name=stng[mailing_default_use_html]>" ;
 if($settings['mailing_default_use_html']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>HTML</option>
 <option value=0 $chk2>TEXT</option>
 </select>
 </td></tr>
 <tr><td> $phrases[emails_msgs_default_encoding] : </td><td><input type=text name=stng[mailing_default_encoding] size=20 value='$settings[mailing_default_encoding]'> <br> * $phrases[leave_blank_to_use_site_encoding]</td></tr>

 <tr><td>  $phrases[mailing_email] : </td><td><input type=text dir=ltr name=stng[mailing_email] size=30 value='$settings[mailing_email]'></td></tr>
 
 </table>
 </fieldset><br>";


//--------------- Load Settings Plugins --------------------------
$pls = load_plugins("settings.php");
  if(is_array($pls)){foreach($pls as $pl){include($pl);}}
//----------------------------------------------------------------


 print "
  <fieldset style=\"width:70%;\">
 <legend><b>$phrases[the_members]</b></legend>
 <table width=100%>
    <tr><td>$phrases[registration] : </td><td>";
    print_select_row("stng[members_register]",array("0"=>$phrases['cp_closed'],"1"=>$phrases['cp_opened']),$settings['members_register']);
print "
 </td></tr>
 
 <tr><td>$phrases[security_code_in_registration] : </td><td>";
 print_select_row("stng[register_sec_code]",array("0"=>$phrases['disabled'],"1"=>$phrases['enabled']),$settings['register_sec_code']);
 print "
 </td></tr>

  <tr><td>$phrases[auto_email_activate]: </td><td><select name=stng[auto_email_activate]>" ;
 if($settings['auto_email_activate']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

 <tr><td>$phrases[stng_videos_download_for_members_only] : </td><td>";
 print_select_row("stng[videos_member_download_only]",array("0"=>$phrases['for_all_visitors'],"2"=>$phrases['as_every_cat_settings'],"1"=>$phrases['for_members_only']),$settings['videos_member_download_only']);
 print "
 </td></tr>
 
 <tr><td>  $phrases[msgs_count_limit] : </td><td><input type=text name=stng[msgs_count_limit] size=5 value='$settings[msgs_count_limit]'>  $phrases[message] </td></tr>

<tr><td>  $phrases[username_min_letters] : </td><td><input type=text name=stng[register_username_min_letters] size=5 value='$settings[register_username_min_letters]'> </td></tr>
<tr><td> $phrases[username_max_letters] : </td><td><input type=text name=stng[username_max_letters] size=5 value='$settings[username_max_letters]'> </td></tr>

<tr><td> $phrases[username_exludes] : </td><td><input type=text name=stng[register_username_exclude_list] dir=ltr size=20 value='$settings[register_username_exclude_list]'> </td></tr>

<tr><td> $phrases[members_perpage] : </td><td><input type=text name=stng[members_perpage] dir=ltr size=5 value='$settings[members_perpage]'> </td></tr>


  </table> 
  </fieldset>
  
  <br>
  <fieldset style=\"width:70%;\">
 <legend><b>$phrases[profile_picture]</b></legend>
 <table width=100%>     
     <tr><td> $phrases[profile_picture] </td><td>" ;
         print_select_row("stng[members_profile_pictures]",array("0"=>"$phrases[not_activated]","1"=>"$phrases[activated]"),$settings['members_profile_pictures']);
print "</td></tr>  

<tr><td> $phrases[pic_max_width] : </td><td><input type=text name=\"stng[profile_pic_width]\" size=5 value='$settings[profile_pic_width]'>  $phrases[pixel] </td></tr>
<tr><td> $phrases[pic_max_height]  : </td><td><input type=text name=\"stng[profile_pic_height]\" size=5 value='$settings[profile_pic_height]'> $phrases[pixel] </td></tr>



<tr><td> $phrases[thumb_width] : </td><td><input type=text name=\"stng[profile_pic_thumb_width]\" size=5 value='$settings[profile_pic_thumb_width]'>  $phrases[pixel] </td></tr>
<tr><td> $phrases[thumb_height]  : </td><td><input type=text name=\"stng[profile_pic_thumb_height]\" size=5 value='$settings[profile_pic_thumb_height]'> $phrases[pixel] </td></tr>
 
 
 
 
</table>
</fieldset>


  <br>
  <fieldset style=\"width:70%;\">
  <legend><b>$phrases[default_privacy_settings]</b></legend>
  <table width='100%'>
  <tr><td>$phrases[profile_view]</td><td>";
  print_select_row("stng_prv[profile]",$privacy_settings_array,$prv_data['profile']);
  print "</td>
  </tr>
 
    <tr><td>$phrases[gender]</td><td>";
  print_select_row("stng_prv[gender]",$privacy_settings_array,$prv_data['gender']);
  print "</td>
  </tr>
  
  
  <tr><td>$phrases[birth]</td><td>";
  print_select_row("stng_prv[birth]",$privacy_settings_array,$prv_data['birth']);
  print "</td>
  </tr>
  
  
  <tr><td>$phrases[country]</td><td>";
  print_select_row("stng_prv[country]",$privacy_settings_array,$prv_data['country']);
  print "</td>
  </tr>
  
   <tr><td>$phrases[last_login]</td><td>";
  print_select_row("stng_prv[last_login]",$privacy_settings_array,$prv_data['last_login']);
  print "</td>
  </tr>
  
   <tr><td>$phrases[online_status]</td><td>";
  print_select_row("stng_prv[online]",$privacy_settings_array,$prv_data['online']);
  print "</td>
  </tr>";
  
  $qrfp = db_query("select * from songs_members_sets order by ord");
   if(db_num($qrfp)){
       while($datafp = db_fetch($qrfp)){
       print "
  <tr><td>$datafp[name]</td><td>";
  print_select_row("stng_prv[field_".$datafp['id']."]",$privacy_settings_array,$prv_data["field_$datafp[id]"]);
  print "</td>
  </tr>";
       }
   }
   
  
  
  print "
    
  
    <tr><td>$phrases[favorite_videos]</td><td>";
  print_select_row("stng_prv[fav_videos]",$privacy_settings_array,$prv_data['fav_videos']);
  print "</td>
  </tr> 
  
  <tr><td>$phrases[receive_pm_from]</td><td>";
  print_select_row("stng_prv[messages]",$privacy_settings_array,$prv_data['messages']);
  print "</td>
  </tr> 
  
  
  </table>
  </fieldset><br>
  
         <fieldset style=\"width:70%;\">
  <legend><b>$phrases[uploader_system]</b></legend> 
  
 <table width=100%>

 <tr><td>  $phrases[uploader_system] : </td><td><select name=stng[uploader]>" ;
 if($settings['uploader']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td> $phrases[disable_uploader_msg]  : </td><td><input type=text name=stng[uploader_msg] size=30 value='$settings[uploader_msg]'></td></tr>
 <tr><td>  $phrases[uploader_path] : </td><td><input dir=ltr type=text name=stng[uploader_path] size=30 value='$settings[uploader_path]'></td></tr>
 <tr><td>  $phrases[uploader_allowed_types] : </td><td><input dir=ltr type=text name=stng[uploader_types] size=30 value='$settings[uploader_types]' style=\"font-family:Arial, Helvetica, sans-serif;font-weight:bold;\">
  <br><font size=1 color='#ACACAC'>$phrases[use_comma_between_types]</font></td></tr>

<tr><td> $phrases[uploader_thumb_width] : </td><td><input type=text name=stng[uploader_thumb_width] size=5 value='$settings[uploader_thumb_width]'> $phrases[pixel] </td></tr>
<tr><td>  $phrases[uploader_thumb_hieght]  : </td><td><input type=text name=stng[uploader_thumb_hieght] size=5 value='$settings[uploader_thumb_hieght]'> $phrases[pixel] </td></tr>
</table>
</fieldset>


 <br>
 <input type=submit value=\"$phrases[edit]\" style=\"width:100;height:30;\">
       <br><br>
 </center>
 </form>" ;

         }
