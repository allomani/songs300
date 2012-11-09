<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//---------------------------------------------------------
$age_from = (int) $age_from;
$age_to = (int) $age_to;
$username = htmlspecialchars($username);
$country = htmlspecialchars($country);
$gender = htmlspecialchars($gender);



open_table($phrases['the_members']);
print "
<form action='members.php' method=post>
<input type='hidden' name='action' value='search'>
<table width=100%>
<tr>
<td><b>$phrases[username] : </b></td><td><input type='text' name='username' value=\"".$username."\"></td>

<td><b>$phrases[country] : </b></td><td><select name=country><option value=''>$phrases[all]</option>";
            $c_qr = db_query("select * from songs_countries order by name asc");
   while($c_data = db_fetch($c_qr)){
        print "<option value='$c_data[name]'".iif($country == $c_data['name']," selected").">$c_data[name]</option>";
           }
           print "</select></td>

</tr>

<tr>
<td><b>$phrases[gender] : </b></td><td>";
print_select_row("gender",array(""=>"$phrases[all]","male"=>"$phrases[male]","female"=>"$phrases[female]"),$gender);

$age_arry = array(""=>"--");
for($i=12;$i<70;$i++){
$age_arry[$i] = $i;
}

print "</td>

<td><b>$phrases[the_age] : </b></td><td> $phrases[from] : ";
print_select_row("age_from",$age_arry,$age_from);
print "&nbsp; $phrases[to] : ";
print_select_row("age_to",$age_arry,$age_to);
print "</td>
</tr>

<tr><td colspan=4 align=center><input type='submit' value='$phrases[search]'></td></tr>
</table>
</form>";

close_table();

      //iif($action=="search","نتائج البحث","من الأعضاء")
open_table();

$page_string= "members.php?action=search&username=$username&country=$country&gender=$gender&age_from=$age_from&age_to=$age_to&start={start}";
    $perpage = iif($settings['members_perpage'],$settings['members_perpage'],20);
    $start = (int) $start;
    
    
if($action=="search"){
    
    //iif($age_from," and left(birth,4) <= ".(date("Y")-$age_from)).iif($age_to," and left(birth,4) >= ".(date("Y")-$age_to)).iif($age_from || $age_to,"  and left(birth,4) not like '0000'
    
    $where_sql = members_fields_replace("username")." like '%".db_escape($username)."%'  ".iif($country," and ".members_fields_replace("country")." like '".db_escape($country)."' ").iif($gender," and gender like '".db_escape($gender)."'").iif($age_from," and UNIX_TIMESTAMP(".members_fields_replace("birth").") <= ".mktime(0,0,0,0,0,date("Y")-$age_from)).iif($age_to," and UNIX_TIMESTAMP(".members_fields_replace("birth").") >= ".mktime(0,0,0,0,0,date("Y")-$age_to))." and members_list=1";

    $qr = db_query("select * from ".members_table_replace("songs_members")." where $where_sql order by ".members_fields_replace("last_login")." desc limit $start,$perpage",MEMBER_SQL);    

  $page_result = db_qr_fetch("select count(*) as count from ".members_table_replace("songs_members")." where $where_sql",MEMBER_SQL);
  
}else{

$qr = db_query("select * from ".members_table_replace("songs_members")." where members_list=1 order by rand() limit $perpage",MEMBER_SQL);

}

$num = db_num($qr);

if($num){
/*
if($action=="search"){
    print "<b>عدد النتائج : </b> $page_result[count] <br><br>";
}     */

print "<table width=100%><tr>";
$c = 0;
while($data = db_fetch($qr)){

$birth_array = connector_get_date($data[members_fields_replace('birth')],"member_birth_array");
$birth = $birth_array['day']."-".$birth_array['month']."-".$birth_array['year'];           
$age = date("Y")-date("Y",strtotime($birth));

if($c >= $settings['songs_cells']){
    print "</tr><tr>";
    $c=0;
}


print "<td valign=top>";
//<td valign=top width='".($settings['profile_pic_thumb_width']+30)."' height='".($settings['profile_pic_thumb_height']+10)."'><a href=\"".str_replace("{id}",$data['id'],$links['links_profile'])."\" title=\"$data[username]\"><img src=\"".get_image($data['thumb'],$style['images']."/profile_no_pic_thumb".iif($data['gender'],"_".$data['gender']).".gif")."\" title=\"$data[username]\" border=0 width='".($settings['profile_pic_thumb_width'])."' height='".($settings['profile_pic_thumb_height'])."'></a></td>

print "<table><tr><td valign=top width='".($settings['profile_pic_thumb_width']+30)."' height='".($settings['profile_pic_thumb_height']+10)."'><a href=\"".str_replace("{id}",$data['id'],$links['profile'])."\" title=\"".$data[members_fields_replace("username")]."\"><img src=\"".get_image($data['img'],$style['images']."/profile_no_pic".iif($data['gender'],"_".$data['gender']).".gif")."\" title=\"$data[username]\" border=0 width='".($settings['profile_pic_thumb_width'])."' height='".($settings['profile_pic_thumb_height'])."'></a></td>

    <td valign=top><a href=\"".str_replace("{id}",$data['id'],$links['profile'])."\" title=\"".$data[members_fields_replace("username")]."\"><b>".$data[members_fields_replace("username")]."</b></a>  
    
    ".iif($age,"<br>$age $phrases[year]").iif($data['country'],"<br> $data[country]").iif($data['gender'],"<br>".$phrases[$data['gender']])." </td></tr></table>";
    
 print "</td>";
    
    $c++;
}
print "</tr></table>";

if($action=="search"){
    print_pages_links($start,$page_result['count'],$perpage,$page_string);
    
    }
    
}else{
    print "<center> $phrases[no_results] </center>";
}
close_table();


//---------------------------------------------
require(CWD . "/includes/framework_end.php"); 

?>