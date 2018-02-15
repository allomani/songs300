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

//---------------------- URLs Fields ---------------------
if($action=="urls_fields" || $action=="urls_fields_edit_ok" || $action=="urls_fields_add_ok" || 
$action=="urls_fields_del" || $action=="urls_fields_disable" || $action=="urls_fields_enable" || $action=="urls_fields_set_default"){

if_admin("urls_fields");


//-----  set default style ----
 if($action=="urls_fields_set_default"){
     db_query("update songs_settings set value='$id' where name like 'default_url_id'");
     load_settings(); 
 }
 
 
//------- enable / disale -----------//
if($action=="urls_fields_disable"){
        db_query("update songs_urls_fields set active=0 where id='$id'");
        }

if($action=="urls_fields_enable"){

       db_query("update songs_urls_fields set active=1 where id='$id'");
        }
// -------- Del ------------//
if($action=="urls_fields_del"){
$id = (int) $id;

if($id !=1){
db_query("delete from songs_urls_fields where id='$id'");
db_query("ALTER TABLE  `songs_songs` DROP  `url_".$id."`"); 
db_query("ALTER TABLE  `songs_songs` DROP  `listens_".$id."`"); 
db_query("ALTER TABLE  `songs_songs` DROP  `downloads_".$id."`"); 

}
}

//------------- edit ---------//
if($action=="urls_fields_edit_ok"){
$id=intval($id);
if($name){
db_query("update songs_urls_fields set name='$name',ord='".intval($ord)."',show_listen='".intval($show_listen)."',show_ext_listen='".intval($show_ext_listen)."',show_download='".intval($show_download)."',
download_icon='".db_escape($download_icon)."',listen_icon='".db_escape($listen_icon)."',ext_listen_icon='".db_escape($ext_listen_icon)."',
download_alt='".db_escape($download_alt)."',listen_alt='".db_escape($listen_alt)."',ext_listen_alt='".db_escape($ext_listen_alt)."',download_for_members='".intval($download_for_members)."',listen_for_members='".intval($listen_for_members)."',listen_type='".intval($listen_type)."'
 where id='$id'");

}
}
//-------- add -----------//
if($action=="urls_fields_add_ok"){
if($name){
$data = db_qr_fetch("select * from songs_urls_fields where id='1'");
$ord_dt = db_qr_fetch("select max(ord) as max from  songs_urls_fields limit 1");
$ord = intval($ord_dt['max'])+1;

db_query("insert into songs_urls_fields(name,ord,show_listen,show_ext_listen,show_download,download_icon,listen_icon,ext_listen_icon,download_alt,listen_alt,ext_listen_alt,download_for_members,listen_for_members,listen_type) 
values(
'".db_escape($name)."',
'$ord',
'$data[show_listen]',
'$data[show_ext_listen]',
'$data[show_download]',
'".db_escape($data['download_icon'])."',
'".db_escape($data['listen_icon'])."',
'".db_escape($data['ext_listen_icon'])."',
'".db_escape($data['download_alt'])."',
'".db_escape($data['listen_alt'])."',
'".db_escape($data['ext_listen_alt'])."',
'".intval($data['download_for_members'])."',
'".intval($data['listen_for_members'])."',
'".intval($data['listen_type'])."'
)");

$field_id = mysql_insert_id();
db_query("ALTER TABLE  songs_songs ADD `url_".$field_id."` VARCHAR( 255 ) NOT NULL");
db_query("ALTER TABLE  songs_songs ADD `listens_".$field_id."` INT( 10 ) NOT NULL, ADD INDEX (`listens_".$field_id."`)");
db_query("ALTER TABLE  songs_songs ADD `downloads_".$field_id."` INT( 10 ) NOT NULL , ADD INDEX (`downloads_".$field_id."`)");
}
}
//-------------------------//

print "<p align=center class=title> $phrases[urls_fields] </p>

<p align=$global_align><a href='index.php?action=urls_fields_add'><img src='images/add.gif' border=0> $phrases[urls_fields_add] </a></p>

<center><table width=90% class=grid>
<tr><td>
<div id=\"urls_fields_list\">";

$qr= db_query("select * from songs_urls_fields order by ord");
if(db_num($qr)){

while($data=db_fetch($qr)){
print "<div id=\"item_$data[id]\" onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='#FFFFFF'\">
<table width=100%><tr>
<td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img src='images/move.gif'></span> 
      </td>
<td>$data[name]</td>
<td width=300> ".iif($data['id']==$settings['default_url_id'],"[$phrases[default]]","<a href='index.php?action=urls_fields_set_default&id=$data[id]'>$phrases[set_default]</a>")." - ";


          if($data['active']){
                        print "<a href='index.php?action=urls_fields_disable&id=$data[id]'>$phrases[disable]</a> - " ;
                        }else{
                        print "<a href='index.php?action=urls_fields_enable&id=$data[id]'>$phrases[enable]</a> - " ;
                        }
                        
print " <a href='index.php?action=urls_fields_edit&id=$data[id]'>$phrases[edit]</a> ".iif($data['id']!=1,"- <a href='index.php?action=urls_fields_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>")."</td></tr>
</table></div>";
}

}else{
print "<center>  $phrases[no_data] <center>";
    }

print "</div></td></tr></table></center>";

print "<script type=\"text/javascript\">
        init_urls_fields_sortlist();
</script>";
}

//---------- Add File Field -------------
if($action=="urls_fields_add"){
 if_admin("urls_fields");

print "<center>
<p align=center class=title>$phrases[files_field_add]</p>
<form action=index.php method=post>
<input type=hidden name=action value='urls_fields_add_ok'>
<table width=80% class=grid>";
print "<tr>
<td><b>$phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>";
print "</table></center>";

}


//---------- Edit File Field -------------
if($action=="urls_fields_edit"){

    if_admin("urls_fields");
$id=intval($id);

$qr = db_query("select * from songs_urls_fields where id='$id'");

if(db_num($qr)){
$data = db_fetch($qr);
print "<center><form action=index.php method=post>
<input type=hidden name=action value='urls_fields_edit_ok'>
<input type=hidden name=id value='$id'>
<table width=82% class=grid>";
print "
<tr><td><b>$phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>


<tr><td><b>$phrases[the_order]</b> </td><td><input type=text size=3  name=ord value=\"$data[ord]\"></td></tr>
</table>
<br>

<fieldset style='width: 80%'>
<legend><b>$phrases[download]</b></legend>
<table width=100%>
<tr><td><b>$phrases[show_download_icon]</b></td><td>";
print_select_row('show_download',array($phrases['no'],$phrases['yes']),$data['show_download']);
print "</td></tr>
<tr><td><b>$phrases[the_download_icon]</b> </td><td><input type=text size=30  name='download_icon' value=\"$data[download_icon]\" dir=ltr></td></tr>  
<tr><td><b>$phrases[download_icon_alt]</b> </td><td><input type=text size=30  name='download_alt' value=\"$data[download_alt]\"></td></tr>  

<tr><td><b>$phrases[download_permission]</b></td><td>";
print_select_row("download_for_members",array("0"=>$phrases['as_every_cat_settings'],"1"=>$phrases['for_members_only'],"2"=>$phrases['for_all_visitors']),$data['download_for_members']);
print "</td></tr>
</table>
</fieldset>

<br>
<fieldset style='width: 80%'>
<legend><b>$phrases[listen]</b></legend>
<table width=100%>
<tr><td>$phrases[show_listen_icon]</td><td>";
print_select_row('show_listen',array($phrases['no'],$phrases['yes']),$data['show_listen']);
print "</td></tr>
<tr><td>$phrases[the_listen_icon] </td><td><input type=text size=30  name='listen_icon' value=\"$data[listen_icon]\" dir=ltr></td></tr>
<tr><td>$phrases[listen_icon_alt] </td><td><input type=text size=30  name='listen_alt' value=\"$data[listen_alt]\"></td></tr>

<tr><td>$phrases[listen_permission]</td><td>";
print_select_row("listen_for_members",array("0"=>$phrases['as_every_cat_settings'],"1"=>$phrases['for_members_only'],"2"=>$phrases['for_all_visitors']),$data['listen_for_members']);
print "</td></tr>

<tr><td>$phrases[listen_type]</td><td>";
print_select_row("listen_type",array("0"=>$phrases['in_site_page'],"1"=>"Pop-Up"),$data['listen_type']);
print "</td></tr>

</table>
</fieldset>


<br>
<fieldset style='width: 80%'>
<legend><b>$phrases[ext_listen]</b></legend>
<table width=100%>
<tr><td>$phrases[show_listen_icon]</td><td>";
print_select_row('show_ext_listen',array($phrases['no'],$phrases['yes']),$data['show_ext_listen']);
print "</td></tr>
<tr><td>$phrases[the_listen_icon]</td><td><input type=text size=30  name='ext_listen_icon' value=\"$data[ext_listen_icon]\" dir=ltr></td></tr>
<tr><td>$phrases[listen_icon_alt] </td><td><input type=text size=30  name='ext_listen_alt' value=\"$data[ext_listen_alt]\"></td></tr>

</table>
</fieldset>


<br>
<table width=82% class=grid>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>";
print "</table></center>";
}else{
print "<center><table width=70% class=grid>";
print "<tr><td align=center>$phrases[err_wrong_url]</td></tr>";
print "</table></center>";
}

}
?>
