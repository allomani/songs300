<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//---------------------------------------------------------
//open_table($phrases['singer_videos']);
 templates_cache(array('singer_info_table','browse_videos_header','browse_videos','browse_videos_footer'));   
// $qra = db_query("select songs_videos_data.* from songs_videos_data,songs_videos_tags where songs_videos_data.id=songs_videos_tags.video_id and songs_videos_tags.singer_id='$id' order by songs_videos_data.id desc");
  
   $qr=db_query("select * from songs_singers where id='$id'");   
     
   //  if(db_num($qr)){
         unset($data_singer);
       
         
         $data = db_fetch($qr);
          db_query("update songs_singers set views=views+1 where id='$id'");  
      
         $data_singer = $data;

         $data_cat = db_qr_fetch("select * from songs_cats where id='$data[cat]'");

   print "<span class='path'> <img src='images/arrw.gif' border=0> <a class=path_link href=\"".cat_url($data_cat['id'],$data_cat['page_name'],$data_cat['name'])."\" title=\"$data_cat[name]\">$data_cat[name]</a> $phrases[path_sep] <a class=path_link href=\"".singer_url($data['id'],$data['page_name'],$data['name'])."\" title=\"$data[name]\">$data[name]</a> $phrases[path_sep] $phrases[singer_videos]";

   print"<br><br> </span>" ;

 
 compile_hook('songs_after_path_links');
   run_template('singer_info_table');  
compile_hook('songs_after_singer_table');
//unset($data_singer);


//----------------------
   $start = intval($start);
   $perpage = $settings['videos_perpage'];
  $page_string = str_replace(array("{id}","{name}"),array($data_singer['id'],singer_url_name($data_singer['page_name'],$data_singer['name'])),$links['singer_videos_w_pages']);
   //---------------------
   
   
  $qr = db_query("select songs_videos_data.* from songs_videos_data,songs_songs where songs_videos_data.id = songs_songs.video_id and songs_songs.album='$id'  limit $start,$perpage ");
if(db_num($qr)){
    
$page_result = db_qr_fetch("select count(*) as count from songs_videos_data,songs_songs where songs_videos_data.id = songs_songs.video_id and songs_songs.album='$id' ");
open_table($phrases['singer_videos']);  
    run_template('browse_videos_header');

    $c=0;
while($data = db_fetch($qr)){



if ($c==$settings['songs_cells']) {
run_template("browse_videos_sep");
$c = 0 ;
}


    run_template('browse_videos');
$c++;

           }
run_template('browse_videos_footer');
   close_table();
   
   //-------------------- pages system ------------------------
print_pages_links($start,$page_result['count'],$perpage,$page_string); 
//-----------------------------


}else{
    open_table();
    print "<center> $phrases[err_no_videos] </center>";  
    close_table();  
}
        
//close_table();

//---------------------------------------------
require(CWD . "/includes/framework_end.php"); 
?>