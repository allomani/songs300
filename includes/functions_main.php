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

//-------- videos path links -----------
function print_videos_path_links($cat,$filename=""){
         global $phrases,$style,$links,$global_align;  
         
      $cat = (int) $cat ;
         if($cat) { 
         
   $data_cat = db_qr_fetch("select name,id,cat,path from songs_videos_cats where id='$cat'"); 
     if($data_cat['path']){         
   $qr=db_query("select name,id,cat from songs_videos_cats where id IN (".$data_cat['path'].")");
   while($data=db_fetch($qr)){
       $cats_data[$data['id']] = $data;
   }
    
   $cats_array = explode(",",$data_cat['path']);
   
           
      foreach($cats_array as $id){
   if($id){
      $dir_data =  $cats_data[$id];

        $dir_content = "<a class='path_link' href=\"".str_replace('{id}',$dir_data['id'],$links['browse_videos'])."\">".$dir_data['name']."</a> $phrases[path_sep] ". $dir_content  ;

        }
      }

   print "<p align=\"$global_align\" class='path'><img src='images/arrw.gif'>&nbsp;<a class='path_link' href=\"".str_replace('{id}','0',$links['browse_videos'])."\">$phrases[the_videos]</a> $phrases[path_sep] $dir_content " . "$filename</p>";
         }
         
         }
}


//---- exts array ----
unset($global_songs_exts);
 $qrcm = db_query("select * from songs_exts");
 while($datacm=db_fetch($qrcm)){
     $global_songs_exts[$datacm['id']] = $datacm;
 }
 //-------------------

 
function get_song_ext($ext_id,$song_date){
      global $global_songs_exts;
      
if($ext_id > -1){
  if($ext_id){ 
  $song_ext =  $global_songs_exts[$ext_id] ;
  
  }else{      
   // unset($song_ext,$song_ext_id); 
  
   foreach($global_songs_exts as  $periods){
     
   if($periods['period_from'] > 0 || $periods['period_to'] > 0){
       $time_from = time() - ($periods['period_from']*60*60*24);
       $time_to = time() - ($periods['period_to']*60*60*24);
      
       if($song_date <= $time_from && $song_date >= $time_to){

       $song_ext = $periods;
                
       break;
       }
   } 
   } 
  }
}
return iif($song_ext['name'] && $song_ext['id'],$song_ext,array("name"=>"","id"=>0));  
  
}


 unset($singers_cache);
 
 
//-------------------------------------------------------------------
function songs_table($id,$album_id,$letter="",$orderby="",$sort="",$start=0,$limit=0){
 
    global $song_ext,$song_ext_id,$lyrics_count,$tr_class,$phrases,$data,$data_singer,$settings,$links,$scripturl,$videos_count,$songs_exts,$singers_cache,$without_tables,$songs_perpage,$songs_start,$page_string,$songs_limit,$page_result;
    
    
  $start=intval($start);
  $songs_start = $start;
  $songs_limit = $limit;
    
    if(!$limit){
       if($letter){
       $page_string= str_replace("{letter}",$letter,$links['letters_songs_w_pages']);
       }else{
       $page_string = singer_url($id,$data['page_name'],$data['name'],$album_id,$orderby,$sort);
     //  $page_string= str_replace(array('{id}','{album_id}','{orderby}','{sort}','{name}'),array($id,$album_id,$orderby,$sort,$data['name']),$links['singer_w_pages']);
       }
    $songs_perpage = $settings['songs_perpage'];   
    
  
    }else{
        $songs_perpage = $limit;
    } 
  //------------------------------------------------------
  
    
    if($letter){
        
 // if(ereg("^([a-zA-Z])*$", $letter)){    
  // $letter_query = "(name like '".db_escape(strtolower($letter))."%' or name like '".db_escape(strtoupper($letter))."%')";
  // }else{
   $letter_query = " songs_songs.name like '".db_escape($letter)."%'";
  // }
   
  
    
$letter = htmlspecialchars($letter);

$qr = db_query("select songs_songs.*,songs_singers.name as singer_name,songs_singers.id as singer_id , songs_singers.page_name as singer_page_name from songs_songs,songs_singers where $letter_query and songs_singers.id = songs_songs.album order by $orderby $sort limit $start,$songs_perpage");
if(!$limit){   
$page_result = db_qr_fetch("select count(*) as count from songs_songs where $letter_query");
}


//$lyrics_count = valueof(db_qr_fetch("select count(*) as count from songs_songs where $letter_query and trim(lyrics)!='' limit $start,$songs_perpage"),"count");
//$videos_count = valueof(db_qr_fetch("select count(*) as count from songs_songs where $letter_query and video_id > 0 limit $start,$songs_perpage"),"count"); 

 
          }else{

  $qr = db_query("select * from songs_songs where album='$id' ".iif((string)$album_id != "all","and album_id='$album_id'")." order by $orderby $sort limit $start,$songs_perpage");
 
 if(!$limit){
  $page_result = db_qr_fetch("select count(*) as count from songs_songs where album='$id' ".iif((string)$album_id != "all","and album_id='$album_id'")."");
 }
 
 //$lyrics_count = db_qr_fetch("select count(id) as count from songs_songs where album='$id' and album_id='$album_id' and trim(lyrics)!='' limit $start,$songs_perpage");

 /* $lyrics_count = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$id' ".iif((string)$album_id != "all","and album_id='$album_id'")." and trim(lyrics)!=''"),"count");
  
  if((string)$album_id != "all"){
   $videos_count = valueof(db_qr_fetch("select count(*) as count from songs_songs where album='$id' and album_id='$album_id' and video_id > 0"),"count");
  }else{
  $videos_count = $data_singer['videos_count'];
  }    */

   }
          
             
    if(db_num($qr)){
compile_hook('songs_before_songs_table');


 
 
 //unset($singers_cache);
 
//--- save results to array -----------
unset($saved_results);
$lyrics_count = 0;
$videos_count = 0;
      while($data = db_fetch($qr)){  
      $saved_results[] = $data;
      if($data['lyrics']){$lyrics_count++;}
      if($data['video_id']){$videos_count++;}
      }
//------------------------------------

run_template("browse_songs_header"); 
//------------------------------------

foreach($saved_results as $data){ 

           if($tr_class == "row_1"){
                   $tr_class="row_2" ;
                   }else{
      $tr_class="row_1";
       }
 
 //-------- ext ------------         
 // unset($song_ext,$song_ext_id);
  $song_ext = get_song_ext($data['ext'],$data['date']); 
  //-------------------------
  
   

            
     //     print "<tr class='$tr_class'>";

          if($letter){
              
       /*  if(isset($singers_cache[$data['album']])){
             $data_singer =  $singers_cache[$data['album']];
          }else{
          $data_singer = db_qr_fetch("select id,name,cat from songs_singers where id='$data[album]'");
         $singers_cache[$data['album']] = $data_singer;
         }       */
         $data_singer['name'] = $data['singer_name'];
         $data_singer['id'] = $data['singer_id'];
         $data_singer['page_name'] = $data['singer_name'];
          }
      
   run_template('browse_songs');
   
 //  print "</tr>";
    }
  
   run_template('browse_songs_footer');
   
   unset($data,$qr,$urls_sets);

//------------ end pages system -------------

compile_hook('songs_after_songs_table'); 
    }else{
  if(!$without_tables){
   open_table();
    }
      print "<center> $phrases[err_no_songs] </center>";
    if(!$without_tables){
    close_table();
    }
    
            }
            
            
}



//--------- prev / next singer ---------------
function prev_next_singer($id,$cat){
    
    global $phrases,$data_prev,$data_next;

if($id && $cat){    
$data_prev = db_qr_fetch("select * from songs_singers where id < $id and cat='$cat' order by id desc limit 1");
$data_next = db_qr_fetch("select * from songs_singers where id > $id and cat='$cat' order by id asc limit 1"); 
if($data_prev['id'] || $data_next['id']){ 
run_template("prev_next_singer");
}
}
}



//--------- prev / next song ---------------
function prev_next_song($id,$singer){
    
    global $phrases,$data_prev,$data_next;

if($id && $singer){    
$data_prev = db_qr_fetch("select * from songs_songs where id < $id and album='$singer' order by id desc limit 1");
$data_next = db_qr_fetch("select * from songs_songs where id > $id and album='$singer' order by id asc limit 1"); 
if($data_prev['id'] || $data_next['id']){ 
run_template("prev_next_song");
}
}
}


//--------- prev / next song ---------------
function prev_next_album($id,$singer){
    
    global $phrases,$data_prev,$data_next,$data_singer;

if($id && $singer){    
$data_prev = db_qr_fetch("select * from songs_albums where id < $id and cat='$singer' order by id desc limit 1");
$data_next = db_qr_fetch("select * from songs_albums where id > $id and cat='$singer' order by id asc limit 1"); 
if($data_prev['id'] || $data_next['id']){ 
run_template("prev_next_album");
}
}
}



   //--------- prev / next video ---------------
function prev_next_video($id,$cat){
    
    global $phrases,$data_prev,$data_next;

if($id && $cat){    
$data_prev = db_qr_fetch("select * from songs_videos_data where id < $id and cat='$cat' order by id desc limit 1");
$data_next = db_qr_fetch("select * from songs_videos_data where id > $id and cat='$cat' order by id asc limit 1"); 
if($data_prev['id'] || $data_next['id']){
    run_template("prev_next_video");
}
}
}


   //--------- prev / next video ---------------
function prev_next_video_cat($id,$cat=0){
    
    global $phrases,$data_prev,$data_next;
$cat = (int) $cat;

if($id){    
$data_prev = db_qr_fetch("select * from songs_videos_cats where id < $id and cat='$cat' order by id desc limit 1");
$data_next = db_qr_fetch("select * from songs_videos_cats where id > $id and cat='$cat' order by id asc limit 1");
if($data_prev['id'] || $data_next['id']){ 
run_template("prev_next_video_cat");
}
}
}

//--------- prev / next cat ---------------
function prev_next_cat($id){
    
    global $phrases,$data_prev,$data_next;

if($id){    
$data_prev = db_qr_fetch("select * from songs_cats where id < $id  order by id desc limit 1");
$data_next = db_qr_fetch("select * from songs_cats where id > $id order by id asc limit 1"); 
if($data_prev['id'] || $data_next['id']){ 
run_template("prev_next_cat");
}
}
}
