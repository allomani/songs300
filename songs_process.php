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

include "global.php" ;
$id = intval($id);

if(is_array($song_id)){

foreach($song_id as $id){
        $id = intval($id);
$data = db_qr_fetch("select url from songs_urls_data where song_id='$id' and cat='1'");

if (strchr($data['url'],"http://")) {
           $file_url = $data['url'] ;
           }else{
  $file_url = "$scripturl/".$data['url'];
        }

$cont .= $file_url."
" ;


        }
}

header("Content-type: audio/x-pn-realaudio");
header("Content-Disposition:  filename=list.ram");
header("Content-Description: PHP Generated Data");
 print $cont ;
