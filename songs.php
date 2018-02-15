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

 compile_hook('songs_start');
 
 $id=intval($id);
 
 if(!$op){
       //     print_r($_GET);
     if(!isset($album_id) || $album_id=="all"){$album_id = "all";}else{$album_id = (int) $album_id;}
     
     $qr=db_query("select * from songs_singers where id='$id'");   
     
     if(db_num($qr)){
         unset($data_singer);
        
         
         $data = db_fetch($qr);
         db_query("update songs_singers set views=views+1 where id='$id'");    
         $data_singer = $data;
      //   $data_singer['songs_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$id'"),"count");
        // $data_singer['albums_count'] = valueof(db_qr_fetch("select count(*) as count from songs_albums where cat='$id'"),"count");    
        // $data_singer['photos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_singers_photos where cat='$id'"),"count");
             
      //   $data_singer['videos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_videos_tags where singer_id='$id'"),"count");
    //  $data_singer['videos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$id' and video_id > 0"),"count");
         
     //     $data4 = db_qr_fetch("select date from songs_songs where album='$id' order by date DESC limit 1");
         $data_cat = db_qr_fetch("select * from songs_cats where id='$data[cat]'");

   print "<span class='path'> <img src='images/arrw.gif' border=0> <a class=path_link href=\"".cat_url($data_cat['id'],$data_cat['page_name'],$data_cat['name'])."\" title=\"$data_cat[name]\">$data_cat[name]</a> $phrases[path_sep] <a class=path_link href=\"".singer_url($data['id'],$data['page_name'],$data['name'])."\" title=\"$data[name]\">$data[name]</a> ";

   if((string)$album_id != "all"){
     
  if($album_id == 0){
   
     print " $phrases[path_sep] $phrases[another_songs]" ;
    }else{
               
   $data_album = db_qr_fetch("select * from songs_albums where id='$album_id'");
   db_query("update songs_albums set views=views+1 where id='$album_id'");
   $data_album['songs_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$id' and album_id='$album_id'"),"count");
   
   print " $phrases[path_sep] $data_album[name]";
   }
           }
   print"<br><br> </span>" ;

  
   compile_hook('songs_after_path_links');
   run_template('singer_info_table');  
compile_hook('songs_after_singer_table');
//unset($data_singer);

}}

//----------------------- END SINGER TABLE ---------------------------------

                     //   print $album_id;     
  if(($data_singer['albums_count'] && (string)$album_id=="all" && !$songs_only) || $albums_only){
                 //   print "here";
      compile_hook('songs_before_albums_table');  
    $qr_album=db_query("select * from songs_albums where cat='$id' order by ".iif($settings['albums_orderby']=="year","year $settings[albums_sort] , id $settings[albums_sort]","$settings[albums_orderby] $settings[albums_sort]"));

  open_table();
  print "<table width=100%><tr>";
$c=0 ;

  while($data_album = db_fetch($qr_album))
  {



if ($c==$settings['songs_cells']) {
print "  </tr><tr>" ;
$c = 0 ;
}
 ++$c ;

 // $album_songs_count = db_qr_fetch("select count(id) as count from songs_songs where album_id='$data[id]' and album='$id'");
 // $album_songs_num = $album_songs_count['count'];
  
 $data_album['singer_id'] = $data['id'];
 $data_album['singer_name'] = $data['name'];
 $data_album['singer_page_name'] = $data['page_name'];
   
//  $albums_template = str_replace(array('{singer_id}','{album_id}','{name}','{img}','{songs_count}'),array("$id","$data[id]","$data[name]","$img_url","$album_songs_count[count]"),get_template('browse_albums'));


          print "<td>";
          run_template('browse_albums'); 
           print "</td>";

          }
      
$album_songs_num = valueof(db_qr_fetch("select count(id) as count from songs_songs where album_id=0 and album='$id'"),"count");

if($album_songs_num){

if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
}
  ++$c ;

  $data_album['img'] = 'images/others_songs.gif' ;
  $data_album['id'] = '0' ;
  $data_album['name'] =  $phrases['another_songs'] ;
  $data_album['page_name'] =  "others" ;
  
  $data_album['singer_id'] = $data['id'];
 $data_album['singer_name'] = $data['name'];
 $data_album['singer_page_name'] = $data['page_name'];
 
 // $albums_template = str_replace(array('{singer_id}','{album_id}','{name}','{img}','{songs_count}'),array("$id","others","$phrases[another_songs]","$img_url","$album2_songs_count[count]"),);


print "<td align=center>";
 run_template('browse_albums'); 
print "</td>";
}
            print "</tr></table>";

          close_table();
compile_hook('songs_after_albums_table');
 //--------------------------- Show Songs Start -----------------------------
  }else{

     
//---- order by vars -------//    
if(!$orderby || !$settings['visitors_can_sort_songs'] || !in_array($orderby,$orderby_checks)){$orderby=($settings['songs_default_orderby'] ? $settings['songs_default_orderby'] : "name");}
if(!$sort || !$settings['visitors_can_sort_songs'] || !in_array($sort,array('asc','desc'))){$sort=($settings['songs_default_sort'] ? $settings['songs_default_sort'] : "asc");}

  
//-------------------------//

  //   if($album_id == "others"){$album_id=0;}  
 //  $album_id=intval($album_id);  

//----------------- start pages system ----------------------
   
if(!$letter){
    $no_singer_name = true; 
}

  songs_table($id,$album_id,$letter,$orderby,$sort,$start);
  
  
if($settings['prev_next_album'] && (string) $album_id  != "all" && !$letter){
prev_next_album($album_id,$id);
}

if($settings['prev_next_singer']){
prev_next_singer($id,$data_singer['cat']);
}




    }
  

//---------------------------------------------------
require(CWD . "/includes/framework_end.php");      
