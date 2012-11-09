<?
if(!defined('IS_ADMIN')){die('No Access');}  

  //------------------------------------- New songs Menu ------------------------------
if($action=="new_songs_menu" || $action=="new_songs_menu_add" || $action=="new_songs_menu_del"){
       if_admin("new_songs");


$cat = (int) $cat;
$singer = (int) $singer;
$album = (int) $album;


if($action=="new_songs_menu_add"){

  if(!is_array($song_id)){ $song_id = array(intval($song_id));}
  
if(is_array($song_id)){
foreach($song_id as $id){ 
$id = intval($id); 
$cntx = db_qr_fetch("select count(id) as count from songs_songs where id='$id'");
     if($cntx['count']){
        db_query("insert into songs_new_songs_menu (song_id) values ('$id')");
        }else{
        print_admin_table("<center>$phrases[err_invalid_song_id]</center>");
        }
}
}

//------------
$c=1;
$qr=db_query("select id from songs_new_songs_menu order by ord asc");
while($data=db_fetch($qr)){
db_query("update songs_new_songs_menu set ord='$c' where id='$data[id]'");
$c++;
}
//------------


}


if($action=="new_songs_menu_del"){
    $id=(array) $id;
    foreach($id as $idx){
 db_query("delete from songs_new_songs_menu where id='$idx'");
    }
  }

  print "<center>
  <p class=title>$phrases[new_songs_menu]</p>
  
  <table width=99% class=grid><tr>
  <td><div id='songs_new_menu_add_form'>
  </div>
  
  <td width=25>
  <div id='ajax_loading' style=\"display:none;\"><img src=\"images/loading.gif\"></div>
  </td>
  </tr></table></form>
  <script>
  get_songs_new_menu_add_form(".$cat.",".$singer.",$album);
  </script>
  <br>
  
 <table width=99%><tr><td width=50%>
  
  <form action=index.php method=post name=sender>
  <input type=hidden name=action value='new_songs_menu_add'>
  <table width=100% class=grid><tr><td align=center> <b> $phrases[song_id] :</b>
  <input type=text name=song_id size=4>
  <input type=submit value='$phrases[add_button]'></td></tr></table></form>

  </td><td width=50%>";
          
print_admin_table("<center>$phrases[new_songs_menu_note]<br><br></center>","100%");
print "</td></tr></table>
<br><br>";

          
$qr=db_query("select * from songs_new_songs_menu order by ord asc");
if(db_num($qr)){

print " <form action='index.php' method=post name='submit_form' onSubmit=\"return confirm('$phrases[are_you_sure]');\">
              <input type=hidden name='action' value='new_songs_menu_del'>
              
          <table width=99% class=grid><tr><td>
  
          <div id=\"new_songs_list\">";
          
while($data = db_fetch($qr)){

        $qr2=db_query("select songs_songs.id as id ,songs_songs.name as name,songs_singers.name as singer from songs_songs,songs_singers where songs_songs.album=songs_singers.id and songs_songs.id='$data[song_id]'");
       if(db_num($qr2)){
               $data2 = db_fetch($qr2);
        print "<div id=\"item_$data[id]\" onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='#FFFFFF'\">
        <table width=100%>
        
        <tr>
     <td width=10><input type='checkbox' name=id[] value='$data[id]'></td>
       <td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img title='$phrases[click_and_drag_to_change_order]' src='images/move.gif'></span> 
      </td>
        <td>$data2[singer] -> <b>$data2[name]</b></td>
      <td width=100><a href=\"index.php?action=new_songs_menu_del&id=$data[id]\" onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
       </tr></table></div>
       ";
       }else{
       db_query("delete from songs_new_songs_menu where song_id='$data[song_id]'");
               }
        }
        
          print "</div></td></tr></table><br>
         <table width=99% class=grid><tr>
         <tr><td width=2><img src='images/arrow_".$global_dir.".gif'></td>
          <td width=100%>

          <a href='#' onclick=\"CheckAll(); return false;\"> $phrases[select_all] </a> -
          <a href='#' onclick=\"UncheckAll(); return false;\">$phrases[select_none] </a>
          &nbsp;  &nbsp;
          <input type=submit value=' $phrases[delete] '>
          </td></tr></table>
        </form></center> ";

         print "<script type=\"text/javascript\">
        init_new_songs_sortlist();
</script>";  
        }else{
                print_admin_table("<center> $phrases[no_data] </center>");
                }
      

        }
?>