<?
include "global.php" ;

if($action !="video" && $action !="song"){$action="song";}  


run_template("page_head");

if($action && $id){ 
    
if($action == "video"){
open_table("$phrases[vote_video]");
$data = db_qr_fetch("select * from songs_videos_data where id='$id'");

}else{
open_table("$phrases[vote_song]");
$data = db_qr_fetch("select * from songs_songs where id='$id'");

 }
 



print "<center>";
print_rating($action,$data['id'],$data['rate']);    
print "</center>";

close_table();
}else{
 open_table();
 print "<center>$phrases[err_wrong_url]</center>";
 close_table();      
}
