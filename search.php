<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//----------------------------------------------------

 if($settings['enable_search']){ 
     
  
         $keyword = trim($keyword);
         
        if(strlen($keyword) >= $settings['search_min_letters']){
          
              $keyword = htmlspecialchars($keyword); 
              
                compile_hook('search_start');   
       open_table("$phrases[search_results]" );
       

       if(!$op || $op=="songs"){

           $field_id = (int) $field_id;
           
        //----------------- start pages system ----------------------
   $start=intval($start);
       $page_string= "search.php?op=songs&".iif($ext,"ext=".intval($ext)."&").iif($c_ext,"c_ext=".htmlspecialchars($c_ext)."&").iif($field_id,"field_id=".intval($field_id)."&")."keyword=".urlencode($keyword)."&start={start}" ;
      $perpage = $settings['songs_perpage'] ;
        //--------------------------------------------------------------
   
   
    /* if($full_text_search && strlen($keyword) >=4){ 
     $qr=db_query("select *,match(name) against('".db_escape($keyword)."') as score from songs_songs where match(name) against('".db_clean_string($keyword,"code","read")."') order by score desc limit $start,$perpage");
         $page_result=db_qr_fetch("select count(*) as count from songs_songs where match(name) against('".db_clean_string($keyword,"code","read")."')");
     }else{   */
       
 if($ext){ 
 $by_field_query = "( songs_songs.ext='".intval($ext)."'";
 if($global_songs_exts[$ext]['period_from'] || $global_songs_exts[$ext]['period_to']){
     $time_from = time() - ($global_songs_exts[$ext]['period_from']*60*60*24);
       $time_to = time() - ($global_songs_exts[$ext]['period_to']*60*60*24);

 $by_field_query .= " or ( songs_songs.date <= $time_from  and songs_songs.date >=  $time_to and ext=0 )";
 }
 
 $by_field_query .= " ) ";
 }else{
  $by_field_query = iif($field_id,"songs_songs.field_".$field_id ." like '".db_escape($keyword)."' ",iif($c_ext,"songs_songs.c_ext like '".db_escape($keyword)."' ","songs_songs.name like '%".db_escape($keyword)."%' "));
 }
   
$qr = db_query("select songs_songs.*,songs_singers.name as singer_name,songs_singers.id as singer_id , songs_singers.page_name as singer_page_name from songs_songs,songs_singers where ".$by_field_query." and songs_singers.id = songs_songs.album order by name asc limit $start,$perpage"); 

  // }
     

  
 if(db_num($qr)){
 $page_result = db_qr_fetch("select count(*) as count from songs_songs where ".$by_field_query);
 
// $lyrics_count = valueof(db_qr_fetch("select count(*) as count from songs_songs where ".$by_field_query."  and trim(lyrics)!='' limit $start,$songs_perpage"),"count");
//$videos_count = valueof(db_qr_fetch("select count(*) as count from songs_songs where ".$by_field_query." and video_id > 0 limit $start,$songs_perpage"),"count"); 

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

      $tr_ord = 1 ;
          foreach($saved_results as $data){

       $song_ext =  get_song_ext($data['ext'],$data['date']) ;
       
       
       
              if($tr_class=="row_1"){
                   $tr_class="row_2" ;
                   }else{
                    $tr_class="row_1";
                           }

      $data_singer['name'] = $data['singer_name'];
         $data_singer['id'] = $data['singer_id'];
         $data_singer['page_name'] = $data['singer_page_name'];
         
         

       //  $data['name'] = str_replace("$keyword","<font class=\"search_replace\">$keyword</font>",$data['name']);

      //  print "<tr class='$tr_class'>";
        //------------ sync urls data  ------------//
    
         
 run_template('browse_songs');
   //       print "</tr>";
    }
    
  run_template('browse_songs_footer');
      
//-------------------- pages system ------------------------
print_pages_links($start,$page_result['count'],$perpage,$page_string);
//------------ end pages system -------------
      }else{
                print "<center>  $phrases[no_results] </center>";
                }
//---------------------------------------------------------------------------------

}elseif($op=="videos"){
    
        //----------------- start pages system ----------------------
   $start=intval($start);
       $page_string= "search.php?op=videos&keyword=".urlencode($keyword)."&start={start}" ;
      $perpage = $settings['videos_perpage'] ;
        //--------------------------------------------------------------
        
         
$qr=db_query("select a.*,b.name as cat_name , b.id as cat_id from songs_videos_data a , songs_videos_cats b where a.name like '%".db_escape($keyword)."%' and b.id=a.cat limit $start,$perpage");
      

if(db_num($qr)){
$page_result = db_qr_fetch("select count(*) as count from songs_videos_data where name like '%".db_escape($keyword)."%'");

run_template('browse_videos_header');

    $c=0;
while($data = db_fetch($qr)){



if ($c==$settings['songs_cells']) {
run_template("browse_videos_sep");
$c = 0 ;
}

$data_cat['name'] = $data['cat_name'];
$data_cat['id'] = $data['cat_id'];


    run_template('browse_videos');
$c++;

           }
run_template('browse_videos_footer');


//-------------------- pages system ------------------------
print_pages_links($start,$page_result['count'],$perpage,$page_string); 
//-----------------------------


              }else{
                 print "<center>  $phrases[no_results] </center>";
                      }
//--------------------- singers -----------------------
        }elseif($op=="singers"){
            
/*if($full_text_search && strlen($keyword) >=4){
$qr=db_query("select *,match(name) against('".db_clean_string($keyword,"code","read")."') as score from songs_singers where match(name) against('".db_clean_string($keyword,"code","read")."') order by score desc limit $start,$perpage");
}else{  */   
//$qr = db_query("select * from songs_singers where name like '%".db_clean_string($keyword,"code")."%' order by binary name ASC");
//}

       
        
        
$field_id = (int) $field_id;

 //----------------- start pages system ----------------------
   $start=intval($start);
       $page_string= "search.php?op=singers&".iif($field_id,"field_id=".intval($field_id)."&")."keyword=".urlencode($keyword)."&start={start}" ;
      $perpage = $settings['singers_perpage'] ;
        //--------------------------------------------------------------
        
$by_field_query = iif($field_id,"field_".$field_id." like '".db_escape($keyword)."'","name like '%".db_escape($keyword)."%'");
        
$qr = db_query("select songs_singers.*,songs_cats.name as cat_name , songs_cats.id as cat_id , songs_cats.page_name as cat_page_name from songs_singers,songs_cats where songs_singers.active=1 and songs_singers.cat=songs_cats.id and songs_cats.active=1 and songs_singers.".$by_field_query." order by  songs_singers.name asc limit $start,$perpage");


    if(db_num($qr)){

$page_result = db_qr_fetch("select count(*) as count from songs_singers where ".$by_field_query);

    print "<table width=100%><tr>";
    while($data = db_fetch($qr)){
   // $data_cat = db_qr_fetch("select id,name from songs_cats where id='$data[cat]'");

if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
$c = 0 ;
}
    ++$c ;
    //   $singer_songs_count = db_qr_fetch("select count(id) as count from songs_songs where album='$data[id]'");
         

       print "<td>";
       run_template('browse_singers');
       print "</td>";

             }
             print "</tr></table>";

print_pages_links($start,$page_result['count'],$perpage,$page_string);

            }else{
               print "<center>  $phrases[no_results] </center>";
                    }
 
 //----------- albums ---------------                   
 }elseif($op=="albums"){
            
/*if($full_text_search && strlen($keyword) >=4){
$qr=db_query("select *,match(name) against('".db_clean_string($keyword,"code","read")."') as score from songs_singers where match(name) against('".db_clean_string($keyword,"code","read")."') order by score desc limit $start,$perpage");
}else{  */   
//$qr = db_query("select * from songs_singers where name like '%".db_clean_string($keyword,"code")."%' order by binary name ASC");
//}

$field_id = (int) $field_id;


//----------------- start pages system ----------------------
   $start=intval($start);
       $page_string= "search.php?op=albums&".iif($field_id,"field_id=".intval($field_id)."&").iif($year,"year=".intval($year)."&")."keyword=".urlencode($keyword)."&start={start}" ;
      $perpage = $settings['albums_perpage'] ;
        //--------------------------------------------------------------
        
        
$by_field_query =  iif($year,"year like '".db_escape($keyword)."'",iif($field_id,"field_".$field_id." like '".db_escape($keyword)."'","name like '%".db_escape($keyword)."%'"));
$qr = db_query("select songs_albums.*,songs_singers.name as singer_name , songs_singers.id as singer_id , songs_singers.page_name as singer_page_name from songs_singers,songs_albums where  songs_singers.id=songs_albums.cat and songs_albums.".$by_field_query."  order by  songs_albums.year desc , id desc limit $start,$perpage");


    if(db_num($qr)){
                                    
    print "<table width=100%><tr>";
    while($data_album = db_fetch($qr)){
$page_result = db_qr_fetch("select count(*) as count from songs_albums where ".$by_field_query);

   // $data_cat = db_qr_fetch("select id,name from songs_cats where id='$data[cat]'");

if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
$c = 0 ;
}
    ++$c ;
    //   $singer_songs_count = db_qr_fetch("select count(id) as count from songs_songs where album='$data[id]'");
         

       print "<td>";
       run_template('browse_albums');
       print "</td>";

             }
             print "</tr></table>";
             
print_pages_links($start,$page_result['count'],$perpage,$page_string);
            }else{
               print "<center>  $phrases[no_results] </center>";
                    }
//-----------------------------------------------------
}elseif($op=="news"){


              //----------------- start pages system ----------------------
    $start=intval($start);
       $page_string= "search.php?op=news&keyword=".urlencode($keyword)."&start={start}" ;
       $news_perpage = $settings['news_perpage'];
        //--------------------------------------------------------------


      
       $qr = db_query("select * from songs_news where title like '%".db_escape($keyword)."%' or content  like '%".db_escape($keyword)."%' or details  like '%".db_escape($keyword)."%' order by id desc limit $start,$news_perpage");

 
    if(db_num($qr)){
         $page_result = db_qr_fetch("select count(*) as count from songs_news where title like '%".db_escape($keyword)."%' or content  like '%".db_escape($keyword)."%' or details  like '%".db_escape($keyword)."%'");

         
// run_template("browse_news_header");
    while($data = db_fetch($qr)){
/* if ($c==$settings['songs_cells']) {
run_template("browse_news_sep");  
$c = 0 ;
}
    ++$c ;*/
    
    
   run_template('browse_news');
             }
//run_template('browse_news_footer');   

//-------------------- pages system ------------------------
print_pages_links($start,$page_result['count'],$news_perpage,$page_string);
//------------ end pages system -------------

            }else{
               print "<center>  $phrases[no_results] </center>";

        }
        
        }
//-----------------------------------------------------
close_table();

compile_hook('search_end'); 
//----------------
         }else{
         open_table();
         $phrases['type_search_keyword'] = str_replace('{letters}',$settings['search_min_letters'],$phrases['type_search_keyword']);
                 print "<center>  $phrases[type_search_keyword] </center>";
                 close_table();
                 }
                 
                 
}else{
 open_table();
 print "<center> $phrases[sorry_search_disabled]</center>";
 close_table();
     }
//---------------------------------------------------
require(CWD . "/includes/framework_end.php"); 
?>