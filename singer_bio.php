<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//---------------------------------------------------------

   $qr=db_query("select * from songs_singers where id='$id'");   
     

         unset($data_singer);
        
         
         $data = db_fetch($qr);

          db_query("update songs_singers set views=views+1 where id='$id'");   
         $data_singer = $data;
 
         $data_cat = db_qr_fetch("select * from songs_cats where id='$data[cat]'");

   print "<span class='path'> <img src='images/arrw.gif' border=0> <a class=path_link href=\"".cat_url($data_cat['id'],$data_cat['page_name'],$data_cat['name'])."\" title=\"$data_cat[name]\">$data_cat[name]</a> $phrases[path_sep] <a class=path_link href=\"".singer_url($data['id'],$data['page_name'],$data['name'])."\" title=\"$data[name]\">$data[name]</a> $phrases[path_sep] $phrases[bio]";

   print"<br><br> </span>" ;

 
 compile_hook('songs_after_path_links');
   run_template('singer_info_table');  
compile_hook('songs_after_singer_table');


open_table($phrases['bio']);
print $data_singer['details'];
close_table();

//---------------------------------------------
require(CWD . "/includes/framework_end.php"); 
?>