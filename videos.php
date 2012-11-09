<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//----------------------------------------------------

$cat= (int) $cat;
 
 compile_hook('videos_start');
 
   print_videos_path_links($cat);
   
  compile_hook('videos_after_path_links'); 
          
//-------- cats -------
$qr = db_query("select * from songs_videos_cats where cat='$cat' and active=1 order by ord asc");
if(db_num($qr)){
 
 templates_cache(array('browse_videos_cats_header','browse_videos_cats_sep','browse_videos_cats','browse_videos_cats_footer'));
     
    
$c=0;
run_template('browse_videos_cats_header'); 

while($data = db_fetch($qr)){
    
    
if ($c==$settings['songs_cells']) {
run_template('browse_videos_cats_sep');   
$c = 0 ;
}

  run_template('browse_videos_cats');   
  $c++;
}

run_template('browse_videos_cats_footer'); 
close_table();
  }else{
    
      $no_cats = true;
  }
 //------------------------
 
 
    //----------------------
   $start = intval($start);
   $perpage = $settings['videos_perpage'];
   $page_string = str_replace('{id}',$cat,$links['browse_videos_w_pages']);
   //---------------------
   
     
      
    $qr = db_query("select songs_videos_data.* from songs_videos_data,songs_videos_cats where songs_videos_data.cat=songs_videos_cats.id and songs_videos_cats.active=1 and songs_videos_cats.id='$cat' order by songs_videos_data.{$settings['videos_orderby']} $settings[videos_sort] limit $start,$perpage");
  
     $data_cat = db_qr_fetch("select name,cat from songs_videos_cats where id='$cat'");       
       
        
    if(db_num($qr)){
        
    //    $videos_count  = db_qr_fetch("select count(*) as count from songs_videos_data where cat='$cat'");
      $videos_count  = db_qr_fetch("select count(*) as count from songs_videos_data,songs_videos_cats where songs_videos_data.cat=songs_videos_cats.id and songs_videos_cats.active=1 and songs_videos_cats.id='$cat'");
      
   templates_cache(array('browse_videos_header','browse_videos_sep','browse_videos','browse_videos_footer'));
   
         
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
           
           
//-------------------- pages system ------------------------
print_pages_links($start,$videos_count['count'],$perpage,$page_string); 
//-----------------------------
 
            }else{
                if($no_cats){
                 open_table();    
                    print "<center> $phrases[err_no_videos] </center>";
                    close_table();
                }
                    }
          
 if($settings['prev_next_video_cat']){
prev_next_video_cat($cat,$data_cat['cat']);
}   


 compile_hook('videos_end');         


//---------------------------------------------------
require(CWD . "/includes/framework_end.php"); 
?>