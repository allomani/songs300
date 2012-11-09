<?
header('Content-type: text/xml');
include "global.php" ;
print "<?xml version=\"1.0\" encoding=\"$settings[site_pages_encoding]\" ?> \n";
if(!$op){$op="singers";}
?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<? print "<title><![CDATA[$sitename - ".htmlspecialchars($op)."]]></title>\n";?>
<description></description>
<?print "<link>$scripturl</link>\n";
print "<copyright><![CDATA[$settings[copyrights_sitename]]]></copyright>";
?>

<?
//-------------------- singers --------------------------------
if($op=="singers"){

$qr=db_query("select songs_singers.*,songs_cats.name as cat_name from songs_singers,songs_cats where songs_cats.id = songs_singers.cat order by songs_singers.last_update desc limit 200") ;


while($data = db_fetch($qr)){

  print "  <item>
        <title><![CDATA[".$data["name"]."]]></title>
        <pubDate>".date("d M Y H:i:s",$data['last_update'])."</pubDate> 
         <description><![CDATA[ <img align=center src=\"".iif(!strchr($data['img'],"://"),$scripturl."/").get_image($data['img'])."\"><br>".get_date($data['last_update'])."]]></description>";

                print "
        <link>".htmlspecialchars($scripturl."/".singer_url($data['id'],$data['page_name'],$data['name']))."</link>
        <category><![CDATA[$data[cat_name]]]></category>
     </item>\n";
     }
}     
//------------------------------- songs -------------------------------------------
if($op=="songs"){

$qr=db_query("select songs_songs.*,songs_singers.name as singer_name from songs_songs,songs_singers where songs_singers.id = songs_songs.album order by songs_songs.id desc limit 200") ;


while($data = db_fetch($qr)){

  print "  <item>
        <title><![CDATA[".$data['singer_name'] . " - " . $data["name"]."]]></title>
        <pubDate>".date("d M Y H:i:s",$data['date'])."</pubDate>
        <link>".htmlspecialchars($scripturl."/".str_replace(array('{cat}','{id}'),array($settings['default_url_id'],$data['id']),$links['song_listen']))."</link>
        <category><![CDATA[$data[singer_name]]]></category>
     </item>\n";
     }

}
//--------------------- albums ----------------------

if($op=="albums"){

$qr=db_query("select songs_albums.*,b.name as singer_name,b.page_name as singer_page_name  from songs_albums,songs_singers b where b.id = songs_albums.cat order by songs_albums.id desc limit 200") ;


while($data = db_fetch($qr)){

  print "  <item>
        <title><![CDATA[".$data['singer_name'] . " - " . $data["name"]."]]></title>
        <pubDate>".date("d M Y H:i:s",$data['date'])."</pubDate>
        <description><![CDATA[ <img align=center src=\"".iif(!strchr($data['img'],"://"),$scripturl."/").get_image($data['img'])."\">]]></description>
        <link>".htmlspecialchars($scripturl."/".album_url($data['id'],$data['page_name'],$data['name'],$data['cat'],$data['singer_page_name'],$data['singer_name']))."</link>
        <category><![CDATA[$data[singer_name]]]></category>
     </item>\n";
     }

    }
    
    
//--------------------- videos ----------------------

if($op=="videos"){

$qr=db_query("select songs_videos_data.*,songs_videos_cats.name as cat_name from songs_videos_data,songs_videos_cats where songs_videos_cats.id = songs_videos_data.cat order by songs_videos_data.id desc limit 200") ;


while($data = db_fetch($qr)){

  print "  <item>
        <title><![CDATA[".$data['cat_name'] . " - " . $data["name"]."]]></title>
        <pubDate>".date("d M Y H:i:s",$data['date'])."</pubDate>
        <description><![CDATA[ <img align=center src=\"".iif(!strchr($data['img'],"://"),$scripturl."/").get_image($data['img'])."\">]]></description>
        <link>".htmlspecialchars($scripturl."/".str_replace("{id}",$data['id'],$links['video_watch']))."</link>
        <media:content url=\"".iif(!strchr($data['url'],"://"),$scripturl."/")."$data[url]\" medium=\"video\" />
        <media:title>".$data['cat_name'] . " - " . $data["name"]."</media:title>
        <media:thumbnail url=\"".iif(!strchr($data['img'],"://"),$scripturl."/").get_image($data['img'])."\" />
        <category><![CDATA[$data[cat_name]]]></category>
     </item>\n";
     }

    }
    
    
//--------------------- photos ----------------------

if($op=="photos"){

$qr=db_query("select songs_singers_photos.*,b.name as singer_name  from songs_singers_photos,songs_singers b where b.id = songs_singers_photos.cat order by songs_singers_photos.id desc limit 200") ;


while($data = db_fetch($qr)){

  print "  <item>
        <title>Photo $data[id] - $data[singer_name]</title>
        <pubDate>".date("d M Y H:i:s",$data['date'])."</pubDate> 
         <description><![CDATA[ <img align=center src=\"".iif(!strchr($data['thumb'],"://"),$scripturl."/").get_image($data['thumb'])."\">]]></description>    
        <link>".htmlspecialchars($scripturl."/".str_replace("{id}",$data['id'],$links['singer_photo']))."</link> 
        <media:content url=\"".iif(!strchr($data['img'],"://"),$scripturl."/")."$data[img]\" medium=\"image\" />
        <media:title>Photo $data[id] - $data[singer_name]</media:title>
        <media:thumbnail url=\"".iif(!strchr($data['thumb'],"://"),$scripturl."/").get_image($data['thumb'])."\" /> 
        <category><![CDATA[$data[singer_name]]]></category>
     </item>\n";
     }

    }
    
//--------------------- news ----------------------

if($op=="news"){

$qr=db_query("select *  from songs_news order by id desc limit 200") ;


while($data = db_fetch($qr)){

  print "  <item>
        <title><![CDATA[".$data["title"]."]]></title>    
        <pubDate>".date("d M Y H:i:s",$data['date'])."</pubDate>
        <description><![CDATA[ <img align=center src=\"".iif(!strchr($data['img'],"://"),$scripturl."/").get_image($data['img'])."\"> <br> $data[content] ]]></description>
        <link>".htmlspecialchars($scripturl."/".str_replace("{id}",$data['id'],$links['news_details']))."</link>
     </item>\n";
     }

    }
//---------------------------------------------------------
print "</channel>
</rss>";