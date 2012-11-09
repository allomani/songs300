<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//---------------------------------------------------------

$id= (int) $id;
$cat = iif($cat,intval($cat),1); 

$qr=db_query("select * from songs_videos_data where id='$id'");


if(db_num($qr)){
    
if(video_download_permission($id)){  
   
    
$data = db_fetch($qr);

print_videos_path_links($data['cat'],$data['name']);

if($data['url']){
db_query("update songs_videos_data set views=views+1 where id='$id'");

$data_song = db_qr_fetch("select * from songs_songs where video_id='$id'");

run_template('video_watch');


if($settings['prev_next_video']){
prev_next_video($id,$data['cat']);
}


}else{
open_table();
print "<center> $phrases[file_is_not_available]</center>";
close_table();      
}
}else{
login_redirect();
}
}else{
open_table();
print "<center> $phrases[err_wrong_url]</center>";
close_table();    
}

//---------------------------------------------
require(CWD . "/includes/framework_end.php"); 