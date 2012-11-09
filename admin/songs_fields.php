<?
 if(!defined('IS_ADMIN')){die('No Access');}  

 
//---------------------- songs Fields ---------------------
if($action=="songs_fields" || $action=="songs_fields_edit_ok" || $action=="songs_fields_add_ok" || $action=="songs_fields_del" || $action=="songs_fields_enable" || $action=="songs_fields_disable"){

 if_admin("songs_fields");
 
 
if($action=="songs_fields_enable"){
    db_query("update songs_songs_fields set active=1 where id='$id'");
}

if($action=="songs_fields_disable"){
    db_query("update songs_songs_fields set active=0 where id='$id'");
}

if($action=="songs_fields_del"){
$id=intval($id);
db_query("delete from songs_songs_fields where id='$id'");
db_query("ALTER TABLE  songs_songs  DROP  `field_".$id."`");
}

if($action=="songs_fields_edit_ok"){
$id=intval($id);
if($name){
    $value=trim($value);
db_query("update songs_songs_fields set name='".db_escape($name)."',details='".db_escape($details)."',`type`='".db_escape($type)."',value='".db_escape($value,false)."',style='$style_v',ord='".intval($ord)."',enable_search='".intval($enable_search)."' where id='$id'");
    }
}

if($action=="songs_fields_add_ok"){
$id=intval($id);
if($name){
    $value=trim($value);
db_query("insert into songs_songs_fields  (name,details,`type`,value,style,ord,enable_search) values('".db_escape($name)."','".db_escape($details)."','".db_escape($type)."','".db_escape($value,false)."','$style_v','$ord','".intval($enable_search)."')");

$field_id = mysql_insert_id();

db_query("ALTER TABLE  songs_songs ADD  `field_".$field_id."` VARCHAR( 255 ) NOT NULL , ADD INDEX (  `field_".$field_id."` )");  
}
}


print "<p align=center class=title> $phrases[songs_custom_fields]</p>

<p align=$global_align><a href='index.php?action=songs_fields_add'><img src='images/add.gif' border=0> $phrases[add_member_custom_field] </a></p>";




$qr= db_query("select * from songs_songs_fields order by ord asc");
if(db_num($qr)){
print "<center><table width=90% class=grid><tr><td>
<div id=\"songs_fields_list\">";

while($data=db_fetch($qr)){
    if($tr_class == "row_1"){
        $tr_class = "row_2";
    }else{
        $tr_class = "row_1";
    }
    
print "<div id=\"item_$data[id]\" class='$tr_class'\">
       
       <table width=100%>
<tr>
 <td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img title='$phrases[click_and_drag_to_change_order]' src='images/move.gif'></span> 
      </td>
      
      <td width=75%>";
if($data['required']){
    print "<b>$data[name]</b>";
    }else{
    print "$data[name]";
        }
        print "</td>
      
<td>".iif(!$data['active'],"<a href='index.php?action=songs_fields_enable&id=$data[id]'>$phrases[enable]</a>","<a href='index.php?action=songs_fields_disable&id=$data[id]'>$phrases[disable]</a>")."
 - <a href='index.php?action=songs_fields_edit&id=$data[id]'>$phrases[edit]</a> - <a href='index.php?action=songs_fields_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td></tr>
</table></div>";

}
print "</div>
</td></table></center>";

 print "<script type=\"text/javascript\">
        init_sortlist('songs_fields_list','set_songs_fields_sort');
</script>";



}else{
print_admin_table("<center>$phrases[no_data] </center>");
    }


}

//---------- Add Member Field -------------
if($action=="songs_fields_add"){
 if_admin("songs_fields");
 
print "<img src='images/arrw.gif'>&nbsp; <a href='index.php?action=songs_fields'>$phrases[songs_custom_fields]</a> / $phrases[add_custom_field]</a><br>";

print "<center>
<p align=center class=title>$phrases[add_custom_field]</p>
<form action=index.php method=post>
<input type=hidden name=action value='songs_fields_add_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "<tr><td><b> $phrases[the_name]</b> </td><td><input type=text size=20  name=name></td></tr>
<tr><td><b> $phrases[the_description] </b></b></td><td><input type=text size=30  name=details></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td>";
/*<select name=type>
<option value='text'>$phrases[textbox]</option>
<option value='textarea'>$phrases[textarea]</option>
<option value='select'>$phrases[select_menu]</option>
<option value='radio'>$phrases[radio_button]</option>
<option value='checkbox'>$phrases[checkbox]</option>
</select> */
$types_arr = array("text"=>$phrases['textbox'],"textarea"=>$phrases['textarea'],"select"=>$phrases['select_menu'],"radio"=>$phrases['radio_button'],"checkbox"=>$phrases['checkbox']);
print_select_row("type",$types_arr);
print "
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[addition_style]</b> </td><td><input type=text size=30  name='style_v' value=\"$data[style]\" dir=ltr></td></tr>


<tr><td><b>$phrases[enable_search]</b></td><td>";
print_select_row("enable_search",array("1"=>$phrases['yes'],"0"=>$phrases['no']),"1");
print "</td></tr>                                                                         

<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>";
print "</table></center>";

}


//---------- Edit Member Field -------------
if($action=="songs_fields_edit"){

    if_admin("songs_fields");
$id=intval($id);

$qr = db_query("select * from songs_songs_fields where id='$id'");

if(db_num($qr)){
$data = db_fetch($qr);

print "<img src='images/arrw.gif'>&nbsp; <a href='index.php?action=songs_fields'>$phrases[songs_custom_fields]</a> / $data[name]</a><br><br>";



print "<center><form action=index.php method=post>
<input type=hidden name=action value='songs_fields_edit_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "<tr><td><b> $phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>
<tr><td><b> $phrases[the_description] </b></b></td><td><input type=text size=30  name=details value=\"$data[details]\"></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td>";
$types_arr = array("text"=>$phrases['textbox'],"textarea"=>$phrases['textarea'],"select"=>$phrases['select_menu'],"radio"=>$phrases['radio_button'],"checkbox"=>$phrases['checkbox']);
print_select_row("type",$types_arr,$data['type']);
/*
<select name=type>";


print "<option value='text'".iif($data['type']=="text", "selected").">$phrases[textbox]</option>
<option value='textarea'".iif($data['type']=="textarea"," selected").">$phrases[textarea]</option>
<option value='select'".iif($data['type']=="select"," selected").">$phrases[select_menu]</option>
<option value='radio'".iif($data['type']=="radio"," selected").">$phrases[radio_button]</option>
<option value='checkbox'".iif($data['type']=="checkbox"," selected").">$phrases[checkbox]</option>
</select> */

print "
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[addition_style]</b> </td><td><input type=text size=30  name='style_v' value=\"$data[style]\" dir=ltr></td></tr>


<tr><td><b>$phrases[enable_search]</b></td><td>";
print_select_row("enable_search",array("1"=>$phrases['yes'],"0"=>$phrases['no']),$data['enable_search']);
print "</td></tr>   
                                                                                                       

<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>";
print "</table></center>";
}else{
print "<center><table width=70% class=grid>";
print "<tr><td align=center>$phrases[err_wrong_url]</td></tr>";
print "</table></center>";
}

}
?>