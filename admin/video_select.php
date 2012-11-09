<?
chdir('./../');
define('CWD', (($getcwd = getcwd()) ? $getcwd : '.'));


require(CWD . "/global.php") ;
require(CWD . "/includes/functions_admin.php") ; 

echo "<html dir=$global_dir>\n";
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";
?>
<? print "<title>$phrases[select_video]</title>\n";?>
<link href="images/style.css" type=text/css rel=stylesheet>
<script src='javascript.js' type="text/javascript" language="javascript"></script>
<script>
function do_video_select(id,field_id){
opener.document.getElementById(field_id).value=id;
opener.document.getElementById(field_id).focus();
opener.document.getElementById(field_id).blur();
window.close();
}
</script>
<br>
<?
if (check_admin_login()) {
  
    $cat = (int) $cat;
    $field_id = htmlspecialchars($field_id);
    
  //----- cats -----  
  $qr = db_query("select * from songs_videos_cats where cat='$cat' order by ord");
  if(db_num($qr)){
  print "<table width=100% class=grid>";
  while($data=db_fetch($qr)){
  print "<tr><td><a href=\"video_select.php?cat=$data[id]&field_id=$field_id\">$data[name]</a></td></tr>";    
  }
  print "</table>";
  }else{
      $no_cats = true;
  }
  
  
  //----- videos -----
  $qr = db_query("select * from songs_videos_data where cat='$cat' order by $settings[videos_orderby] $settings[videos_sort]");
  if(db_num($qr)){
  print "<table width=100% class=grid>";
  while($data=db_fetch($qr)){
  print "<tr><td><img src=\"images/video_select.gif\">&nbsp;<a href=\"javascript:;\" onClick=\"do_video_select($data[id],'".$field_id."');\">$data[name]</a></td></tr>";    
  }
  }else{
  if($no_cats){print_admin_table("<center> $phrases[err_no_videos]</center>");}
  }
  
    
    
    }else{
print_admin_table("<center>$phrases[please_login_first]</center>");
     }



     print "</html>";
     ?>