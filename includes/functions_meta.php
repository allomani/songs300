<?
function set_meta_values(){
       
global $sitename,$phrases,$start,$settings,$keyword,$action,$id,$op,$cat,$section_name,$sec_name,$meta_description,$meta_keywords,$title_sub,$album_id,$year,$letter,$albums_only,$songs_only;
 
//------ Singer / Album Title ---------------
if((CFN == "songs.php" || CFN=="singer_overview.php") && $id){

//$album_id = intval($album_id);
 if(!isset($album_id) || $album_id=="all"){$album_id = "all";}
 
$qr = db_query("select songs_singers.*,songs_cats.name as cat_name from songs_singers,songs_cats where songs_singers.id='$id' and songs_cats.id = songs_singers.cat");
if(db_num($qr)){
$data = db_fetch($qr) ;
   

//$data_singer['albums_count'] = valueof(db_qr_fetch("select count(*) as count from songs_albums where cat='$id'"),"count");   
            
            
$start =  (int) $start;
$perpage = intval($settings['songs_perpage']);    
    
if($start){
    $page_number = str_replace("{x}",intval(($start / $perpage) + 1),$phrases['page_number_x']);
    $sp = " - ";
}else{
    $page_number = "";
    $sp = "";
}


 if(CFN=="songs.php" && (($data['albums_count'] && (string)$album_id=="all" && !$songs_only) || $albums_only)){
  $meta = get_meta_values('singer_albums');   
 }else{
if((string)$album_id=="all" && CFN=="songs.php"){
$meta = get_meta_values('singer_songs');    
}elseif((string)$album_id !="all" && CFN=="songs.php"){   
if($album_id==0){
    $data_album['name'] = $phrases['another_songs'];
    $data_album['year'] = "";
}else{
$data_album = db_qr_fetch("select * from songs_albums where id='".db_escape($album_id)."'");
if(!$data_album['year']){$data_album['year']="";}
}
$meta = get_meta_values('album');
}else{
$meta = get_meta_values('singer');
}
 }


$to_find = array("{name}","{album}","{album_year}","{cat_name}","{sp}","{page}");
$to_replace = array($data['name'],$data_album['name'],$data_album['year'],$data['cat_name'],$sp,$page_number);

if($data_album['id']){
$title_sub = iif($data_album['page_title'],$data_album['page_title'],str_replace($to_find,$to_replace,$meta['title'])) ;
$meta_description = iif($data_album['page_description'],$data_album['page_description'],str_replace($to_find,$to_replace,$meta['description']));
$meta_keywords = iif($data_album['page_keywords'],$data_album['page_keywords'],str_replace($to_find,$to_replace,$meta['keywords']));

}else{     
$title_sub = iif($data['page_title'],$data['page_title'],str_replace($to_find,$to_replace,$meta['title'])) ;
$meta_description = iif($data['page_description'],$data['page_description'],str_replace($to_find,$to_replace,$meta['description']));
$meta_keywords = iif($data['page_keywords'],$data['page_keywords'],str_replace($to_find,$to_replace,$meta['keywords']));
}


}else{
 $title_sub = "" ;
 $meta_description = "";
 $meta_keywords = "";
 }
 }            
                 



                 
 //-------- Singer Videos -------------------
 if(CFN == "singer_videos.php"){
     $data = db_qr_fetch("select songs_singers.*,songs_cats.name as cat_name from songs_singers,songs_cats where songs_singers.id='$id' and songs_cats.id = songs_singers.cat");
 
 $start =  (int) $start;
$perpage = intval($settings['videos_perpage']);    
    
if($start){
    $page_number = str_replace("{x}",intval(($start / $perpage) + 1),$phrases['page_number_x']);
    $sp = " - ";
}else{
    $page_number = "";
    $sp = "";
}


   $meta = get_meta_values('singer_videos'); 

   $to_find = array("{name}","{cat_name}","{sp}","{page}");
$to_replace = array($data['name'],$data['cat_name'],$sp,$page_number);


$title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);  
 }
 
 
 //-------- Singer Videos -------------------
 if(CFN == "singer_bio.php"){
     $data = db_qr_fetch("select songs_singers.*,songs_cats.name as cat_name from songs_singers,songs_cats where songs_singers.id='$id' and songs_cats.id = songs_singers.cat");

   $meta = get_meta_values('singer_bio'); 

   $to_find = array("{name}","{cat_name}");
$to_replace = array($data['name'],$data['cat_name']);


$title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);  
 }
 
  //-------- Singer Photos -------------------
 if(CFN == "singer_photos.php"){
     
 if($id){
      $data = db_qr_fetch("select songs_singers.*,songs_cats.name as cat_name,songs_singers_photos.name as photo_name from songs_singers,songs_cats,songs_singers_photos where songs_singers.id=songs_singers_photos.cat and songs_singers_photos.id='$id' and songs_cats.id = songs_singers.cat");
 
        $meta = get_meta_values('singer_photo'); 

   $to_find = array("{id}","{name}","{cat_name}","{photo_name}");
$to_replace = array($id,$data['name'],$data['cat_name'],$data['photo_name']);


$title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']); 

 }else{
     $data = db_qr_fetch("select songs_singers.*,songs_cats.name as cat_name from songs_singers,songs_cats where songs_singers.id='$cat' and songs_cats.id = songs_singers.cat");
 
 $start =  (int) $start;
$perpage = intval($settings['photos_perpage']);    
    
if($start){
    $page_number = str_replace("{x}",intval(($start / $perpage) + 1),$phrases['page_number_x']);
    $sp = " - ";
}else{
    $page_number = "";
    $sp = "";
}


   $meta = get_meta_values('singer_photos'); 

   $to_find = array("{name}","{cat_name}","{sp}","{page}");
$to_replace = array($data['name'],$data['cat_name'],$sp,$page_number);


$title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);  
 }
 }
   //------ profile  ---------------

if(CFN == "profile.php"){
$qr = db_query("select ".members_fields_replace("username")." from ".members_table_replace('songs_members')." where ".members_fields_replace('id')."='$id'",MEMBER_SQL);
if(db_num($qr)){
$data = db_fetch($qr) ;

$meta = get_meta_values('member_profile'); 
$to_find = array("{name}");
$to_replace = array($data[members_fields_replace("username")]);


$title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);


        }else{
 $title_sub = "$phrases[the_profile]" ;
 $meta_description = "";
 $meta_keywords = "";
 }
 }
 
    //---------- members page ---------------
 if(CFN=="members.php"){
 

$start =  (int) $start;
$perpage = intval($settings['members_perpage']);    
    
if($start){
    $page_number = str_replace("{x}",intval(($start / $perpage) + 1),$phrases['page_number_x']);
    $sp = " - ";
}else{
    $page_number = "";
    $sp = "";
}


$meta = get_meta_values('members_page'); 
$to_find = array("{sp}","{page}");
$to_replace = array($sp,$page_number);


   $title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);

 }

//------ Songs Cats Title ---------------
if(CFN == "browse.php" && !$letter && $id){
$qr = db_query("select name,page_title,page_description,page_keywords from songs_cats where id='$id'");
if(db_num($qr)){
$data = db_fetch($qr) ;

$meta = get_meta_values('cats');
$to_find = array("{name}");
$to_replace = array($data['name']);

$title_sub = iif($data['page_title'],$data['page_title'],str_replace($to_find,$to_replace,$meta['title'])) ;
$meta_description = iif($data['page_description'],$data['page_description'],str_replace($to_find,$to_replace,$meta['description']));
$meta_keywords = iif($data['page_keywords'],$data['page_keywords'],str_replace($to_find,$to_replace,$meta['keywords']));

        }else{
 $title_sub = "" ;
 $meta_description = "";
 $meta_keywords = "";
 }
 }
 
 
   //---------- songs letters ---------------
 if(CFN=="songs.php" && $letter){
 
 $letter = htmlspecialchars($letter);   
 
$start =  (int) $start;
$perpage = intval($settings['songs_perpage']);    
    
if($start){
    $page_number = str_replace("{x}",intval(($start / $perpage) + 1),$phrases['page_number_x']);
    $sp = " - ";
}else{
    $page_number = "";
    $sp = "";
}


$meta = get_meta_values('letters_songs'); 
$to_find = array("{letter}","{sp}","{page}");
$to_replace = array($letter,$sp,$page_number);


   $title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);

 }
 
 
  //---------- songs letters ---------------
 if(CFN=="browse.php" && $letter){
 
 $letter = htmlspecialchars($letter);   
$meta = get_meta_values('letters_singers'); 
$to_find = array("{letter}");
$to_replace = array($letter);


   $title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);

 }
 
   //---------- songs listen / lyrics ---------------
 if(CFN=="listen.php" || CFN=="listen_window.php" || CFN=="lyrics.php"){
 

$data = db_qr_fetch("select songs_songs.name,songs_songs.album_id, songs_singers.name as singer_name,songs_cats.name as cat_name  from songs_songs,songs_singers,songs_cats  where songs_singers.id=songs_songs.album and songs_cats.id = songs_singers.cat and songs_songs.id='$id'");   
  
if($data['album_id']){
    $data_album = db_qr_fetch("select name from songs_albums where id='$data[album_id]'");
}else{
    $data_album['name'] = "";
}

     
    
 if(CFN=="lyrics.php"){ 
$meta = get_meta_values('song_lyrics'); 
 }else{
 $meta = get_meta_values('song_listen'); 
 }
 
$to_find = array("{name}","{singer_name}","{cat_name}","{album_name}");
$to_replace = array($data['name'],$data['singer_name'],$data['cat_name'],$data_album['name']);


   $title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);

 }
 
 
 
 
 
//------ Videos Cats Title ---------------
if(CFN == "videos.php"){
    
$start =  (int) $start;
$perpage = intval($settings['videos_perpage']);    
    
if($start){
    $page_number = str_replace("{x}",intval(($start / $perpage) + 1),$phrases['page_number_x']);
    $sp = " - ";
}else{
    $page_number = "";
    $sp = "";
}


$qr = db_query("select name,page_title,page_description,page_keywords from songs_videos_cats where id='$cat'");
if(db_num($qr)){
$data = db_fetch($qr) ;

$meta = get_meta_values('videos_cat');
  }else{
  $data = array();
$meta = get_meta_values('videos');
 }
 
$to_find = array("{name}","{sp}","{page}");
$to_replace = array($data['name'],$sp,$page_number);

$title_sub = iif($data['page_title'],$data['page_title'],str_replace($to_find,$to_replace,$meta['title'])) ;
$meta_description = iif($data['page_description'],$data['page_description'],str_replace($to_find,$to_replace,$meta['description']));
$meta_keywords = iif($data['page_keywords'],$data['page_keywords'],str_replace($to_find,$to_replace,$meta['keywords']));

      

 }
 
 //------- Video Watch ---------------
 if(CFN == "video_watch.php"){
     $data = db_qr_fetch("select songs_videos_data.* , songs_videos_cats.name as cat_name  from songs_videos_data,songs_videos_cats  where songs_videos_data.id='$id' and songs_videos_cats.id = songs_videos_data.cat");   
    
$meta = get_meta_values('video_watch'); 
$to_find = array("{name}","{cat_name}");
$to_replace = array($data['name'],$data['cat_name']);


   $title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);
 }
 
 
 //------- Albums Page -----------------
 if(CFN == "albums.php"){
 
   
   $start =  (int) $start;
   $year = (int) $year;
   
$perpage = intval($settings['albums_perpage']);    
    
if($start){
    $page_number = str_replace("{x}",intval(($start / $perpage) + 1),$phrases['page_number_x']);
    $sp = " - ";
}else{
    $page_number = "";
    $sp = "";
}

  
if($year){
    $meta = get_meta_values('albums_page_w_year'); 
    $to_find = array("{year}","{sp}","{page}"); 
$to_replace = array($year,$sp,$page_number);
}else{
    $meta = get_meta_values('albums_page'); 
  $to_find = array("{sp}","{page}");   
$to_replace = array($sp,$page_number);
}
 
 $title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);    
 }
 
 
 //------ News Title ---------------
if(CFN == "news.php"){
if($id){
$qr = db_query("select title from songs_news where id='$id'");
if(db_num($qr)){
$data = db_fetch($qr) ;

$meta = get_meta_values('news'); 
$to_find = array("{name}");
$to_replace = array($data['title']);


$title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);


        }else{
 $title_sub = "$phrases[the_news]" ;
 $meta_description = "";
 $meta_keywords = "";
 }
 }else{
     

    $start =  (int) $start;
    $news_perpage = intval($settings['news_perpage']);    
    
    
if($start){
    $page_number = str_replace("{x}",intval(($start / $news_perpage) + 1),$phrases['page_number_x']);
    $sp = " - ";
}else{
    $page_number = "";
    $sp = "";
}

if($cat){$data  = db_qr_fetch("select name from songs_news_cats where id='$cat'");}
if(!$data['name']){$data['name'] = $phrases['the_news'];}

$meta = get_meta_values('news_cats'); 
$to_find = array("{name}","{page}","{sp}");
$to_replace = array($data['name'],$page_number,$sp);    


$title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']); 
     
 }
 
}
  //------ Pages Title ---------------
if($action == "pages"){
$qr = db_query("select title from songs_pages where id='$id'");
if(db_num($qr)){
$data = db_fetch($qr) ;
   $meta = get_meta_values('pages');  


$to_find = array("{name}");
$to_replace = array($data['title']);


$title_sub = str_replace($to_find,$to_replace,$meta['title']) ;
$meta_description = str_replace($to_find,$to_replace,$meta['description']);
$meta_keywords = str_replace($to_find,$to_replace,$meta['keywords']);


        }else{
 $title_sub = "$phrases[the_pages]" ;
 $meta_description = "";
 $meta_keywords = "";
 }
 }
   //------ Search Title ---------------
if(CFN == "search.php" && $keyword){

$keyword = strip_tags(htmlspecialchars($keyword));
$keyword = trim($keyword);


if($keyword){

    $start =  (int) $start;
    if($op == "songs" || !$op){
    $songs_perpage = intval($settings['songs_perpage']);   
    }elseif($op=="videos"){
        $songs_perpage = intval($settings['videos_perpage']);   
    }elseif($op=="singers"){
        $songs_perpage = intval($settings['singers_perpage']);   
    }elseif($op=="albums"){
        $songs_perpage = intval($settings['albums_perpage']);   
    }elseif($op=="news"){
        $songs_perpage = intval($settings['news_perpage']);   
        
    }else{
        $songs_perpage = intval($settings['songs_perpage']);   
    }
     
    
    
if($start){
    $page_number = str_replace("{x}",intval(($start / $songs_perpage) + 1),$phrases['page_number_x']);
    $sp = " - ";
}else{
    $page_number = "";
    $sp = "";
}


$meta = get_meta_values('search'); 
$to_find = array("{name}","{page}","{sp}");
$to_replace = array($keyword,$page_number,$sp);


$title_sub = iif($data['page_title'],$data['page_title'],str_replace($to_find,$to_replace,$meta['title'])) ;
$meta_description = iif($data['page_description'],$data['page_description'],str_replace($to_find,$to_replace,$meta['description']));
$meta_keywords = iif($data['page_keywords'],$data['page_keywords'],str_replace($to_find,$to_replace,$meta['keywords']));
        }else{
 $title_sub = "" ;
 $meta_description = "";
 $meta_keywords = "";
 }

 }
 
 
  //------ Contact us ---------------
if(CFN == "contactus.php"){

$meta = get_meta_values('contactus'); 

$title_sub = $meta['title'] ;
$meta_description = $meta['description'];
$meta_keywords = $meta['keywords']; 

 }
 
 
  //------ Search Title ---------------
if(CFN == "votes.php"){

$meta = get_meta_values('votes'); 

$title_sub = $meta['title'] ;
$meta_description = $meta['description'];
$meta_keywords = $meta['keywords']; 

 }
 
//-------------------------------------
//if($section_name){
//$sec_name = " -  $section_name" ;
       // }
if(!$meta_description){ $meta_description= $settings['header_description']." , ".iif($title_sub,$title_sub,$sitename);}
if(!$meta_keywords){$meta_keywords = iif($settings['header_keywords'],$settings['header_keywords'],$settings['header_description'])." , ".iif($title_sub,$title_sub,$sitename); }  

}



function get_meta_values($name){
    $data = db_qr_fetch("select * from songs_meta where name like '".db_escape($name)."'");
    return $data;
}

?>