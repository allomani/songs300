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
print "<?xml version=\"1.0\" encoding=\"$settings[site_pages_encoding]\" ?> \n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?

if(!$op){
//---------- cats -------------
$qr=db_query("select * from songs_cats order by id");
while($data = db_fetch($qr)){
print "<url>
<loc>".htmlspecialchars($scripturl."/".cat_url($data['id'],$data['page_name'],$data['name']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}
//---------- singers -------------
$qr=db_query("select * from songs_singers order by id") ;
while($data = db_fetch($qr)){
print "<url>
<loc>".htmlspecialchars($scripturl."/".singer_url($data['id'],$data['page_name'],$data['name']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}


//---------- albums -------------
$qr=db_query("select songs_albums.*,b.name as singer_name,b.page_name as singer_page_name  from songs_albums,songs_singers b where b.id = songs_albums.cat order by songs_albums.id") ;

while($data = db_fetch($qr)){                                                           

print "<url>
<loc>".htmlspecialchars($scripturl."/".album_url($data['id'],$data['page_name'],$data['name'],$data['cat'],$data['singer_page_name'],$data['singer_name']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}

//---------- videos cats -------------
$qr=db_query("select id from songs_videos_cats order by id desc");
while($data = db_fetch($qr)){
print "<url>
<loc>".htmlspecialchars($scripturl."/".str_replace("{id}",$data['id'],$links['browse_videos']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}

//---------- News -------------
$qr=db_query("select id from songs_news order by id");
while($data = db_fetch($qr)){
print "<url>
<loc>".htmlspecialchars($scripturl."/".str_replace("{id}",$data['id'],$links['news_details']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}


//---------- Pages -------------
$qr=db_query("select id from songs_pages order by id");
while($data = db_fetch($qr)){
print "<url>
<loc>".htmlspecialchars($scripturl."/".str_replace("{id}",$data['id'],$links['pages']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}


//--------------------------
}



//---------- SONGS ---------------------
if($op=="songs"){
    
$page = intval($page);
      if(!$page){$page=1;}
      $perpage =  $sitemap_perpage ;
      $start = (($page-1) * $perpage) ;

$qr=db_query("select id from songs_songs order by id limit $start,$perpage");
while($data = db_fetch($qr)){
print "<url>
<loc>".htmlspecialchars($scripturl."/".str_replace(array('{cat}','{id}'),array($settings['default_url_id'],$data['id']),$links['song_listen']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}
}


//-------- VIDEOS ---------------------
if($op=="videos"){
    $page = intval($page);
      if(!$page){$page=1;}
      $perpage =  $sitemap_perpage ;
      $start = (($page-1) * $perpage) ;

$qr=db_query("select id from songs_videos_data order by id limit $start,$perpage");
while($data = db_fetch($qr)){
print "<url>
<loc>".htmlspecialchars($scripturl."/".str_replace('{id}',$data['id'],$links['video_watch']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}
}



//-------- PHOTOS ---------------------
if($op=="photos"){
    $page = intval($page);
      if(!$page){$page=1;}
      $perpage =  $sitemap_perpage ;
      $start = (($page-1) * $perpage) ;

$qr=db_query("select id from songs_singers_photos order by id limit $start,$perpage");
while($data = db_fetch($qr)){
print "<url>
<loc>".htmlspecialchars($scripturl."/".str_replace('{id}',$data['id'],$links['singer_photo']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}
}

print "</urlset>";

