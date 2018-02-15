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

require("global.php");

require(CWD . "/includes/framework_start.php");   
//----------------------------------------------------
?>
<script>
<?
unset($songs_arr);
$url_id = $settings['default_url_id'];
if($action=="random"){
$num = intval($num);
$cat = intval($cat) ;

if(!$num){$num=10;}

if($cat){
$qr = db_query("select songs_songs.url_{$url_id},songs_songs.name,songs_singers.name as singer_name from songs_songs,songs_singers where songs_songs.album=songs_singers.id and songs_singers.cat='$cat' order by rand() limit $num");
}else{
$qr = db_query("select songs_songs.url_{$url_id},songs_songs.name,songs_singers.name as singer_name from songs_songs,songs_singers where songs_singers.id = songs_songs.album order by rand() limit $num");
}

    while($data=db_fetch($qr)){
        $songs_arr[] = array("name"=>$data['singer_name']." - ".$data['name'],"url"=>$data["url_{$url_id}"]);
    }
    
    
}else{
    
$song_id = (array) $song_id;
$count_songs = count($song_id);
if($count_songs){

    $song_id = array_map("intval",$song_id);
    $orders = array_flip($song_id);
      
    $qr = db_query("select songs_songs.id,songs_songs.name,songs_songs.url_{$url_id},songs_singers.name as singer_name from songs_songs,songs_singers where songs_singers.id = songs_songs.album and songs_songs.id IN (".implode(",",$song_id).")");
  while($data=db_fetch($qr)){
        $songs_arr[$orders[$data['id']]] = array("name"=>$data['singer_name']." - ".$data['name'],"url"=>$data["url_{$url_id}"],"id"=>$data['id']);
    }
  ksort($songs_arr);

}
}
//-------------------------
$songs_arr = (array) $songs_arr;
$count_arr_songs = count($songs_arr);
$c=0;
foreach($songs_arr as $song){
    $arr_str .= "[\"$song[name]\",\"$song[url]\"]";
    if($c < ($count_arr_songs-1)){
    $arr_str .= ",";    
}
$c++;
}

print  "var songs_list=[".$arr_str."];
var max_index = $count_arr_songs";
?>


</script>

<?

open_table($phrases['playlist']);
if($count_arr_songs){
print "
<table width=100%><tr><td width=20>
<div id='loading_div' style=\"display:none;\"><img src='images/loading.gif'></div>
</td><td>
</td></tr></table>


<table width=100%><tr><td width=50%>

<fieldset>
";
$i=0;
foreach($songs_arr as $song){
    print "<div id='song_{$i}' class='pl_song_stop' onClick=\"play_song('".$i."');\"> <b>".($i+1).".</b> $song[name]</div>";
    $i++;
}


  

print "
</fieldset>
</td><td>

 
<div id='player_div'></div> ";

print "</td></tr></table>


<script>
play_song(0);
</script>";

  
}else{
    print "<center>$phrases[err_no_songs]</center>";
}
  
close_table();


//---------------------------------------------------
require(CWD . "/includes/framework_end.php");    
