<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//---------------------------------------------------------
//open_table($phrases['singer_videos']);
templates_cache(array('singer_overview','singer_info_table','browse_photos','prev_next_singer','browse_videos_header','browse_videos','browse_videos_footer'));
    
// $qra = db_query("select songs_videos_data.* from songs_videos_data,songs_videos_tags where songs_videos_data.id=songs_videos_tags.video_id and songs_videos_tags.singer_id='$id' order by songs_videos_data.id desc");
  
 $qr=db_query("select * from songs_singers where id='$id' and active=1");   
     
    if(db_num($qr)){
         unset($data_singer);
       
         
         $data = db_fetch($qr);

     db_query("update songs_singers set views=views+1 where id='$id'");     
         $data_singer = $data;
     //    $data_singer['songs_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$id'"),"count");
      //    $data_singer['albums_count'] = valueof(db_qr_fetch("select count(*) as count from songs_albums where cat='$id'"),"count");    
      //   $data_singer['photos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_singers_photos where cat='$id'"),"count");    
      //   $data_singer['videos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_videos_tags where singer_id='$id'"),"count");
   //   $data_singer['videos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$id' and video_id > 0"),"count");
         
     //     $data4 = db_qr_fetch("select date from songs_songs where album='$id' order by date DESC limit 1");
         $data_cat = db_qr_fetch("select * from songs_cats where id='$data[cat]'");

   print "<span class='path'> <img src='images/arrw.gif' border=0> <a class=path_link href=\"".cat_url($data_cat['id'],$data_cat['page_name'],$data_cat['name'])."\" title=\"$data_cat[name]\">$data_cat[name]</a> $phrases[path_sep] <a class=path_link href=\"".singer_url($data['id'],$data['page_name'],$data['name'])."\" title=\"$data[name]\">$data[name]</a>  ";

   print"<br><br> </span>" ;

 
 compile_hook('songs_after_path_links');
   run_template('singer_info_table');  
compile_hook('songs_after_singer_table');
//unset($data_singer);


$qr_photos = db_query("select * from songs_singers_photos where cat='$id' order by ord asc limit $settings[overview_photos_limit]");  
$qr_videos = db_query("select songs_videos_data.* from songs_videos_data,songs_songs where songs_videos_data.id = songs_songs.video_id and songs_songs.album='$id' limit $settings[overview_videos_limit]"); 


$no_singer_name = true;
run_template('singer_overview');


if($settings['prev_next_singer']){
prev_next_singer($id,$data_singer['cat']);
}


    }else{
        open_table();
        print " <center> $phrases[err_wrong_url] </center>";
        close_table();
    }
//---------------------------------------------
require(CWD . "/includes/framework_end.php"); 
?>