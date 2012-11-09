<?
define("IS_DOWNLOAD_FILE",1);
require("global.php");

$id = intval($id);            
$cat = iif($cat,intval($cat),1);



if($action == "video"){
$qr=db_query("select id,url from songs_videos_data where id='$id'");
}else{
$qr=db_query("select * from songs_songs where id='$id'");
}


if (db_num($qr)){
 $data=db_fetch($qr);

//=============== Videos =================//
if($action=="video"){
//-------- Check Permission -------//
if(video_download_permission($id)){ 
//-------- Video Watch ------------//
if($op=="watch"){
db_query("update songs_videos_data set views=views+1 where id='$id'");

header("Content-type: audio/x-pn-realaudio");
header("Content-Disposition:  filename=listen.ram");
header("Content-Description: PHP Generated Data");

   if (strchr($data['url'],"http://")) {
   print $data['url'];
           }else{
  print $scripturl."/".$data['url'];
        }
//--------- video download -----------//        
          }else{
         db_query("update songs_videos_data set downloads=downloads+1 where id='$id'");
         if (strchr($data['url'],"http://")) {
           header("Location: $data[url]");
            }else{
             header("Location: $scripturl/$data[url]");
                    }

                    }
//--------- Redirect -----------//                    
}else{
    login_redirect(true);
}                   
//================ Songs ==================//
}else{
    
$url = $data['url_'.$cat];   

if($url){ 

//----------- Song Listen ----------//
if ($op == "listen"){
  if(song_listen_permission($id,$cat)){   
db_query("update songs_songs set listens_".$cat."=listens_".$cat."+1 where id='$id'");

if(!$handler){$handler='';}
$player_data = get_player_data($url); 
$url = iif(!strchr($url,"://"),$scripturl."/".$url,$url); 
$content = str_replace("{url}",$url,$player_data['ext_content']);



header("Content-type: ".$player_data['ext_mime']);
header("Content-Disposition:  filename=".$player_data['ext_filename']);
  
 $num_ramadv_data = db_qr_fetch("select count(id) as count from songs_banners where type='listen' and active=1"); 
 $num_ramadv = intval($num_ramadv_data['count']);
 unset($num_ramadv_data);
//---------
run_php($content);
//--------

}else{
login_redirect(true);
}
//-------------- Song Download ----------//
  }else{   
      if(song_download_permission($id,$cat)){   
        // print("update songs_urls_data set downloads=downloads+1 where song_id='$id' and cat='$cat'"); 
         db_query("update songs_songs set downloads_".$cat."=downloads_".$cat."+1 where id='$id'");
         $url = iif(!strchr($url,"://"),$scripturl."/".$url,$url);  
          header("Location: $url");
         
                    }else{
login_redirect(true);
}
  }
}else{
    print "<center> $phrases[file_is_not_available]</center>";       
}

}
//------------------------------//


}else{
print "<center> $phrases[err_wrong_url] </center>";
}

?>