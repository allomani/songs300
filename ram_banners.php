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

define("IS_RAM_BANNERS_FILE",1);
require("global.php") ;

$qr = db_query("select * from songs_banners where type='listen' and active=1 and (start_date <= ".time()." or start_date=0) and (expire_date > ".time()." or expire_date=0) order by ord");

while($data = db_fetch($qr)){
   
db_query("update songs_banners set views=views+1 where id=$data[id]");
if($data['c_type']=="code"){
run_php($data['content']);
    }else{
print "<center><a href='banner.php?id=$data[id]' target=_blank title=\"$data[title]\"><img src=\"$data[img]\" border=0 alt=\"$data[title]\"></a><br></center>";
}
}
?>
