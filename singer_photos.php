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
//---------------------------------------------------------
if($id){                           
                                
//---------- view photo ---------------------

$qr = db_query("select * from songs_singers_photos where id='$id'");
 if(db_num($qr)){
         $data = db_fetch($qr);
$data_singer = db_qr_fetch("select * from songs_singers where id='$data[cat]'");
  $data_cat = db_qr_fetch("select * from songs_cats where id='$data_singer[cat]'");      
    
     
   //--- update views ----//
   db_query("update songs_singers_photos set views=views+1 where id='$id'");
   //--------------------//

   
//--- data photos array----
 $qrc = db_query("select id,thumb from songs_singers_photos where cat='$data[cat]' order by ord");
 $i=0;
 unset($list,$cur_index);
while($datac=db_fetch($qrc)){
    $list[$i]['thumb'] = $datac['thumb']; 
    $list[$i]['id'] = $datac['id']; 
    if($datac['id']==$id){$cur_index = $i;}
    $i++;
}
$prev_index = $cur_index - 1;
$next_index = $cur_index + 1;
//-------------------------

//----- in this photo --------//  
       $in_this_photo = "";
        $qra = db_query("select songs_singers.*  from songs_singers,songs_singers_photos_tags where songs_singers.id=songs_singers_photos_tags.singer_id and songs_singers_photos_tags.photo_id='$id' and songs_singers_photos_tags.singer_id > 0 order by songs_singers.name asc");
        while($dataa = db_fetch($qra)){
                $in_this_photo .= iif($in_this_photo," , ")."<a href=\"".singer_url($dataa['id'],$dataa['page_name'],$dataa['name'])."\" title=\"$dataa[name]\">$dataa[name]</a>";
        }
       
       
        $qra = db_query("select *  from songs_singers_photos_tags where photo_id='$id' and singer_id='0' order by name asc");
        while($dataa = db_fetch($qra)){
                 $in_this_photo .= iif($in_this_photo," , ")."$dataa[name]"; 
        }
//------------------------------

 print "<span class='path'> <img src='images/arrw.gif' border=0> <a class=path_link href=\"".cat_url($data_cat['id'],$data_cat['page_name'],$data_cat['name'])."\" title=\"$data_cat[name]\">$data_cat[name]</a> $phrases[path_sep] <a class=path_link href=\"".singer_url($data_singer['id'],$data_singer['page_name'],$data_singer['name'])."\" title=\"$data_singer[name]\">$data_singer[name]</a> $phrases[path_sep] <a class='path_link' href=\"".str_replace(array("{id}","{name}"),array($data_singer['id'],singer_url_name($data_singer['page_name'],$data_singer['name'])),$links['singer_photos'])."\" title=\"$phrases[singer_photos]\">$phrases[singer_photos]</a>" . " $phrases[path_sep] $phrases[the_photo] ".($cur_index+1)."/".count($list)."<br><br></span>";


   
       open_table();
       run_template('photo_view');
       close_table();
      
      //------ Comments -------------------
if($settings['enable_singer_photo_comments']){
    open_table($phrases['members_comments']);
    get_comments_box('singer_photo',$id);
    close_table();
}

 
   }else{
          open_table();
         print "<center> $phrases[err_wrong_url]</center>";
         close_table();
         }
    
}elseif($cat){
 
 
  
 $qr=db_query("select * from songs_singers where id='$cat'");   
     
   //  if(db_num($qr)){
         unset($data_singer);
        
         
         $data = db_fetch($qr);
      db_query("update songs_singers set views=views+1 where id='$cat'"); 
       
         $data_singer = $data;
        // $data_singer['songs_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$cat'"),"count");
        //  $data_singer['albums_count'] = valueof(db_qr_fetch("select count(*) as count from songs_albums where cat='$cat'"),"count");    
       //  $data_singer['photos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_singers_photos where cat='$cat'"),"count");    
      //   $data_singer['videos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_videos_tags where singer_id='$id'"),"count");
     // $data_singer['videos_count'] = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$cat' and video_id > 0"),"count");
         
     //     $data4 = db_qr_fetch("select date from songs_songs where album='$id' order by date DESC limit 1");
         $data_cat = db_qr_fetch("select * from songs_cats where id='$data[cat]'");

print "<span class='path'> <img src='images/arrw.gif' border=0> <a class=path_link href=\"".cat_url($data_cat['id'],$data_cat['page_name'],$data_cat['name'])."\" title=\"$data_cat[name]\">$data_cat[name]</a> $phrases[path_sep] <a class=path_link href=\"".singer_url($data_singer['id'],$data_singer['page_name'],$data_singer['name'])."\" title=\"$data_singer[name]\">$data_singer[name]</a> $phrases[path_sep] $phrases[singer_photos]</a><br><br></span>";


 
 compile_hook('songs_after_path_links');
   run_template('singer_info_table');  
compile_hook('songs_after_singer_table');
     
      
      
//-------- show singer photos --------------------
  
  //----------------------
   $start = intval($start);
   $perpage = $settings['photos_perpage'];
  $page_string = str_replace(array("{id}","{name}"),array($data_singer['id'],singer_url_name($data_singer['page_name'],$data_singer['name'])),$links['singer_photos_w_pages']);
   //---------------------
   
   
   $qr = db_query("select * from songs_singers_photos where cat='$cat' order by ord asc limit $start,$perpage");
         if(db_num($qr)){
      $page_result = db_qr_fetch("select count(*) as count from songs_singers_photos where cat='$cat'");       
        //     $data_singer = db_qr_fetch("select id,name,page_name,cat from songs_singers where id='$cat'");
         //    $data_cat = db_qr_fetch("select id,name,page_name from songs_cats where id='$data_singer[cat]'");

         open_table($phrases['singer_photos']);
         run_template('browse_photos');
       /*  print "<center><table width='100%'><tr>";
         $c=0 ;
         while($data = db_fetch($qr)){
          if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
$c = 0 ;
}
 ++$c ;
  print "<td align=center><a href=\"".str_replace("{id}",$data['id'],$links['singer_photo'])."\"><img border=0 src=\"".get_image($data['thumb'])."\" title=\"$data[name]\"></a></td>" ;
                 }
          /*       if(($settings['movie_photos_cells']-$i) > 0){
                         print "<td colspan=".($settings['movie_photos_cells']-$i)."></td>";
                         }    */
               /*  print "</tr></table>";  */
         close_table();
         
  //-------------------- pages system ------------------------
print_pages_links($start,$page_result['count'],$perpage,$page_string); 
//-----------------------------

                 } 
    
}else{
    open_table();
    print "<center>$phrases[err_wrong_url]</center>";
    close_table();
}
    

//---------------------------------------------
require(CWD . "/includes/framework_end.php"); 
?>
