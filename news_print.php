<?
include "global.php";


  $id = intval($id);

       $qr = db_query("select * from songs_news where id='$id'");
              if(db_num($qr)){
              $data = db_fetch($qr);

 run_template('browse_news_print');
          


     }else{

     print "<center>$phrases[err_wrong_url]</center>";

             }
         
