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

include "global.php";


  $id = intval($id);

       $qr = db_query("select * from songs_news where id='$id'");
              if(db_num($qr)){
              $data = db_fetch($qr);

 run_template('browse_news_print');
          


     }else{

     print "<center>$phrases[err_wrong_url]</center>";

             }
         
