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

 //---------------------- Singer Add -----------------
 if($action=="singer_add"){
  if_cat_admin($cat);
     
       $data_cat = db_qr_fetch("select name from songs_cats where id='$cat'");     
    print "<img border=0 src='images/arrw.gif'><a href='index.php?action=singers'> $phrases[the_songs_and_singers] </a> / <a href='index.php?action=singers&cat=$data[cat]'> $data_cat[name]</a> / $phrases[singer_add]<br><br>"; 
  
       
  
print "

<p align=center class=title>$phrases[singer_add]</p>

  <form name=sender method=POST action='index.php'>
  <input type=hidden name=action value='singer_add_ok'>
   <input type=hidden name=cat value='$cat'>
 <center><table width=92% class=grid>
  <tr>
  <td>
  <b>$phrases[the_name] : </b></td>
  <td><input type=text size=30 name=name></td>
  
  </tr>
  <tr>
  <td>
 <b> $phrases[the_image] :</b></td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=img></td><td><a href=\"javascript:uploader('singers','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td>
   </tr> ";
   
     //---------- signer fields -----------------
  $cf = 0 ;

$qrf = db_query("select * from songs_singers_fields where active=1 order by ord");
   if(db_num($qrf)){
    
while($dataf = db_fetch($qrf)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
    <tr><td width=25%><b>$dataf[name] : </b><br>$dataf[details]</td><td>";
    print get_song_field("custom[$cf]",$dataf,"");
        print "</td></tr>";
$cf++;
}

}
  //------------------------------------------ 

  print " </table> <br>
  
  
   <fieldset style='width:90%;text-align:$global_align'>
   <legend><b>$phrases[the_description]</b></legend>
   <textarea name='content' rows=8 cols=50></textarea>
   </fieldset>
   <br>
   <fieldset style='width:90%'> 
    <legend><b>$phrases[bio]</b></legend>";
     editor_print_form("details",600,300,"");
     print "</fieldset>";
   
     //-------------- Tags ------------//                 
                              print " <br><br>
                              <fieldset style=\"width:90%;text-align:$global_align\">
                              <legend><b>$phrases[page_custom_info]</b></legend>
                              <table width=100%>
                              
                              <tr><td><b>$phrases[the_title] : </td><td>
                              <input type=text size=30 name=page_title></td></tr>
                              
                              <tr><td><b>$phrases[the_description] : </td><td>
                              <input type=text size=30 name=page_description></td></tr>
                              
                              <tr><td><b>$phrases[the_page_keywords] : </td><td>
                              <input type=text size=30 name=page_keywords ></td></tr>
                              
                              <tr><td><b>$phrases[page_file_name] : </td><td>
                              <input type=text size=30 name='page_name'  dir='ltr'></td></tr>
                              
                              </table>
                              <br>
                              <font color='#808080'>* $phrases[leave_blank_to_use_default_settings] </font>
                              </fieldset><br>
                              ";
                              print "
        <input type=\"submit\" value=\"$phrases[add_button]\">
        
        </form>";
 }
 
 ///---------------------- Album Add ------------------
 if($action=="album_add"){
  
 if_singer_admin($cat);
   
    $data = db_qr_fetch("select * from songs_singers where id='$cat'"); 
    $data_cat = db_qr_fetch("select name from songs_cats where id='$data[cat]'");     
    print "<img border=0 src='images/arrw.gif'><a href='index.php?action=singers'> $phrases[the_songs_and_singers] </a> / <a href='index.php?action=singers&cat=$data[cat]'> $data_cat[name]</a> / <a href='index.php?action=singer_edit&id=$data[id]'> $data[name]</a> / $phrases[add_album]<br><br>"; 
  
  
         print " <center>
           <form action='index.php' method=post name=sender2>
      <input type=hidden name='action' value='album_add_ok'>
      <input type=hidden name='id' value='$cat'>

      <table width=60% class=grid>
      <tr><td><b>$phrases[the_name]:</b></td><td><input type=text name=name size=20></td><td></td></tr>
<tr>
  <td>
 <b> $phrases[the_image] :</b></td>
  <td> <table><tr><td><input type=text  dir=ltr size=20 name=album_img></td><td><a href=\"javascript:uploader2('albums','album_img','sender2');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td>
   </tr>
   <tr>
  <td>
  <b>$phrases[release_year] : </b></td>
  <td><input type=text size=6 name='year'></td>
  </tr>";
  
     //---------- album fields -----------------
  $cf = 0 ;

$qrf = db_query("select * from songs_albums_fields where active=1 order by ord");
   if(db_num($qrf)){
    
while($dataf = db_fetch($qrf)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
    <tr><td width=25%><b>$dataf[name] : </b><br>$dataf[details]</td><td>";
    print get_song_field("custom[$cf]",$dataf,"");
        print "</td></tr>";
$cf++;
}

}
  //------------------------------------------

  print "</table><br>";

   //-------------- Tags ------------//                 
                              print " <br>
                              <fieldset style=\"width:60%;text-align:$global_align\">
                              <legend><b>$phrases[page_custom_info]</b></legend>
                              <table width=100%>
                              
                              <tr><td><b>$phrases[the_title] : </td><td>
                              <input type=text size=30 name=page_title></td></tr>
                              
                              <tr><td><b>$phrases[the_description] : </td><td>
                              <input type=text size=30 name=page_description></td></tr>
                              
                              <tr><td><b>$phrases[the_page_keywords] : </td><td>
                              <input type=text size=30 name=page_keywords ></td></tr>
                              
                              <tr><td><b>$phrases[page_file_name] : </td><td>
                              <input type=text size=30 name='page_name'  dir='ltr'></td></tr>
                              
                              </table>
                              <br>
                              <font color='#808080'>* $phrases[leave_blank_to_use_default_settings] </font>
                              </fieldset><br>
                              ";
                              print "
        <input type=\"submit\" value=\"$phrases[add_button]\">
        </form>
      </center>" ;
 }
//----------------------------- Manage Singers ----------------------------------------------
if($action=="singers" || $action=="singer_add_ok" || $action=="singer_del"){


if_singer_admin($id);

if($action=="singer_del"){
delete_singer($id);
}

if($action=="singer_add_ok"){
   $name =  trim($name);
if($name){
 $cat =intval($cat);
 db_query("insert into songs_singers (name,img,cat,date,content,details,page_name,page_title,page_description,page_keywords,active)values('".db_escape($name)."','".db_escape($img)."','$cat','".time()."','".db_escape($content)."','".db_escape($details,false)."','".db_escape($page_name)."','".db_escape($page_title)."','".db_escape($page_description)."','".db_escape($page_keywords)."','1')");

 $new_id = mysql_insert_id();
  //------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
 db_query("update songs_singers set field_".intval($custom_id[$i])."='".db_escape($custom[$i],false)."' where id='$new_id'");
   }
   }
   }
  //------------------------------------------------

}
     }

//---------------------------------------------------------------------------------------------
/*
if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions']){
    $qr_cat=db_query("select id,name from songs_cats where (id IN ($usr_data[permisions]) and id='$cat') order by ord ASC");
    }

     }else{

$qr_cat=db_query("select id,name from songs_cats where id='$cat' order by ord asc");
      }

  if(db_num($qr_cat)){
  $data_cat = db_fetch($qr_cat); */

  
  if($cat){
      
  if_cat_admin($cat);
  
   print "<img border=0 src='images/arrw.gif'><a href='index.php?action=singers'> $phrases[the_songs_and_singers] </a> / $data_cat[name]</a><br><br>
   
   
   <a href=\"index.php?action=singer_add&cat=$cat\"><img src=\"images/add.gif\">&nbsp;$phrases[singer_add]</a><br><br>";


 $qr=db_query("select * from songs_singers where cat='$cat' order by  name ASC");

  

   if(db_num($qr)){

  $c =0 ;
  print "<center> <br><br>
  <table width=98% class=grid><tr>";
  while($data = db_fetch($qr)){
        
    print "<td width=25% align=center>
    <a href='index.php?action=singer_edit&id=$data[id]'>".iif($settings['cp_singer_img'],"<img src=\"".iif(!strchr($data['img'],"://"),"$scripturl/").get_image($data['img'])."\" border=0><br>")."$data[name]</a>
    </td>";
     ++$c ;
     if($c >= 4){print "</tr><tr>";$c=0;}

          }
        print "</td></tr></table></center>";

         }else{
               print_admin_table("<center>$phrases[no_singers_or_no_permissions]</center>");
               }
        }else{

        if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions']){
    $qr=db_query("select * from songs_cats where id IN ($usr_data[permisions]) order by ord ASC");
    }

     }else{

$qr=db_query("select * from songs_cats order by ord ASC");
      }


                 $c =0 ;
  print "<center> <br><br>
  <table width=98% class=grid><tr>";
  while($data = db_fetch($qr)){   
    print "<td width=25% align=center><a href='index.php?action=singers&cat=$data[id]'>$data[name]</a></td>";
     ++$c ;
     if($c >= 4){print "</tr><tr>";$c=0;}

          }
                        }
        }

//------------------------- Move To Singer List ---------------------
if($action=='song_singer_set'){
print "<center> <p class=title>$phrases[move_songs]</p>";
if(is_array($song_id)){

 if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions'])
{
    $qr=db_query("select id from songs_singers where cat IN ($usr_data[permisions]) and id='$id'");
    }
    if(!db_num($qr)){
        print_admin_table("<center>$phrases[access_denied]</center>");
            die();
            }
     }

 $c = 1 ;
 $data_from = db_qr_fetch("select name from songs_singers where id='$id'");
print "<form action=index.php method=post>
<input type=hidden name=action value='song_singer_set2'>

<input type=hidden name=id value='$id'>

<table width=90% class=grid><tr><td align=center colspan=2><b> $phrases[move_from] : </b> $data_from[name] </td></tr>
<td><b>#</b></td><td><b> $phrases[the_name] </b></td></tr>";

foreach($song_id as $song_idx){
   $data = db_qr_fetch("select name,id from songs_songs where id='$song_idx'");
   print "<input type=hidden name='song_id[]' value='$song_idx'>  ";
print "<tr><td><b>$c</b></td><td>$data[name]</td></tr>";
++$c;
    }
 print "<tr><td colspan=2 align=center><b>$phrases[move_to]  : </b><select name=singer_id>";



 if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions']){
    $qr=db_query("select id,name,cat from songs_singers where cat IN ($usr_data[permisions]) and id !='$id' order by cat,name ASC");
    }

     }else{

$qr=db_query("select id,name,cat from songs_singers where id !='$id' order by cat, name asc");
      }


         $last_cat = -1;
 while($data = db_fetch($qr)){
     
 if($last_cat != $data['cat']){
     if($last_cat != -1){print "</optgroup>";}
     $data_cat = db_qr_fetch("select name from songs_cats where id='$data[cat]'");
     print "<optgroup label=\"$data_cat[name]\">";
     $last_cat = $data['cat'];
 }
 
 
         print "<option value='$data[id]'>$data[name]</option>";
         }

print "</optgroup>
</select> <input type=submit value=' $phrases[next] '></td></tr></table></form>";
}else{
        print "<center>  $phrases[please_select_songs_first] </center>";
        }
        }

//------------------------- Move To Singer List2 ---------------------
if($action=='song_singer_set2'){

print "<center> <p class=title>$phrases[move_songs]</p>";
if(is_array($song_id)){

 if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions'])
{
    $qr=db_query("select id from songs_singers where cat IN ($usr_data[permisions]) and id='$id'");
    }
    if(!db_num($qr)){
          print_admin_table("<center>$phrases[access_denied]</center>");
            die();
            }
     }

 $c = 1 ;
 $data_from = db_qr_fetch("select name from songs_singers where id='$id'");
print "<form action=index.php method=post>
<input type=hidden name=action value='song_singer_set_ok'>

<input type=hidden name=id value='$id'>

<table width=90% class=grid><tr><td align=center colspan=2><b>$phrases[move_from] : </b> $data_from[name] </td></tr>
<td><b>#</b></td><td><b> $phrases[the_name] </b></td></tr>";

foreach($song_id as $song_idx){
   $data = db_qr_fetch("select name,id from songs_songs where id='$song_idx'");
   print "<input type=hidden name='song_id[]' value='$song_idx'>  ";
print "<tr><td><b>$c</b></td><td>$data[name]</td></tr>";
++$c;
    }
    $data_to=db_qr_fetch("select id,name from songs_singers where id ='$singer_id'");
 print "<tr><td colspan=2 align=center><b> $phrases[move_to]  : </b> $data_to[name]
 <input type=hidden name=singer_id value='$singer_id'></tr>
 <tr><td align=center colspan=2><b>$phrases[the_album] :</b> <select name=album_id><option value='0'>$phrases[without_album]</option>";
 $qr_albums = db_query("select * from songs_albums where cat='$singer_id'");
 while($data_albums = db_fetch($qr_albums))
 {
         print "<option value='$data_albums[id]'>$data_albums[name]</option>";
         }




print "</select><input type=submit value=' $phrases[move_do] '></td></tr></table></form>";
}else{
        print "<center>  $phrases[please_select_songs_first] </center>";
        }
        }
        
//----------------------- Singer Info ---------------------
if($action=="singer_info"){

              
if_singer_admin($id);   

$qr = db_query("select * from songs_singers where id='$id'"); 
 if(db_num($qr)){
     $data = db_fetch($qr);
 
      $data_cat = db_qr_fetch("select name from songs_cats where id='$data[cat]'");     
    print "<img border=0 src='images/arrw.gif'><a href='index.php?action=singers'> $phrases[the_songs_and_singers] </a> / <a href='index.php?action=singers&cat=$data[cat]'> $data_cat[name]</a> / <a href='index.php?action=singer_edit&id=$id'> $data[name]</a> / $phrases[singer_info]<br><br>"; 
  
  
      
   print "
   <center>
    <table width=90% class=grid>
  <form name=sender method=POST action='index.php'>
  <input type=hidden name=action value='singer_edit_ok'>
  <input type=hidden name=id value='$id'>
  <tr>
  <td rowspan=6 align=center valign=top>
  <img src=\"".iif(!strchr($data['img'],"://"),"$scripturl/").get_image($data['img'])."\" border=0></td>
  
  <td>
  <b>$phrases[the_name] :</b> </td>
  <td><input type=text value=\"$data[name]\" size=30 name=name></td>
  </tr>
  <tr>
  <td>
  <b>$phrases[the_image] :</b></td>
  <td>
  <table><tr><td><input type=text value=\"$data[img]\" dir=ltr size=30 name=img></td><td><a href=\"javascript:uploader('singers','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>
   </td>
   </tr>
   
  
  <tr>
   <td> <b>$phrases[the_cat] :</b> </td>

   <td><select name=cat>";

   $qr_cat=db_query("select * from songs_cats order by ord ASC");
   while($data_cat = db_fetch($qr_cat)){
   if($data_cat['id']==$data['cat']){
           $select = "selected" ;
           }else{
                   $select = "";
                   }

      print "<option value='$data_cat[id]' $select>$data_cat[name]</option>";
           }
   print"</select></td>
   </tr>";
   
  //---------- signer fields -----------------
  $cf = 0 ;

$qrf = db_query("select * from songs_singers_fields where active=1 order by ord");
   if(db_num($qrf)){
    
while($dataf = db_fetch($qrf)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
    <tr><td width=25%><b>$dataf[name] : </b><br>$dataf[details]</td><td>";
    print get_song_field("custom[$cf]",$dataf,$data["field_".$dataf['id']]);
        print "</td></tr>";
$cf++;
}

}
  //------------------------------------------ 

   print "</td></tr>
   </table>  <br>
   
   
   <fieldset style='width:90%;text-align:$global_align'>
   <legend><b>$phrases[the_description]</b></legend>
   <textarea name='content' rows=8 cols=50>".($data['content'])."</textarea>
   </fieldset>
   <br>
   <fieldset style='width:90%'> 
    <legend><b>$phrases[bio]</b></legend>";
     editor_print_form("details",600,300,"$data[details]");
     print "</fieldset>";
   
   
   //-------------- Tags ------------//                 
                              print "    <br>
                              <fieldset style=\"width:90%;text-align:$global_align\">
                              <legend><b>$phrases[page_custom_info]</b></legend>
                              <table width=100%>
                              
                              <tr><td><b>$phrases[the_title] : </td><td>
                              <input type=text size=30 name=page_title value=\"$data[page_title]\"></td></tr>
                              
                              <tr><td><b>$phrases[the_description] : </td><td>
                              <input type=text size=30 name=page_description value=\"$data[page_description]\"></td></tr>
                              
                              <tr><td><b>$phrases[the_page_keywords] : </td><td>
                              <input type=text size=30 name=page_keywords value=\"$data[page_keywords]\"></td></tr>
                              
                              <tr><td><b>$phrases[page_file_name] : </td><td>
                              <input type=text size=30 name='page_name' value=\"$data[page_name]\" dir='ltr'></td></tr>
                              
                              </table>
                              <br>
                              <font color='#808080'>* $phrases[leave_blank_to_use_default_settings] </font>
                              </fieldset><br>
                              ";
                              print "
        <input type=\"submit\" value=\"$phrases[edit]\">
        
        </form> </center>";

}else{
    print_admin_table("<center>$phrases[err_wrong_url]</center>");
}
}
//------------------------------ Manage Songs -----------------------------------------
if($action=="singer_edit" || $action=="singer_edit_ok" ||
   $action=="album_add_ok" || $action=="album_edit_ok" || $action=="album_del" || $action=="album_del_w_songs" || 
    $action=="song_add_ok" || $action=="song_edit_ok" || $action=="song_del" || $action=="song_album_set" ||
    $action=='song_singer_set_ok' || $action=='song_ext_set' || $action=="singer_enable" || $action=="singer_disable" || $action=="album_move_ok"){


  $aid = (int) $aid;
  
  //$album_id = $id ;

if_singer_admin($id);
 
 //----------------------------------
 if($action=="singer_disable"){
        db_query("update songs_singers set active=0 where id='$id'");
        }

if($action=="singer_enable"){

       db_query("update songs_singers set active=1 where id='$id'");
        }     
//--------------------------------------------------------------------------------------------
if($action=="singer_edit_ok"){
if($name){                                                                                                                
 db_query("update songs_singers set name='".db_escape($name)."',img='".db_escape($img)."',cat='".intval($cat)."',content='".db_escape($content)."',details='".db_escape($details,false)."',page_name='".db_escape($page_name)."',page_title='".db_escape($page_title)."',page_description='".db_escape($page_description)."',page_keywords='".db_escape($page_keywords)."' where id='$id'");


  //------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
 db_query("update songs_singers set field_".intval($custom_id[$i])."='".db_escape($custom[$i],false)."' where id='$id'");
   }
   }
   }
  //------------------------------------------------
  
  
}
        }
//-----------------------------------------------------
 if($action=="album_edit_ok"){
         db_query("update songs_albums set name='".db_escape($name)."',img='".db_escape($img)."',year='".intval($year)."',page_name='".db_escape($page_name)."',page_title='".db_escape($page_title)."',page_description='".db_escape($page_description)."',page_keywords='".db_escape($page_keywords)."' where id='$album_id'");
       
 
  //------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
 db_query("update songs_albums set field_".intval($custom_id[$i])."='".db_escape($custom[$i],false)."' where id='$album_id'");
   }
   }
   }
  //------------------------------------------------
  
  
 }
//-----------------------------------------------------
 if($action=="album_del" || $action=="album_del_w_songs"){
     
$album_id = (array) $album_id ;

foreach($album_id as $iid){
 
//------     
if($action=="album_del_w_songs"){
   
    $qr_s  = db_query("select id from songs_songs where album_id='$iid' and album='$id'");
       while($data_s  = db_fetch($qr_s)){
        delete_song($data_s['id']);
    }

}else{
         db_query("update songs_songs set album_id='0' where album_id='$iid' and album='$id'");
}
//-----

   db_query("delete from songs_albums where id='$iid' and cat='$id'");       
   
}
         update_singer_counters($id,'albums');
         update_singer_counters($id,'songs');
         update_singer_counters($id,'videos'); 
         
         }
//--------------------------------------------------------
if($action=="album_add_ok"){
if(trim($name)){
     db_query("insert into songs_albums(name,img,cat,year,date,page_name,page_title,page_description,page_keywords) values('".db_escape($name)."','".db_escape($album_img)."','".intval($id)."','".intval($year)."','".time()."','".db_escape($page_name)."','".db_escape($page_title)."','".db_escape($page_description)."','".db_escape($page_keywords)."')");
   

   $new_id = mysql_insert_id();
   
//------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
 db_query("update songs_albums set field_".intval($custom_id[$i])."='".db_escape($custom[$i],false)."' where id='$new_id'");
   }
   }
   }
  //------------------------------------------------
  
  
}
     update_singer_counters($id,'albums');
        }
        
        
//---- album move ---------        
if($action=="album_move_ok"){
 if_singer_admin($singer_from);
 
 
 $album_id = (array) $album_id;
 
 foreach($album_id as $iid){
db_query("update songs_albums set cat='$id' where id='$iid'");
db_query("update songs_songs set album='$id' where album_id='$iid'");     
 }
 
 
 update_singer_counters($id,'albums');
         update_singer_counters($id,'songs');
         update_singer_counters($id,'videos'); 
         
         update_singer_counters($singer_from,'albums');
         update_singer_counters($singer_from,'songs');
         update_singer_counters($singer_from,'videos');    
}


//--------------------------------------------------------------
if($action=='song_album_set'){
$song_id = (array) $song_id;
foreach($song_id as $iid){
    db_query("update songs_songs set album_id='".intval($album_id)."' where id='".intval($iid)."' and album='$id'");
    }
    }
//------------------------------------------------
if($action=='song_singer_set_ok'){
$song_id = (array) $song_id;
$song_id = array_map("intval",$song_id);
$album_id = (int) $album_id;
$singer_id = (int) $singer_id;

foreach($song_id as $song_idx){

 if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions'])
{
    $qr=db_query("select id from songs_singers where cat IN ($usr_data[permisions]) and id='$singer_id'");
    $qr2=db_query("select songs_singers.id from songs_singers,songs_songs where songs_singers.cat IN ($usr_data[permisions]) and songs_songs.album=songs_singers.id and songs_songs.id='$song_idx'");
    }
    if(!db_num($qr) ||!db_num($qr2)){
        print_admin_table("<center>$phrases[access_denied]</center>");
            die();
            }
     }

   db_query("update songs_songs set album='$singer_id',album_id='$album_id' where id='$song_idx'");

    }
  
  update_singer_counters($id,'songs'); 
  update_singer_counters($singer_id,'songs');
  
   update_singer_counters($id,'videos'); 
  update_singer_counters($singer_id,'videos');
   
    }
//------------------------------------------------
if($action=='song_ext_set'){
$song_id = (array) $song_id;
foreach($song_id as $iid){
   db_query("update songs_songs set ext='$ext' where id='".intval($iid)."' and album='$id'");

    }
   
    }
//-----------------------------------------------------------------------------------------
 if($action=="song_del"){
$song_id = (array) $song_id;
    foreach($song_id as $del_id){
        delete_song($del_id);
    }
update_singer_counters($id,'songs');
update_singer_counters($id,'videos'); 

         }
//-------------------------------------------------------------------------
 if($action=="song_add_ok"){
        //  print_r($custom_url);
    for ($i = 0; $i <= count($name); $i++)
        {

//$name[$i] = trim($name[$i]);

if($name[$i]){

db_query("insert into songs_songs(name,lyrics,album,date,ext,c_ext,album_id,video_id) 
values('".db_escape($name[$i])."','".db_escape($lyrics[$i],false)."','".intval($id)."','".time()."','".intval($ext[$i])."','".db_escape($c_ext[$i])."','".intval($album_id)."','".intval($video_id[$i])."')");

$song_id = mysql_insert_id();
  //------------ URLs------------
  if(is_array($custom_url_data) && is_array($custom_url_id)){ 
for($m = 0;$m < count($custom_url_id["$i"]);$m++){
    
    $url_id = $custom_url_id[$i][$m];

if($url_id){
   db_query("update songs_songs set url_".$url_id."='".db_escape($custom_url_data["$i"]["$m"],false)."' where id='".$song_id."'");  
}


    }
}
//------------- Songs Custom Fields  ------------------
 if(is_array($song_field_data) && is_array($song_field_id)){
for($z=0;$z<count($song_field_id[$i]);$z++){
    
unset($field_id,$field_value);
    
$field_id = $song_field_id[$i][$z];
$field_value  = $song_field_data[$i][$z];

if($field_id){

db_query("update songs_songs set field_".$field_id."='".db_escape($field_value)."' where id='".$song_id."'");


}
}
 }
//--------------------------
                        }
             }
             
update_singer_counters($id,'songs');
update_singer_counters($id,'videos'); 

                        }
//---------------------Song Edit ------------------------------------------------
if($action == "song_edit_ok"){

for ($i = 0; $i < count($song_id); $i++)
        {
db_query("update songs_songs set name='".db_escape($name[$i])."',lyrics='".db_escape($lyrics[$i],false)."',ext='".intval($ext[$i])."',c_ext='".db_escape($c_ext[$i])."',video_id='".intval($video_id[$i])."' where id='".intval($song_id[$i])."'");
    
    
//---------- URLs------
if(is_array($custom_url_data) && is_array($custom_url_id)){ 
for($m = 0;$m < count($custom_url_id["$i"]);$m++){
$url_id = $custom_url_id[$i][$m];

if($url_id){
   db_query("update songs_songs set url_".$url_id."='".db_escape($custom_url_data["$i"]["$m"],false)."' where id='".$song_id[$i]."'");  
}

    }
}
 //------------- Songs Custom Fields  ------------------

 if(is_array($song_field_data) && is_array($song_field_id)){
for($z=0;$z<count($song_field_id[$i]);$z++){

unset($field_id,$field_value);
    
$field_id = $song_field_id[$i][$z];
$field_value  = $song_field_data[$i][$z];

if($field_id){

db_query("update songs_songs set field_".$field_id."='".db_escape($field_value)."' where id='".$song_id[$i]."'");


}
}
}
     //--------------------------------- 

        }
        
update_singer_counters($id,'videos');
       }



//--------------------------- Singer Edit Form ----------------------------
  $data = db_qr_fetch("select * from songs_singers where id='$id'");
  $data_cat = db_qr_fetch("select name from songs_cats where id='$data[cat]'");
  
  
  print "
  <img border=0 src='images/arrw.gif'><a href='index.php?action=singers'> $phrases[the_songs_and_singers] </a> / <a href='index.php?action=singers&cat=$data[cat]'> $data_cat[name]</a> / <a href='index.php?action=singer_edit&id=$id'>$data[name]</a>";
  
  if($aid){
  $data_a = db_qr_fetch("select * from songs_albums where id='$aid'");
  print " / <a href='index.php?action=singer_edit&id=$id&aid=$aid'>$data_a[name]</a>";
  }
  print " <br><br>";
   
     
  if($data_a['name']){
      print_admin_table("<center><img src=\"".iif(!strchr($data_a['img'],"://"),$scripturl."/").get_image($data_a['img'])."\"><br>$data_a[name]</center>
      <p align='$global_align_x'><a href='index.php?action=album_edit&id=$id&album_id=$data_a[id]'>$phrases[edit] </a></p>");
  }
  
  
  //------------ set last update date ---------------------
$lstupd_qr = db_query("select date from songs_songs where album='$id' order by id desc limit 1");
if(db_num($lstupd_qr)){
          $lstupd_data = db_fetch($lstupd_qr);

           db_query("update songs_singers set last_update='$lstupd_data[date]' where id='$id'");

          }else{
            db_query("update songs_singers set last_update='0' where id='$id'");
                  }
  //---------------------------------------------------------------
                  
                  
  if(!$aid){
  print "
  <center>
  
  <table class=grid width=50%><tr>
  <td colspan=4 align=center> 
  <table width=100%><tr><td width=150 align=center valign=top>
  <img src=\"".iif(!strchr($data['img'],"://"),$scripturl."/").get_image($data['img'])."\">
  </td><td>
   <b>$phrases[last_update] : </b> ".get_date($data['last_update'])." <br>
  <b>$phrases[songs_count] : </b> $data[songs_count] <br>
  <b>$phrases[the_albums_count] : </b> $data[albums_count] <br> 
  <b>$phrases[videos_count] : </b> $data[videos_count] <br> 
  <b>$phrases[photos_count] : </b> $data[photos_count] <br> 
  </td></table>
  <hr class='separate_line' size=1>
  </td></tr>
  <tr>
      <td align=center><a href='index.php?action=singer_info&id=$id'><img src='images/singer_info.png' border=0><br>$phrases[singer_info]</a></td>    
      <td align=center><a href='index.php?action=singer_photos&id=$id'><img src='images/photos_manage.png' border=0><br>$phrases[manage_photos]</a></td> 
      <td align=center>".iif($data['active'],"<a href='index.php?action=singer_disable&id=$data[id]'><img src='images/disable.png' border=0><br>$phrases[disable]</a>","<a href='index.php?action=singer_enable&id=$data[id]'><img src='images/enable.png' border=0><br>$phrases[enable]</a>")."</td>  
     <td align=center><a href=\"index.php?action=singer_del&id=$data[id]&cat=$data[cat]\" onClick=\"return confirm('$phrases[del_singer_warning]');\"><img src='images/delete_64.png' border=0><br>$phrases[delete]</a></td> 

      </table>
      </center>
      <br>";
                  print "<br><hr class='separate_line' size=1><br>"; 


//-------------------------------- Albums Managment ----------------------------
      print "
      <p align=center class=title> $phrases[the_albums] </p>
      <img src='images/add.gif'><a href='index.php?action=album_add&cat=$id'>$phrases[add_album]</a><br><br>";
      
     $qr_albums = db_query("select * from songs_albums where cat='$id' order by year desc,id desc");
     if(db_num($qr_albums)){
      print "<center>
      <form action='index.php' method='post' name='albums_form'>
      <input type='hidden' name='id' value='$id'>              
      <table width=99% class=grid>";
     $c = 0;
      while($data_albums =db_fetch($qr_albums)){
          $song_count = db_qr_fetch("select count(id) as count from songs_songs where album_id='$data_albums[id]'");

      if($tr_class == "row_1"){
         $tr_class = "row_2";
     }else{
         $tr_class = "row_1";
     }
     
     
      print "<tr id='album_tr_$c' class='$tr_class'>
       <td width=2><input name='album_id[$c]' type='checkbox' value='$data_albums[id]' onclick=\"set_checked_color('album_tr_$c',this,'$tr_class')\"></td>
       <td ><a href='index.php?action=singer_edit&id=$id&aid=$data_albums[id]'>$data_albums[name]".iif($data_albums['year']," ($data_albums[year])")."</a></td>
       <td align=center width=100>  $song_count[count] $phrases[song] </td>
      <td align='$global_align_x' width=230><a href='index.php?action=album_edit&id=$id&album_id=$data_albums[id]'>$phrases[edit] </a> - 
      <a href=\"index.php?action=album_del&id=$id&album_id=$data_albums[id]\" onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a> - 
      <a href=\"index.php?action=album_del_w_songs&id=$id&album_id=$data_albums[id]\" onClick=\"return confirm('$phrases[del_with_songs_warn]');\">$phrases[delete_w_songs]</a></td>
      </tr>";

      $c++;
              }
              print "
      <tr>
      <td width=100% colspan=4>
          <table><tr>
          <td width=2><img src='images/arrow_".$global_dir.".gif'></td>  
          <td>

          <a href='#' onclick=\"CheckAll('albums_form'); return false;\"> $phrases[select_all] </a> -
          <a href='#' onclick=\"UncheckAll('albums_form'); return false;\">$phrases[select_none] </a>
          &nbsp;&nbsp;  
          <select name='action'>
          <option value='album_move'>$phrases[move]</option>
          <option value='album_del'>$phrases[delete]</option>
          <option value='album_del_w_songs'>$phrases[delete_w_songs]</option>
          </select>
           &nbsp;&nbsp;  
          <input type=submit value=' $phrases[do_button] ' onClick=\"return confirm('$phrases[are_you_sure]');\">
          
      </td>
      
      </table>
      </td></tr>
      </table>
      </form></center>";
            }else{
                print_admin_table("<center>$phrases[no_albums]</center>");
            }

            print "<br><hr class='separate_line' size=1><br>";
  }
//------------------------------- Songs Managment -----------------------------
  print "<center>

 <br><center><font class=title> $phrases[the_songs] </font>
   <br><br> [<a href='index.php?action=song_add&id=$id&aid=$aid'> $phrases[add_songs]  </a>";
   if(if_admin("",true)){
           print "- <a href='index.php?action=auto_add&id=$id&aid=$aid'> $phrases[auto_search]  </a>";
           }
           print "]<br><br>


   <table width=99% class=grid>";

   $qr = db_query("select * from songs_songs where album='$id'".iif($aid," and album_id='$aid'")." order by album_id DESC, name ASC");

   if (db_num($qr)){
  
    //------- exts --------------
unset($songs_exts);
$qre = db_query("select * from songs_exts");

$songs_exts[0] = $phrases['auto'];    
while($datae = db_fetch($qre)){
    $songs_exts[$datae['id']] = $datae['name'];
}
$songs_exts[-1] = $phrases['without_ext'];
//--------------------------

 /*
   //--------------------------------------    
   $qre  = db_query("select * from songs_exts order by id asc");
  //    $songs_exts[0] = $phrases['without_ext']." / " .$phrases['auto'];  
   while($datae= db_fetch($qre)){
       $songs_exts[$datae['id']] = $datae['name'];
   }
  
   //-----------------------------------  */
   
    print "
    <form action=index.php method=post  name=submit_form>

   <input type=hidden name=id value='$id'>" ;
       $c = 0 ;
   while($data = db_fetch($qr)){
       
     if($tr_class == "row_1"){
         $tr_class = "row_2";
     }else{
         $tr_class = "row_1";
     }
     
       print " <tr id='song_tr_$c' class='$tr_class'>
       <td width=2><input name='song_id[$c]' type='checkbox' value='$data[id]' onclick=\"set_checked_color('song_tr_$c',this,'$tr_class')\"></td>
 <td>$data[name]" ;

 if($data['c_ext']){
   print "&nbsp;&nbsp;  <i><font color=#808080>".$data['c_ext']."</font></i>";
   }
   
   
 $song_ext = get_song_ext($data['ext'],$data['date']);
    
 if($song_ext['name']){
   print "&nbsp;&nbsp;  <i><font color=#D20000>".$song_ext['name']."</font></i>";
   }

   print "</td>
 <td align=center>";
 if($data['album_id']){
 $get_album = db_qr_fetch("select name from songs_albums where id='$data[album_id]'");
 }else{
 $get_album['name'] = "";
 }
 if($get_album['name']){print $get_album['name'];}else{print "$phrases[without_album]";}

 print "</td><td align=center><a href='index.php?action=song_edit&song_id=$data[id]&id=$id'>$phrases[edit] </a> </td><td align=center>
                <a href='index.php?action=song_del&song_id=$data[id]&id=$id' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>
                </td>
                </tr>";
                $c++;
           }
          print "<tr><td width=2><img src='images/arrow_".$global_dir.".gif'></td>
          <td width=100% colspan=5>
          <table><tr><td>

          <a href='#' onclick=\"CheckAll(); return false;\"> $phrases[select_all] </a> -
          <a href='#' onclick=\"UncheckAll(); return false;\">$phrases[select_none] </a>
          &nbsp;&nbsp;  ";
           $qr_albums = db_query("select * from songs_albums where cat='$id' order by id desc");

          print "<select name=action onChange=\"show_options(this)\"><option value='song_ext_set'>$phrases[change_ext]</option>" ;
           if(db_num($qr_albums)){
          print "<option value='song_album_set'>$phrases[move_to_album]</option>" ;
          }

          print "
          <option value='song_singer_set'>$phrases[move_to_singer]</option>
          <option value='song_edit'>$phrases[edit_songs]</option>
          <option value='song_del'>$phrases[delete_songs]</option>
          <option value='new_songs_menu_add'>$phrases[add_to_new_songs_menu]</option> 
          </select></td><td><div id=albums_set_div style=\"display: none; text-decoration: none\">";

     if(db_num($qr_albums)){
      print "<img src='images/arrow_".$global_dir."2.gif'><select name='album_id'>
      <option value='0'> $phrases[without_album] </option> ";

      while($data_albums =db_fetch($qr_albums)){
      print "<option value='$data_albums[id]'>$data_albums[name]</option>";
              }
              print "</select>";
           }

          print "</div>
          <div id=comments_set_div style=\"visibility: inline; text-decoration: none\">";
  $qr_exts = db_query("select * from songs_exts order by id");

      print "<img src='images/arrow_".$global_dir."2.gif'>";
      /*<select name='ext'>
      <option value='0'> $phrases[without_ext] / $phrases[auto] </option> ";
      
      while($data_exts =db_fetch($qr_exts)){
      print "<option value='$data_exts[id]'>$data_exts[name]</option>";
              }
              print "</select>";  */
           print_select_row("ext",$songs_exts); 
          print "</div>
          </td><td><input type=submit value=' $phrases[do_button] ' onClick=\"return confirm('$phrases[are_you_sure]');\"></td></tr></table>
          </td></tr></form> ";
           }else{
              print " <tr><td align=center> $phrases[err_no_songs] </td></tr>";
                   }
           print "</table>

          </center>";
  }

  //--------------------------- Song Edit Form ------------------------------
  if($action=="song_edit"){
  if(!is_array($song_id)){$song_ids=array("$song_id");}else{$song_ids=$song_id;}
    //unset($song_id);

  //------- exts --------------
unset($songs_exts);
$qre = db_query("select * from songs_exts");

$songs_exts[0] = $phrases['auto'];    
while($datae = db_fetch($qre)){
    $songs_exts[$datae['id']] = $datae['name'];
}
$songs_exts[-1] = $phrases['without_ext'];
//--------------------------


   print "<center><form name=sender method=POST action='index.php'>
    <input type=hidden name=action value='song_edit_ok'>
    <input type=hidden name=id value='$id'>";
   $i = 0 ;
   foreach($song_ids as $song_id){
    $qr = db_query("select * from songs_songs where id='$song_id'");
   if(db_num($qr)){
           $data = db_fetch($qr) ;
  
      print "<input type=hidden name=song_id[$i] value='$song_id'>

   <table width=80% class=grid>

    <tr>
    <td><b>$phrases[the_name]</b> </td>
    <td><input name='name[$i]' type='text' value=\"$data[name]\">
    </td>
    <td><b> $phrases[the_ext] </b></td>
    <td> ";
    print_select_row("ext[$i]",$songs_exts,$data['ext']);
    print "</td>
    </tr>  ";

    
//----------- Urls -------------//
$qr3 = db_query("select * from songs_urls_fields where active=1 order by ord");
if(db_num($qr3)){
    print "<tr>";

$c = 0;
$m = 0 ;
while($data3 = db_fetch($qr3)){

if($c==2){
    print "</tr><tr>" ;
    $c=0;
    }


print "<td>
                               <b>$data3[name]</b></td>

                               <td>
                               <input type=\"hidden\" name=\"custom_url_id[$i][$m]\" value=\"$data3[id]\">

                                <table><tr><td><input type=\"text\" name=\"custom_url_data[$i][$m]\" size=\"30\" dir=ltr value=\"".$data["url_".$data3['id']]."\"></td><td>
                                <a href=\"javascript:uploader('songs','custom_url_data[$i][$m]','win".$data3['id'].$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                </td></tr></table>

                                </td>";
                                $c++ ;
                                $m++;
                                
}
print "</tr>";
unset($field_data,$data3,$m,$c);
}
//----------- Custom Fields -------------//
$qr4 = db_query("select * from songs_songs_fields where active=1 order by ord");
if(db_num($qr4)){
    print "<tr>";

$c = 0;
$m = 0 ;
while($data4 = db_fetch($qr4)){

if($c==2){
    print "</tr><tr>" ;
    $c=0;
    }

//$field_data = db_qr_fetch("select * from songs_custom_fields where cat='$data4[id]' and song_id='$data[id]'");
print "<td>
                               <b>$data4[name]</b></td>
                               <td>
                               <input type=\"hidden\" name=\"song_field_id[$i][$m]\" value=\"$data4[id]\">
                               ".get_song_field("song_field_data[$i][$m]",$data4,$data["field_".$data4['id']])."</td>";
                                $c++ ;
                                $m++;

}
print "</tr>";
unset($data4,$m,$c);
}
//-------------------------------


    print "<tr>
    <td><b>$phrases[lyrics]</b></td>
    <td colspan=3><textarea name='lyrics[$i]' rows=5 cols=40>$data[lyrics]</textarea>
    </td></tr>
    
    
    <tr>
    <td><b>$phrases[video_id]</b></td>
    <td><input type='text' size='5' name='video_id[$i]' id='video_id_{$i}' value=\"$data[video_id]\" onBlur=\"get_video_name('video_name_{$i}',this.value);\"  onChange=\"get_video_name('video_name_{$i}',this.value);\">
    <a href=\"javascript:;\" onClick=\"video_select('video_id_{$i}');\" title=\"$phrases[select_video]\"><img src=\"images/video_select.gif\"></a>
       <div id='video_name_{$i}'></div>
         <div id='video_name_{$i}_loading' style='display:none;'><img src='images/loading.gif'></div> ";
        if($data['video_id']){
       print "
        <script>
        get_video_name('video_name_{$i}',$data[video_id]);
        </script>";
        }
    print "</td>
    <td><b>$phrases[custom_ext] </b></td>
    <td><input type='text' name=\"c_ext[$i]\" value=\"$data[c_ext]\" size=20></td>
    </tr>
    
    </table><br>";
    ++$i;
    }
            }

            if($i > 0){
            print "<input type=submit value='  $phrases[edit]  '></center>";
                    }else{
            print_admin_table("<center> $phrases[err_wrong_url] </center>");
            }
            print "</form>";
          }
 //------------------------------ Songs Add Form -----------------------------
  if($action=="song_add"){
      $id=intval($id); 
      
$qr = db_query("select songs_singers.id,songs_singers.name,songs_cats.name as cat_name , songs_cats.id as cat_id from songs_singers,songs_cats where songs_cats.id=songs_singers.cat and songs_singers.id='$id'");
 
 if(db_num($qr)){
     
$data = db_fetch($qr);


//------- exts --------------
unset($songs_exts);
$qre = db_query("select * from songs_exts");

$songs_exts[0] = $phrases['auto'];    
while($datae = db_fetch($qre)){
    $songs_exts[$datae['id']] = $datae['name'];
}
$songs_exts[-1] = $phrases['without_ext'];
//--------------------------


 if(!$add_limit){
$add_limit = $settings['songs_add_limit'] ;
  }
  
  $add_limit = intval($add_limit);
  

  print "
  <p align=$global_dir><img src='images/arrw.gif'> <a href='index.php?action=singers&cat=$data[cat_id]'>$data[cat_name]</a> / <a href='index.php?action=singer_edit&id=$data[id]'>$data[name]</a> / $phrases[add_songs]</p>";
  unset($qr,$data);
  
  print "<center>
  <form method=\"POST\" action=\"index.php\">

      <input type=\"hidden\" name=\"id\" value='$id'>
      <input type=\"hidden\" name=\"aid\" value='$aid'> 
      <input type=hidden name=action value=song_add>
      <table width=30% class=grid>
      <tr><td align=center> $phrases[fields_count] : <input type=text name=add_limit value='$add_limit' size=3>
      &nbsp;&nbsp;<input type=submit value='$phrases[edit]'></td></tr></table></form>

      <br>
       <form method=\"POST\" action=\"index.php\" name=\"sender\">
<div align=\"center\">
<input type=\"hidden\" name=\"action\" value=\"song_add_ok\">
      <input type=\"hidden\" name=\"id\" value=\"$id\">
        <input type=hidden name=add_limit value='$add_limit'>
      <input type=hidden name='aid' value='$aid'>
               
                

    <table width=30% class=grid>
      <tr><td align=center> $phrases[the_album] : <select name=album_id><option value='0'>$phrases[without_album]</option>";
      $qr_albums = db_query("select * from songs_albums where cat='$id' order by year desc,id desc");

      while($data_albums =db_fetch($qr_albums)){
      print "<option value='$data_albums[id]'".iif($aid==$data_albums['id']," selected").">$data_albums[name]".iif($data_albums['year']," ($data_albums[year])")."</option>";
              }


     print "</select></td></tr></table>
      <br>";




//-------------- Auto Add Operation ----------
    if($auto_add && in_array($auto_folder,$autosearch_folders)){
      //  $dir_for_read = CWD . ($script_path ? "/" . $script_path  :"") . "/".$dir_for_read ;

       $dir_for_read = $auto_folder . $auto_subfolder ;
     //  print $dir_for_read;
     //$auto_search_exclude_exts

     if(file_exists($dir_for_read)){
       $allowed_types_arr = explode(",",trim($allowed_ext));
       $exclude_types_arr = explode(",",trim($auto_search_exclude_exts));

       foreach($allowed_types_arr as $ext){
           if(!in_array($ext,$exclude_types_arr)){
           $allowed_types[] = $ext;
           }
       }

       $files_list = get_files($dir_for_read,$allowed_types,$subdirs_search);
       $i =0;
       
       if(function_exists('iconv')){ $conv_path = true;}else{$conv_path = false;}
       
       foreach($files_list as $file_name){
           
           if($conv_path){
           $file_name = iconv( "cp1256", "utf-8",$file_name);
           }
           
           $sql = "select count(*) as count from songs_songs where ( name like 'DumpText' ";
           for($m=0;$m < count($urls_sets);$m++){
           if($urls_sets[$m]['active']){
           $sql .= "or url_".$urls_sets[$m]['id']." like '%".db_escape($file_name)."' ";
           }
           }
      //     $sql = substr($sql,0,strlen($sql)-1);
           $sql .= " )";
            $sql .= iif($search_in_cat_only," and album='$id'","");
            
           $url_exists = valueof(db_qr_fetch($sql),"count");
        
        //    print ($sql."<br>");
         
           if(!$url_exists){
               $new_files_list[$i] = $file_name ;
               $i++;
           }
       }
      //  print_r($new_files_list) ;
       unset($files_list);

if(count($new_files_list)){
$add_limit = count($new_files_list) ;
$auto_add_ok = 1;
}else{
 print_admin_table("<center> $phrases[no_new_files] </center>") ;
}
     }else{
         print_admin_table("<center> $phrases[err_autosearch_folder_not_exists] </center>") ;
     }
    }
    //-----------------------------------
    
for ($i=0;$i<$add_limit;$i++){
                     print "<br><table  class=grid cellspacing=\"0\" width=\"98%\" >

                     <tr><td ><b>#".($i+1)."</b></td></tr>";

if($auto_add_ok){

switch ($auto_url_field){
case "url" : $url_value = iif($use_complete_url,$scripturl."/").$new_files_list[$i];break;
case substr($auto_url_field,0,7)=="custom_" : $custom_url_value[substr($auto_url_field,7,strlen($auto_url_field)-7)]=iif($use_complete_url,$scripturl."/").$new_files_list[$i];break;
}


$auto_name_value = basename($new_files_list[$i]);
switch ($auto_name_field){
case "name" : $name_value = $auto_name_value;break;
case substr($auto_url_field,0,7)=="custom_" : $custom_url_value[substr($auto_url_field,7,strlen($auto_url_field)-7)]=$auto_name_value;break;
}

 }
 
print "<tr>
                                <td>
                                <b>$phrases[the_name]</b></td><td><input type=\"text\" name=\"name[$i]\" value=\"$name_value\" size=\"20\"></td>

                                <td> <b>  $phrases[the_ext] </b></td>
                                <td>";
                                print_select_row("ext[$i]",$songs_exts);
print " </td></tr>";
//-------- Custom Urls --------------
$qr = db_query("select * from songs_urls_fields where active=1 order by ord");
if(db_num($qr)){
    print "<tr>";

$c = 0;
$m = 0 ;
while($data = db_fetch($qr)){

if($c==2){
    print "</tr><tr>" ;
    $c=0;
    }

print "<td>
                               <b>$data[name]</b></td>

                               <td>
                               <input type=\"hidden\" name=\"custom_url_id[$i][$m]\" value=\"$data[id]\">

                                <table><tr><td><input type=\"text\" name=\"custom_url_data[$i][$m]\" size=\"30\" dir=ltr value=\"".$custom_url_value[$data['id']]."\"></td><td>
                                <a href=\"javascript:uploader('songs','custom_url_data[$i][$m]','win".$data['id'].$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[uplaod_file]'></a>
                                </td></tr></table>

                                </td>";
                                $c++ ;
                                $m++;

}
print "</tr>";
}
//----------- Custom Fields -------------//
$qr4 = db_query("select * from songs_songs_fields where active=1 order by ord");
if(db_num($qr4)){
    print "<tr>";

$c = 0;
$m = 0 ;
while($data4 = db_fetch($qr4)){

if($c==2){
    print "</tr><tr>" ;
    $c=0;
    }

//$field_data = db_qr_fetch("select * from songs_custom_fields where cat='$data4[id]' and song_id='$data[id]'");
print "<td>
                               <b>$data4[name]</b></td>
                               <td>
                               <input type=\"hidden\" name=\"song_field_id[$i][$m]\" value=\"$data4[id]\">
                               ".get_song_field("song_field_data[$i][$m]",$data4,$data4['value'])."</td>";
                                $c++ ;
                                $m++;

}
print "</tr>";
unset($data4,$m,$c);
}
//--------------------------------------
                        print "
                          <tr>
                                 <td>
                                <b>$phrases[lyrics]</b></td>

<td colspan=3>                            
                                 <textarea rows='2' name='lyrics[$i]' cols='30'></textarea> </td>

     </tr>
     
     <tr>
         <td><b>$phrases[video_id]</b></td>
    <td><input type='text' size='5' name='video_id[$i]' id='video_id_{$i}' value=\"\" onBlur=\"get_video_name('video_name_{$i}',this.value);\"  onChange=\"get_video_name('video_name_{$i}',this.value);\">
    <a href=\"javascript:;\" onClick=\"video_select('video_id_{$i}');\" title=\"$phrases[select_video]\"><img src=\"images/video_select.gif\"></a>
       <div id='video_name_{$i}'></div>
         <div id='video_name_{$i}_loading' style='display:none;'><img src='images/loading.gif'></div> ";

    print "</td>
     <td><b>$phrases[custom_ext] </b></td>
    <td><input type='text' name=\"c_ext[$i]\" value=\"$data[c_ext]\" size=20></td></tr>
                        </table><br>";

                        }

print "<br>
<input type=\"submit\" value=\"$phrases[add_button]\" style=\"width:100\">

     <br>   </form>\n";

 }else{
     print_admin_table("<center> $phrases[err_wrong_url]</center>");
 }
 }

//-------------------------------- Auto Add --------------------------------------------------
if($action=="auto_add"){

    $id=intval($id);

   //if_cat_admin($cat);

if_singer_admin($id);

   $qr = db_query("select songs_singers.id,songs_singers.name,songs_cats.name as cat_name , songs_cats.id as cat_id from songs_singers,songs_cats where songs_cats.id=songs_singers.cat and songs_singers.id='$id'");
 
 if(db_num($qr)){
     
$data = db_fetch($qr);



  print "
  <p align=$global_dir><img src='images/arrw.gif'> <a href='index.php?action=singers&cat=$data[cat_id]'>$data[cat_name]</a> / <a href='index.php?action=singer_edit&id=$data[id]'>$data[name]</a> / $phrases[auto_search]</p>";
  unset($qr,$data);


   print "<form action=index.php method=post>
   <input type=hidden name=action value='song_add'>
   <input type=hidden name=auto_add value='1'>
   <input type=hidden name=id value='$id'>
   <input type=hidden name='aid' value='$aid'>  
      
   <center><table dir=ltr width=80% class=grid>
   <tr><td colspan=2 align=center> <p class=title>$phrases[auto_search] </p></td></tr>

   <tr><td width=150>Folder : </td><td>";
   print_select_row("auto_folder",$autosearch_folders,null,null,null,null,true);
   print  "<input type=text name=auto_subfolder value='/'></td></tr>
   <tr><td></td><td><input type=\"checkbox\" name=\"subdirs_search\" value=1 checked> Include Sub-Directories </td></tr>
   <tr><td></td><td><input type=\"checkbox\" name=\"search_in_cat_only\" value=1> Search For Exists Files in This Singer ONLY </td></tr>

   <tr><td> Extentions : </td><td>
    <input type=text name=allowed_ext value='$auto_search_default_exts' size=50> </td></tr>

    <tr><td width=150> URL Field : </td><td><select name=auto_url_field>";
    /*
foreach($data_fields_checks as $key=>$value){
if($value !='image_n_thumb'){
print "<option value='$value'>$key</option>";
        }
}
*/

  $qr=db_query("select * from songs_urls_fields where active=1 order by id");
  while($data = db_fetch($qr)){
      print "<option value='custom_$data[id]'>$data[name]</option>";
  }
   print  "</select> <input type=\"checkbox\" name=\"use_complete_url\" value=1> Use Complete URL</td></tr>

     <tr><td width=150> Filename Field : </td><td><select name=auto_name_field>
     <option value=''>None</option>
     <option value='name'>$phrases[the_name]</option>";
     /*
foreach($data_fields_checks as $key=>$value){
if($value !='image_n_thumb'){
print "<option value='$value'>$key</option>";
        }
}
        */
  $qr=db_query("select * from songs_urls_fields where active=1 order by id");
  while($data = db_fetch($qr)){
      print "<option value='custom_$data[id]'>$data[name]</option>";
  }
   print  "</select></td></tr>
    <tr><td colspan=2 align=center>
   <input type=submit value=' Search '></td></tr></table></form>";
 }else{
     print_admin_table("<center>$phrases[err_wrong_url]</center>");
 }
   }

 //----------------------------- Album Edit --------------------------------
if($action=="album_edit"){
  $data = db_qr_fetch("select * from songs_albums where id='$album_id'");

  
      $data_singer = db_qr_fetch("select * from songs_singers where id='$id'"); 
    $data_cat = db_qr_fetch("select name from songs_cats where id='$data_singer[cat]'");     
    print "<img border=0 src='images/arrw.gif'><a href='index.php?action=singers'> $phrases[the_songs_and_singers] </a> / <a href='index.php?action=singers&cat=$data_singer[cat]'> $data_cat[name]</a> / <a href='index.php?action=singer_edit&id=$data_singer[id]'> $data_singer[name]</a> / $data[name]<br><br>"; 
  
  
  
  print "<center>

  <table width=60% class=grid>
  <form name=sender method=POST action='index.php'>
  <input type=hidden name=action value='album_edit_ok'>
  <input type=hidden name=id value='$id'>
  <input type=hidden name=album_id value='$album_id'>

  <tr><td colspan=2><center><font class=title>$phrases[edit_album] </font></center></td></tr>
  <tr>
  <td>
  <b>$phrases[the_name] : </b></td>
  <td><input type=text value=\"$data[name]\" size=30 name=name></td>
  </tr>
  <tr>
 <td>
  <b>$phrases[the_image] :</b></td>
  <td> <table><tr><td><input type=text value=\"$data[img]\" dir=ltr size=30 name=img></td><td><a href=\"javascript:uploader('albums','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td>
   </tr>
<tr>
  <td>
  <b>$phrases[release_year] : </b></td>
  <td><input type=text value=\"".iif($data['year'],$data['year'],"")."\" size=6 name='year'></td>
  </tr>";
  
   //---------- album fields -----------------
  $cf = 0 ;

$qrf = db_query("select * from songs_albums_fields where active=1 order by ord");
   if(db_num($qrf)){
    
while($dataf = db_fetch($qrf)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
    <tr><td width=25%><b>$dataf[name] : </b><br>$dataf[details]</td><td>";
    print get_song_field("custom[$cf]",$dataf,$data["field_".$dataf['id']]);
        print "</td></tr>";
$cf++;
}

}
  //------------------------------------------ 

   print "</table>  ";
   
   
                      
             //-------------- Tags ------------//                 
                              print " <br> <br>
                              <fieldset style=\"width:60%;text-align:$global_align\">
                              <legend><b>$phrases[page_custom_info]</b></legend>
                              <table width=100%>
                              
                              <tr><td><b>$phrases[the_title] : </td><td>
                              <input type=text size=30 name=page_title value=\"$data[page_title]\"></td></tr>
                              
                              <tr><td><b>$phrases[the_description] : </td><td>
                              <input type=text size=30 name=page_description value=\"$data[page_description]\"></td></tr>
                              
                              <tr><td><b>$phrases[the_page_keywords] : </td><td>
                              <input type=text size=30 name=page_keywords value=\"$data[page_keywords]\"></td></tr>
                              
                              <tr><td><b>$phrases[page_file_name] : </td><td>
                              <input type=text size=30 name='page_name' value=\"$data[page_name]\" dir='ltr'></td></tr>
                              
                              </table>
                              <br>
                              <font color='#808080'>* $phrases[leave_blank_to_use_default_settings] </font>
                              </fieldset><br>
                              ";
                              print "
        <input type=\"submit\" value=\"$phrases[edit]\">
        
        </form>";
}

//-------------------- album move ------------------------
if($action=="album_move"){
    if_singer_admin($id);
   $album_id = (array) $album_id;
   
 print "<center> <p class=title>$phrases[album_move]</p>";
if(count($album_id)){


 $c = 1 ;
 $data_from = db_qr_fetch("select name from songs_singers where id='$id'");
print "<form action=index.php method=post>
<input type=hidden name=action value='album_move_ok'>

<input type=hidden name=singer_from value='$id'>

<table width=90% class=grid><tr><td align=center colspan=2><b> $phrases[move_from] : </b> $data_from[name] </td></tr>
<td><b>#</b></td><td><b> $phrases[the_name] </b></td></tr>";

foreach($album_id as $iid){
   $data = db_qr_fetch("select name,id from songs_albums where id='$iid'");
   print "<input type=hidden name='album_id[]' value='$iid'>  ";
print "<tr><td><b>$c</b></td><td>$data[name]</td></tr>";
++$c;
    }
 print "<tr><td colspan=2 align=center><b>$phrases[move_to]  : </b><select name='id'>";



 if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions']){
    $qr=db_query("select id,name,cat from songs_singers where cat IN ($usr_data[permisions]) and id !='$id' order by cat,name ASC");
    }

     }else{

$qr=db_query("select id,name,cat from songs_singers where id !='$id' order by cat, name asc");
      }


         $last_cat = -1;
 while($data = db_fetch($qr)){
     
 if($last_cat != $data['cat']){
     if($last_cat != -1){print "</optgroup>";}
     $data_cat = db_qr_fetch("select name from songs_cats where id='$data[cat]'");
     print "<optgroup label=\"$data_cat[name]\">";
     $last_cat = $data['cat'];
 }
 
 
         print "<option value='$data[id]'>$data[name]</option>";
         }

print "</optgroup>
</select> <input type=submit value=' $phrases[next] '></td></tr></table></form>";
}else{
        print "<center>  $phrases[please_select_albums] </center>";
        }   
}
