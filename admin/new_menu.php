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
//------------------------------------- New Stores Menu ------------------------------
if($action=="new_menu" || $action=="new_menu_add" || $action=="new_menu_del"){
       if_admin("new_stores");
       
$cat = (int) $cat;
$singer = (int) $singer;
$album = (int) $album;

if($action=="new_menu_add"){
    


if($add_singer){
$cntx = db_qr_fetch("select count(id) as count from songs_singers where id='$singer'");
$type = 'singer';
$add_id = $singer;
}else{
$cntx = db_qr_fetch("select count(id) as count from songs_albums where id='$album'");
$type = 'album';
$add_id = $album;
}
     if($cntx['count']){
        db_query("insert into songs_new_menu (`cat`,`type`) values ('$add_id','$type')");
        
//------------
$c=1;
$qr=db_query("select id from songs_new_menu order by ord asc");
while($data=db_fetch($qr)){
db_query("update songs_new_menu set ord='$c' where id='$data[id]'");
$c++;
}
//------------

        }else{
        print_admin_table("<center>$phrases[err_invalid_id]</center>");
        }
        }
//------ del ----------//        
if($action=="new_menu_del"){
    $id = (array) $id;
    
    foreach($id as $iid){
 db_query("delete from songs_new_menu where id='$iid'");
    }
  }
//------------------//

  print "<center>
  <p align=center class=title>$phrases[new_stores_menu] </p>
  
  <table width=90% class=grid><tr>
  <td><div id='new_menu_add_form'>
  </div>
  
  <td width=25>
  <div id='ajax_loading' style=\"display:none;\"><img src=\"images/loading.gif\"></div>
  </td>
  </tr></table></form>
  <script>
  get_new_menu_add_form(".$cat.",".$singer.");
  </script>
  <br>";

          
$qr=db_query("select * from songs_new_menu order by ord");
if(db_num($qr)){
print "
<form action='index.php' method='post' name='submit_form'>
<input type='hidden' name='action' value='new_menu_del'>

<table width=90% class=grid><tr><td>
          <div id=\"new_stores_list\">";
          
while($data = db_fetch($qr)){

     if($data['type']=="singer"){
     $qr2=db_query("select songs_singers.id as id ,songs_singers.name as name,songs_cats.name as cat from songs_singers,songs_cats where songs_singers.cat=songs_cats.id and songs_singers.id='$data[cat]'");
     }else{
     $qr2=db_query("select songs_albums.id as id ,songs_albums.name as name,songs_singers.name as cat,songs_cats.name as cat_first from songs_singers,songs_albums,songs_cats where songs_albums.cat=songs_singers.id and songs_singers.cat=songs_cats.id and songs_albums.id='$data[cat]'");
     }  
       if(db_num($qr2)){
               $data2 = db_fetch($qr2);
        print "<div id=\"item_$data[id]\" onmouseover=\"this.style.backgroundColor='#EFEFEE'\" onmouseout=\"this.style.backgroundColor='#FFFFFF'\">
        <table width=100%><tr>
        
        <td width=10><input type='checkbox' name=id[] value='$data[id]'></td>
                  <td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img title='$phrases[click_and_drag_to_change_order]' src='images/move.gif'></span> 
      </td>
     
        <td>".iif($data2['cat_first'],"$data2[cat_first] -> ")."$data2[cat] ->  <b>$data2[name]</b></td>
      <td width=100><a href=\"index.php?action=new_menu_del&id=$data[id]\" onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
       </tr></table>
       </div>
       
       
       ";
       }else{
          // print "wrong $data[cat] . $data[type]";
       db_query("delete from songs_new_menu where cat='$data[cat]'");
               }
        }
        
        print "</div></td></tr></table>
                      <br>
         <table class=grid width=90%>            
     <tr><td width=2><img src='images/arrow_".$global_dir.".gif'></td>
          <td width=100%>


          <a href='#' onclick=\"CheckAll(); return false;\"> $phrases[select_all] </a> -
          <a href='#' onclick=\"UncheckAll(); return false;\">$phrases[select_none] </a>
        &nbsp;&nbsp;  
         
          
          <input type='submit' value=' $phrases[delete] ' onClick=\"return confirm('$phrases[are_you_sure]');\">
          
         </td></tr>
          </table> 
          </form> 
           </center>";  
           
           print "<script type=\"text/javascript\">
        init_sortlist('new_stores_list','set_new_stores_sort');
</script>";  
        }else{
                print_admin_table("<center> $phrases[no_data] </center>");
                }
       

        
      
        }
?>
