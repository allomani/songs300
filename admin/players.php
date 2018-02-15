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

if($action=="players" || $action=="players_edit_ok" || $action=="players_add_ok" || $action=="players_del"){
 
 if_admin("players");


//-------- del ----------
if($action=="players_del"){
   $id = (int) $id; 
   if($id > 1){
       db_query("delete from songs_players where id='$id'");
   }  
}

//----- edit ---------- 
if($action=="players_edit_ok"){
    $id = (int) $id;
    db_query("update songs_players set name='".db_escape($name)."',int_content='".db_escape($int_content,false)."',playlist_content='".db_escape($playlist_content,false)."',video_content='".db_escape($video_content,false)."',ext_content='".db_escape($ext_content,false)."',
    ext_mime='".db_escape($ext_mime)."',ext_filename='".db_escape($ext_filename)."',exts='".db_escape($exts)."' where id='$id'");
    
}

//----- add ---------
if($action=="players_add_ok"){
    db_query("insert into songs_players (name) values ('".db_escape($name)."')");
    $id = mysql_insert_id();
    $data=db_qr_fetch("select * from songs_players where id='1'");
    db_query("update songs_players set int_content='".db_escape($data['int_content'],false)."',ext_content='".db_escape($data['ext_content'],false)."',
    ext_mime='".db_escape($data['ext_mime'])."',ext_filename='".db_escape($data['ext_filename'])."',
    exts='',view_style='".intval($data['view_style'])."' where id='$id'");
 print "<script>window.location=\"index.php?action=players_edit&id=$id\";</script>";    
}  


print "<p align=center class=title>$phrases[the_players]</p>";

print "<img src='images/add.gif'>&nbsp; <a href='index.php?action=players_add'>$phrases[players_add]</a> <br><br>";



$qr=db_query("select id,name from songs_players");
if(db_num($qr)){
    print "<center><table width=90% class=grid>";
    while($data=db_fetch($qr)){
        
    if($tr_class=='row_1'){
           $tr_class = 'row_2';
    }else{
        $tr_class  = 'row_1';
    }
        print "<tr class='$tr_class'><td>$data[name]</td><td align='$global_align_x'><a href='index.php?action=players_edit&id=$data[id]'>$phrases[edit]</a>
         ".iif($data['id'] > 1 , "- <a href='index.php?action=players_del&id=$data[id]' onClick=\"return confirm('".$phrases['are_you_sure']."');\">$phrases[delete]</a>")."
        </td></tr>";
    }
    print "</table></center>";
}else{
print_admin_table("<center> $phrases[no_players] </center>");
}

    
}


//----------- players edit ------------------
if($action=="players_edit"){
    
    if_admin("players");
    
    $id = (int) $id;
    $qr=db_query("select * from songs_players where id='$id'");
    if(db_num($qr)){
        $data=db_fetch($qr);
        
        print "<img src='images/arrw.gif'>&nbsp; <a href='index.php?action=players'>$phrases[the_players]</a> / $data[name] <br><br>"; 
        print "<center>
        <form action='index.php' method=post>
        <input type=hidden name='action' value='players_edit_ok'>
        <input type=hidden name='id' value='$id'>
        
          <table width=70% class=grid>
        <tr><td><b>$phrases[the_name]</b></td><td><input type=text name='name' value=\"$data[name]\"></td></tr>
         ".iif($id > 1,"<tr><td><b>$phrases[extensions]</b></td><td><input type=text name='exts' dir=ltr value=\"$data[exts]\" style=\"font-family:Arial, Helvetica, sans-serif;font-weight:bold;\">
          <br><font size=1 color='#ACACAC'>$phrases[use_comma_between_types]</font></td></tr>")."
            
        </table><br>
        
        <fieldset style='width:69%'>
        <legend><h3>$phrases[internal_player]</h3></legend>
        
        <br>
        
        <table width=100% class=grid>
        <tr><td colspan=2><h3>$phrases[the_songs]</h3></td></tr>
       
           
        <tr><td><b>$phrases[the_content]</b></td><td>
            <textarea cols=50 rows=15 name='int_content' dir=ltr>".htmlspecialchars($data['int_content'])."</textarea></td></tr>
          
         
            </table><br>
        
        
                <table width=100% class=grid>
        <tr><td colspan=2><h3>$phrases[playlist]</h3></td></tr>
       
           
        <tr><td><b>$phrases[the_content]</b></td><td>
            <textarea cols=50 rows=15 name='playlist_content' dir=ltr>".htmlspecialchars($data['playlist_content'])."</textarea></td></tr>
          
         
            </table><br>
            
          
          <table width=100% class=grid>
        <tr><td colspan=2><h3>$phrases[the_videos]</h3></td></tr>
       
           
        <tr><td><b>$phrases[the_content]</b></td><td>
            <textarea cols=50 rows=15 name='video_content' dir=ltr>".htmlspecialchars($data['video_content'])."</textarea></td></tr>
          
         
            </table><br>
            </fieldset>
            <br>
            
              
           <table width=70% class=grid>
        <tr><td colspan=2><h3>$phrases[external_player]</h3></td></tr>
       
            <tr><td><b>MIME</b></td><td><input type=text name='ext_mime' value=\"$data[ext_mime]\" size=30 dir=ltr></td></tr>   
       <tr><td><b>$phrases[file_name]</b></td><td><input type=text name='ext_filename' dir=ltr value=\"$data[ext_filename]\" size=30></td></tr> 
            <tr><td><b>$phrases[the_content]</b></td><td>
            <textarea cols=50 rows=15 name='ext_content' dir=ltr>".htmlspecialchars($data['ext_content'])."</textarea></td></tr>
            
           
            </table>
            <br>
            
              
            
               <table width=70% class=grid>            
                <tr><td colspan=2 align=center><input type=submit value='$phrases[edit]'></td></tr>     
                 </table>    
            </form>
            </center>";
    }else{
    print_admin_table("<center>$phrases[err_wrong_url]</center>");
    }
}



//----------- players edit ------------------
if($action=="players_add"){
    
    if_admin("players");     

        print "<img src='images/arrw.gif'>&nbsp; <a href='index.php?action=players'>$phrases[the_players]</a> / $phrases[players_add] <br><br>"; 
        print "<center>
        <form action='index.php' method=post>
        <input type=hidden name='action' value='players_add_ok'>
       
        
        <table width=70% class=grid>
        <tr><td><b>$phrases[the_name]</b></td><td><input type=text name='name'></td>
        <td align=center><input type=submit value='$phrases[add]'></td></tr>
            </table></form>
            </center>";
   
}
