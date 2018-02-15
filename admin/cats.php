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

if($action=="cats" ||  $action=="cat_del" || $action=="edit_cat_ok" || $action=="cat_add_ok" || $action=="cat_disable" || $action=="cat_enable"){
        if_admin();

//---------------------------------------------------------
if($action =="cat_add_ok"){
    
    $ord_dt = db_qr_fetch("select max(ord) as max from songs_cats where cat='$cat' limit 1");
$ord = intval($ord_dt['max'])+1;


  db_query("insert into songs_cats (name,download_for_members,listen_for_members,page_name,page_title,page_description,page_keywords,active,ord) values('".db_escape($name)."','".intval($download_for_members)."','".intval($listen_for_members)."','".db_escape($page_name)."','".db_escape($page_title)."','".db_escape($page_description)."','".db_escape($page_keywords)."','1','$ord')");
        }
//----------------------------------------------------------
 if($action=="cat_del"){
  $id=intval($id);
  
   $qr = db_query("select id from songs_singers where cat='$id'");
   while($data = db_fetch($qr)){
    delete_singer($data['id']);
    }


      db_query("delete from songs_cats where id='$id'");
  
 }
//-----------------------------------------------------------
 if($action=="edit_cat_ok"){
   $id=intval($id);  
 db_query("update songs_cats set name='".db_escape($name)."',download_for_members='".intval($download_for_members)."',listen_for_members='".intval($listen_for_members)."',page_name='".db_escape($page_name)."',page_title='".db_escape($page_title)."',page_description='".db_escape($page_description)."',page_keywords='".db_escape($page_keywords)."' where id='$id'");
         }
//-----------------------------------------------------------

if($action=="cat_disable"){
        db_query("update songs_cats set active=0 where id='$id'");
        }

if($action=="cat_enable"){

       db_query("update songs_cats set active=1 where id='$id'");
        }
//-----------------------------------------------

  print "<p class=title align=center>$phrases[the_songs_cats] </p>
  
  <img src='images/add.gif'>&nbsp;<a href='index.php?action=cat_add'>$phrases[add_button]</a><br><br>";
           



 $qr = db_query("select * from  songs_cats order by ord");
 if(db_num($qr)){
 print "<center>

 <table width=90% class=grid><tr><td>
  <div id=\"cats_list\">";
 while($data = db_fetch($qr)){
     
     if($tr_class == "row_1"){
         $tr_class = "row_2";
     }else{
         $tr_class = "row_1";
     }
     
     
      print "<div id=\"item_$data[id]\" class='$tr_class'>
      <table width=100%><tr>
    
      <td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img src='images/move.gif'></span> 
      </td>
      
      <td>$data[name]</td>
      <td width=200>";
      
      if($data['active']){
                        print "<a href='index.php?action=cat_disable&id=$data[id]'>$phrases[disable]</a> - " ;
                        }else{
                        print "<a href='index.php?action=cat_enable&id=$data[id]'>$phrases[enable]</a> - " ;
                        }
                        
      print "<a href='index.php?action=cat_edit&id=$data[id]'>$phrases[edit] </a> - 
      <a href=\"index.php?action=cat_del&id=$data[id]\" onClick=\"return confirm('$phrases[del_cat_warning]');\">$phrases[delete]</a></td>
      </tr></table>
      </div>";
         }
       print "
       </div></td></tr></table>
       ";
       
print "<script type=\"text/javascript\">
        init_cats_sortlist();
</script>";
 }else{
     print_admin_table("<center>$phrases[no_cats]</center>");
 }
        }
 //-------------- cat add -----------------
 if($action=="cat_add"){
     
     print " 
       <img src='images/arrw.gif'>&nbsp;<a href='index.php?action=cats'>$phrases[the_songs_cats]</a> / $phrases[add_button] <br><br>  
       
     <center>
     <form method=\"POST\" action=\"index.php\">

   <input type=hidden name='action' value='cat_add_ok'>
   
   <table width=60% class=grid><tr>
   <td><b> $phrases[the_name] </b></td> 
  <td> <input type=text name=name size=30>
    </td></tr>
  <tr> <td>
                <b>$phrases[download_permission]</b></td>
                                <td>";
                                print_select_row("download_for_members",array("0"=>$phrases['for_all_visitors'],"1"=>$phrases['for_members_only']));
               print "
                       </td></tr>
                       
             
              <tr> <td>
                <b>$phrases[listen_permission]</b></td>
                                <td>";
                                print_select_row("listen_for_members",array("0"=>$phrases['for_all_visitors'],"1"=>$phrases['for_members_only']));
               print "
                       </td></tr>
                       
                       </table><br>";

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
                    
          
            

   </center>  ";
   
 }
 //-------------------------------------------------------------
        if($action == "cat_edit"){
               $id = intval($id);
               
               $qr = db_query("select * from songs_cats where id='$id'");

               if(db_num($qr)){
                   $data=db_fetch($qr);
               print "
              <img src='images/arrw.gif'>&nbsp;<a href='index.php?action=cats'>$phrases[the_songs_cats]</a> / $data[name] <br><br>      
               <center>

                <table border=0 width=\"60%\"  class=grid><tr>

                <form method=\"POST\" action=\"index.php\">

                      <input type=hidden name=\"id\" value='$id'>

                      <input type=hidden name=\"action\" value='edit_cat_ok'> ";


                  print "  <tr>
                                <td width=\"50\">
                <b>$phrases[the_name]</b></td><td>
                <input type=\"text\" name=\"name\" value=\"$data[name]\" size=\"29\"></td>
                        </tr>

 <tr> <td>
                <b>$phrases[download_permission]</b></td>
                                <td>";
                                print_select_row("download_for_members",array("0"=>$phrases['for_all_visitors'],"1"=>$phrases['for_members_only']),$data['download_for_members']);
               print "
                       </td></tr>
                       
             
              <tr> <td>
                <b>$phrases[listen_permission]</b></td>
                                <td>";
                                print_select_row("listen_for_members",array("0"=>$phrases['for_all_visitors'],"1"=>$phrases['for_members_only']),$data['listen_for_members']);
               print "
                       </td></tr>
                       
                                 
                       </table><br>";
                   
             //-------------- Tags ------------//                 
                              print " <br>
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
                    
          
             

</form>    </center>\n";
               }else{
                   print_admin_table("<center>$phrases[err_wrong_url]</center>");
               }
                      }
