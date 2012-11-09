<?
  if(!defined('IS_ADMIN')){die('No Access');}


if($action=="tools"){
    if_admin();
    print "<p align=center class='title'>$phrases[tools]</p>";
    
    print "<center>
    <table width=60% class=grid><tr><td align=center>";
        print "<form action='index.php' method=post>
    <input type='hidden' name='action' value='update_counters_ok'>
    <input type='submit' value='$phrases[update_counters]'>
    </form>";
print "</td></tr></table></center>";
}

//---------- counters ------------- 

if($action=="update_counters_ok"){
    if_admin();
    
    print "<p align=center class='title'>$phrases[update_counters]</p>";
    
    //------ singers counters ----------
    $qr = db_query("select id,name from songs_singers");
    while($data=db_fetch($qr)){
        update_singer_counters($data['id'],'songs');
        update_singer_counters($data['id'],'albums');
        update_singer_counters($data['id'],'videos');
        update_singer_counters($data['id'],'photos');
        print "$data[name] .. $phrases[done] <br>";
        
    }
   
   print "<br><br><font size=3> $phrases[process_done_successfully] </font> ";
}

//----------------------------