<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//---------------------------------------------------------

$id= (int) $id;
$cat = iif($cat,intval($cat),1); 

$qr=db_query("select * from songs_songs where id='$id'");


if(db_num($qr)){
    
if(song_listen_permission($id,$cat)){  
   
    
$data = db_fetch($qr);

$url = $data['url_'.$cat];

if($url){
db_query("update songs_songs set listens_".$cat."=listens_".$cat."+1 where id='$id'");


$data_singer = db_qr_fetch("select * from songs_singers where id='$data[album]'");
                                                                                    
if($data['video_id']){
    $data_video = db_qr_fetch("select * from songs_videos_data where id='$data[video_id]'");
}else{
    $data_video = array();
}


    
   
  /*   $data_singer['songs_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$data_singer[id]'"),"count");
        $data_singer['albums_count'] = valueof(db_qr_fetch("select count(*) as count from songs_albums where cat='$data_singer[id]'"),"count");    
         $data_singer['photos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_singers_photos where cat='$data_singer[id]'"),"count");    
        // $data_singer['videos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_videos_tags where singer_id='$data_singer[id]'"),"count");
         $data_singer['videos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$data_singer[id]' and video_id > 0"),"count"); 
         */
         
         $data_cat = db_qr_fetch("select * from songs_cats where id='$data_singer[cat]'");

            print "<span class='path'> <img src='images/arrw.gif' border=0> <a class=path_link href=\"".cat_url($data_cat['id'],$data_cat['page_name'],$data_cat['name'])."\" title=\"$data_cat[name]\">$data_cat[name]</a> $phrases[path_sep] <a class=path_link href=\"".singer_url($data_singer['id'],$data_singer['page_name'],$data_singer['name'])."\" title=\"$data_singer[name]\">$data_singer[name]</a>  ";

      
  if($data['album_id']){
            
   $data_album = db_qr_fetch("select * from songs_albums where id='$data[album_id]'");
   $data_album['songs_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$data_singer[id]' and album_id='$data[album_id]'"),"count");
    
   
  print " $phrases[path_sep] <a class=path_link href=\"".album_url($data_album['id'],$data_album['page_name'],$data_album['name'],$data_singer['id'],$data_singer['page_name'],$data_singer['name'])."\">$data_album[name]</a>";
    
    }else{
        if($data_albums_count['count']){
   print " $phrases[path_sep] $phrases[another_songs]" ;
        } 
   }
     
   print " $phrases[path_sep] $data[name] ";
       
   print" <br><br></span>" ;

  
compile_hook('songs_after_path_links');
run_template('singer_info_table');
compile_hook('songs_after_singer_table');



     run_template('song_listen');
  
if($settings['prev_next_song']){
prev_next_song($id,$data_singer['id']);
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