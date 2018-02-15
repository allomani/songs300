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

set_meta_values(); 
run_template("page_head");

$id= (int) $id;
$cat = iif($cat,intval($cat),1); 

$qr=db_query("select * from songs_songs where id='$id'");


if(db_num($qr)){
    
if(song_listen_permission($id,$cat)){  
   
    
$data = db_fetch($qr);

$url = $data['url_'.$cat];

if($url){
db_query("update songs_songs set listens_".$cat."=listens_".$cat."+1 where id='$id'");


//$data_singer = db_qr_fetch("select * from songs_singers where id='$data[album]'");

  //       $data_cat = db_qr_fetch("select * from songs_cats where id='$data_singer[cat]'");


     run_template('song_listen');

   
}else{
open_table();
print "<center> $phrases[file_is_not_available]</center>";
close_table();      
}
}else{
open_table();
print "<center> $phrases[please_login_first]</center>";
close_table();
}
}else{
open_table();
print "<center> $phrases[err_wrong_url]</center>";
close_table();    
}
?>
