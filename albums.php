<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//---------------------------------------------------------
$year = (int) $year;

$qr = db_query("select year from songs_albums where year > 0 group by year order by year desc");
if(db_num($qr)){
open_table();                                     
print "<center><a href=\"".$links['albums_page']."\" class='big'>$phrases[all]</a> &nbsp;&nbsp;";
while($data = db_fetch($qr)){
    print "<a href=\"".str_replace("{year}",$data['year'],$links['albums_page_w_year'])."\" class='big'>$data[year]</a> &nbsp;&nbsp;";
}
print "</center>";
close_table();
}


//-----------
$start = (int) $start;
$perpage = $settings['albums_perpage'];
$page_string = str_replace("{year}",$year,$links['albums_page_w_pages']);
//---------------

$qr = db_query("select songs_albums.*,songs_singers.name as singer_name , songs_singers.id as singer_id , songs_singers.page_name as singer_page_name from songs_albums,songs_singers where songs_singers.id = songs_albums.cat ".iif($year,"and songs_albums.year like '$year'")." order by songs_albums.year desc,songs_albums.id desc limit $start,$perpage");
if(db_num($qr)){
$page_result = db_qr_fetch("select count(*) as count from songs_albums ".iif($year," where year like '$year'"));
 
 $last_year = -1;
 
 open_table();   
  //    print "<table width=100%><tr>";
$c=0 ;
   while($data_album = db_fetch($qr)){
 
 if($last_year != $data_album['year']){
     if($last_year != -1){
     print "</tr></table>";
     }
     print "<span class=title>".iif($data_album['year'],$data_album['year'],$phrases['other'])."</span>
     <hr class='separate_line' size=1>";
     print "<table width=100%><tr>";   
     $last_year = $data_album['year'];
     $c=0;
 }
   
    if ($c==$settings['songs_cells']) {
print "  </tr><tr>" ;
$c = 0 ;
}
 ++$c ;
 

       print "<td>";
          run_template('browse_albums'); 
          print "</td>";
              
   }
   
         print "</tr></table>";  
         close_table(); 
         
 print_pages_links($start,$page_result['count'],$perpage,$page_string);
 
}else{
    open_table();
     print "<center> $phrases[no_albums] </center>"; 
    close_table();
}
    

//---------------------------------------------
require(CWD . "/includes/framework_end.php"); 