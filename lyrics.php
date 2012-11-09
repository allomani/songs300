<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//---------------------------------------------------------
compile_hook('lyrics_start'); 

 templates_cache(array('singer_info_table'));   
 
 $qr=db_query("select * from songs_songs where id='$id'");   
if(db_num($qr)){
         db_query("update songs_singers set views=views+1 where id='$data[album]'");
         
         $data = db_fetch($qr);

      
         $data_singer = db_qr_fetch("select * from songs_singers where id='$data[album]'");

         $data_cat = db_qr_fetch("select * from songs_cats where id='$data_singer[cat]'");

   print "<span class='path'> <img src='images/arrw.gif' border=0> <a class=path_link href=\"".cat_url($data_cat['id'],$data_cat['page_name'],$data_cat['name'])."\" title=\"$data_cat[name]\">$data_cat[name]</a> $phrases[path_sep] <a class=path_link href=\"".singer_url($data_singer['id'],$data_singer['page_name'],$data_singer['name'])."\" title=\"$data_singer[name]\">$data_singer[name]</a> $phrases[path_sep] $data[name] ";

   print"<br><br> </span>" ;
  compile_hook('lyrics_after_path_links'); 
  
 
 compile_hook('songs_after_path_links');
   run_template('singer_info_table');  
compile_hook('songs_after_singer_table');


open_table("$data[name]");
print "<center>$data[lyrics]</center>";
close_table();
                
           
}else{
    open_table();
    print "<center> $phrases[err_wrong_url] </center>";
    close_table();
}
     

compile_hook('lyrics_end');  

//---------------------------------------------
require(CWD . "/includes/framework_end.php"); 
?>