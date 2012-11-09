<?
if(!defined('IS_ADMIN')){die('No Access');} 


// ---------------------- Songs_exts -----------------------------
if($action=="exts" || $action =="ext_add_ok" || $action=="ext_del" || $action=="edit_ext_ok"){

 if_admin();

if($action =="ext_add_ok"){
  db_query("insert into songs_exts (name) values('".db_escape($name,false)."')");
        }

//----------------------------------------------------------
 if($action=="ext_del"){
 if($id){
      db_query("delete from songs_exts where id='$id'");
         }
 }
//-----------------------------------------------------------
 if($action=="edit_ext_ok"){

 db_query("update songs_exts set name='".db_escape($name,false)."',period_from='".intval($period_from)."',period_to='".intval($period_to)."' where id='$id'");
         }
//-----------------------------------------------------------

 print "<center><p class=title>$phrases[the_songs_exts] </p>
   <form method=\"POST\" action=\"index.php\">

   <table width=45% class=grid><tr>
   <td> $phrases[the_ext] :
    <input type=hidden name='action' value='ext_add_ok'>
   <input type=text name=name size=30>
    </td>
    <td><input type=submit value='$phrases[add_button]'></td>
    </tr></table>



   </center><br>";

 $qr = db_query("select * from  songs_exts");
 print "<center><table width=90% class=grid>
 <tr><td><b>$phrases[the_ext]</b></td><td align=center><b>$phrases[auto]</b></td><td align='$global_align_x'><b>$phrases[the_options]</b></td></tr>";
 while($data = db_fetch($qr)){
     
          
     if($tr_class == "row_1"){
         $tr_class = "row_2";
     }else{
         $tr_class = "row_1";
     }
     
      print "<tr class='$tr_class'><td>$data[name]</td>
      <td align=center>".iif($data['period_from'] || $data['period_to']," $data[period_from] - $data[period_to] $phrases[day]")."</td>
      <td align='$global_align_x'><a href='index.php?action=ext_edit&id=$data[id]'>$phrases[edit] </a> - 
      <a href=\"index.php?action=ext_del&id=$data[id]\" onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
      ";
         }
       print "</table>";




        }

         //-------------------------------------------------------------
        if($action == "ext_edit"){
  
 print "<img src='images/arrw.gif'>&nbsp; <a href='index.php?action=exts'>$phrases[the_songs_exts]</a> / $phrases[edit]</a><br>";

       
               $qr = db_query("select * from songs_exts where id='$id'");
               if(db_num($qr)){
                   $data = db_fetch($qr);
               print "<center>

                <table border=0 width=\"60%\" class=grid><tr>

                <form method=\"POST\" action=\"index.php\">

                      <input type=hidden name=\"id\" value='$id'>

                      <input type=hidden name=\"action\" value='edit_ext_ok'> ";


                  print "  <tr>
                 <td>
                <b>$phrases[the_ext]</b></td><td>
                <input type=\"text\" name=\"name\" value=\"".htmlspecialchars($data['name'])."\" size=\"29\"></td>
                        </tr>
 </table>
        <br>
 <fieldset style='width:60%'>
 <legend><b>$phrases[auto]</b></legend>
 
<table width='100%'>
<tr>
                 <td>
                <b>$phrases[from]</b></td><td>
                <input type=\"text\" name=\"period_from\" value=\"$data[period_from]\" size=\"4\"> $phrases[day] </td>
                        </tr>
                        
                                        <tr>
                 <td>
                <b>$phrases[to]</b></td><td>
                <input type=\"text\" name=\"period_to\" value=\"$data[period_to]\" size=\"4\"> $phrases[day]</td>
                        </tr>
 </table>
 <br>
 <font color='#969696'>$phrases[ext_auto_note]</font>
 </fieldset>
 
                <br>
                <input type=\"submit\" value=\"$phrases[edit]\"> 

</form>    </center>\n";
               }else{
                   print_admin_table("<center>$phrases[err_wrong_url]</center>");
               }
                      }

?>