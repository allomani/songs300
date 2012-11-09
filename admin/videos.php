<?
if(!defined('IS_ADMIN')){die('No Access');}  

//---------------------------------- Videos Cats -----------------------------
if($action=="videos_cats" ||  $action=="videos_cat_del" || $action=="videos_cat_edit_ok" || 
$action=="videos_cat_add_ok" || $action=="videos" || $action=="video_add_ok" || $action=="video_edit_ok" || 
$action=="video_del" || $action=="videos_cats_enable" || $action=="videos_cats_disable" || $action=="video_move_ok" || $action=="videos_cat_move_ok"){

    $cat = (int) $cat;
    
//if_videos_cat_admin($cat);


print_admin_videos_path($cat);



 //--------- enable / disable cat ------------
 if($action=="videos_cats_disable"){
     if_videos_cat_admin($id); 
        db_query("update songs_videos_cats set active=0 where id='$id'");
        }

if($action=="videos_cats_enable"){
        if_videos_cat_admin($id); 
       db_query("update songs_videos_cats set active=1 where id='$id'");
        }  
//--------------------Cat Add--------------------------------
if($action =="videos_cat_add_ok"){
    if_videos_cat_admin($cat,false);
    
$ord_dt = db_qr_fetch("select max(ord) as max from songs_videos_cats where cat='$cat' limit 1");
$ord = intval($ord_dt['max'])+1;


  db_query("insert into songs_videos_cats (name,cat,img,active,download_limit,page_title,page_description,page_keywords,ord) values('".db_escape($name)."','$cat','".db_escape($img)."','1','".intval($download_limit)."','".db_escape($page_title)."','".db_escape($page_description)."','".db_escape($page_keywords)."','$ord')");
    
$new_id = mysql_insert_id();
  $path = get_videos_cat_path_str($new_id);  
  db_query("update songs_videos_cats set path='$path' where id='$new_id'"); 
  
}
//-----------------Cat Del----------------------------------
 if($action=="videos_cat_del"){
$id = (array) $id;

foreach($id as $iid){
  $delete_array = get_videos_cats($iid);
  
  foreach($delete_array as $id_del){
     if_videos_cat_admin($id_del); 
  } 
                 
     $qrv = db_query("select id,cat from songs_videos_data where cat IN (".implode(",",$delete_array).")");
     while($datav = db_fetch($qrv)){
     delete_video($datav['id'],$datav['cat']);
     }
     
     db_query("delete from songs_videos_cats where id IN (".implode(",",$delete_array).")");


     }
 
 }
//--------------------Cat Edit--------------------------------
 if($action=="videos_cat_edit_ok"){
 if_videos_cat_admin($id); 
 
   if(if_admin("",true)){
 $users_str = @implode(',',(array) $user_id);
 $update_cat_users=true;  
 }else{
     $users_str= "";
     $update_cat_users=false; 
 }
 
 
 db_query("update songs_videos_cats set name='".db_escape($name)."',img='".db_escape($img)."',download_limit='".intval($download_limit)."',page_title='".db_escape($page_title)."',page_description='".db_escape($page_description)."',page_keywords='".db_escape($page_keywords)."'".iif($update_cat_users,",`users`='".db_escape($users_str)."'")." where id='$id'");
         }
         
  //------- cats move ------
if($action=="videos_cat_move_ok"){
 if_videos_cat_admin($cat,false);
 //if_videos_cat_admin($cat_from,false);
 
$qr_to =  db_qr_num("select id from songs_videos_cats where id='$cat'");
if($cat==0){$qr_to=1;}

 if($qr_to > 0){
     if(is_array($id)){
     
        foreach($id as $idx){
            if_videos_cat_admin($idx); 
        }
          
    foreach($id as $idx){
            db_query("update songs_videos_cats set cat='$cat' where id='$idx'");
            
            //---- update paths -----//
            $subcats = get_videos_cats($idx);
            foreach($subcats as $sub_id){
            $path = get_videos_cat_path_str($sub_id);
            db_query("update songs_videos_cats set path='$path' where id='$sub_id'"); 
            } 
    }  
           
     }else{
          print_admin_table("$phrases[err_cats_not_selected]");   
     }
      
         }else{
       print_admin_table("$phrases[err_invalid_cat_id]");
        }
    }
 //---------------- video move ----------------------------
if($action=="video_move_ok"){
if_videos_cat_admin($cat_from);
if_videos_cat_admin($cat);

$qr_to =  db_qr_num("select id from songs_videos_cats where id='$cat'");

 if($qr_to > 0){
     $id = (array) $id;
    
    foreach($id as $iid){
            db_query("update songs_videos_data set cat='$cat' where id='$iid' and cat='$cat_from'");
    }
     

         }else{
       print_admin_table("<center><b>$phrases[err_invalid_cat_id] </center>");
        }
   }
 //-------------------------Video Add------------------------------
if($action=="video_add_ok"){  
if_videos_cat_admin($cat);  

for($i=0;$i<count($name);$i++){
$name[$i] = trim($name[$i]);

if($name[$i]){
db_query("insert into songs_videos_data (name,url,img,cat,date) values('".db_escape($name[$i])."','".db_escape($url[$i])."','".db_escape($img[$i])."','$cat','".time()."')");
  
/*
//---- tags ----//
   $inserted_id = mysql_insert_id();
   for($x=0;$x<count($tags[$i]);$x++){
            $aid = $tags[$i][$x];
            db_query("insert into songs_videos_tags (video_id,".iif(is_numeric($aid),"singer_id","name").") values('".$inserted_id."','".db_escape($aid)."')");  
        }
 //------------//   */
}
     
    }
}
//------------------------Video Del------------------------------
if($action=="video_del"){
if_videos_cat_admin($cat);  

$id = (array) $id;    
foreach($id as $iid){
delete_video($iid,$cat);
}
 }
//-----------------------Video Edit-------------------------
if($action=="video_edit_ok"){
if_videos_cat_admin($cat);  

$id = (array) $id;
for($i=0;$i<count($id);$i++){
db_query("update songs_videos_data set name='".db_escape($name[$i])."',img='".db_escape($img[$i])."',url='".db_escape($url[$i])."' where id='".$id[$i]."' and cat='$cat'");

/*
//------- tags ------
db_query("delete from songs_videos_tags where video_id='".$id[$i]."'");  
        for($x=0;$x<count($tags[$i]);$x++){
            $aid = $tags[$i][$x];
            db_query("insert into songs_videos_tags (video_id,".iif(is_numeric($aid),"singer_id","name").") values('".$id[$i]."','".db_escape($aid)."')");  
        }
//------------------  */

/*
if($this_song_only[$i]){
    db_query("update songs_songs set video_id = 0 where video_id='".$id[$i]."'");
}
 
 if($song_id[$i]){
     db_query("update songs_songs set video_id='".$id[$i]."' where id='".$song_id[$i]."'");
 }  */
        
        
}
}        
//-----------------------------------------------------------
        

//-------- List Cats ---------//
 print "<p align=$global_align><a href='index.php?action=videos_cat_add&cat=$cat'><img src='images/add.gif' border=0> $phrases[add_cat]</a></p>";   

 /*  if($user_info['groupid'] != 1){
$usr_data2 = db_qr_fetch("select permisions_videos from songs_user where id='$user_info[id]'");

if($usr_data2['permisions_videos']){
    $qr=db_query("select * from songs_videos_cats where id IN ($usr_data2[permisions_videos]) and cat='$cat' order by ord ASC");
    }

     }else{   */

       $qr = db_query("select * from songs_videos_cats where cat='$cat' order by ord asc");
    //  }
      

 if(db_num($qr)){
 print "<center>
 <p class=title>$phrases[the_cats]</p>
 <form action='index.php' method='post' name='cats_form'>
<input type=hidden name='cat' value='$cat'>
<table width=80% class=grid><tr><td>

<div id=\"videos_cats_list\" >";

 while($data = db_fetch($qr)){
     
      if($tr_class == "row_1"){
         $tr_class = "row_2";
     }else{
         $tr_class = "row_1";
     }
     
     
      print "<div id=\"item_{$data['id']}\" class='$tr_class'>
      <table width=100%><tr>

      <td width=2>
      <input type=checkbox name=id[] value='$data[id]'  onclick=\"set_checked_color('item_{$data['id']}',this,'$tr_class')\">
      </td>
      <td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img title='$phrases[click_and_drag_to_change_order]' src='images/move.gif'></span> 
      </td>
      
      <td>
      
      <a href='index.php?action=videos&cat=$data[id]'>$data[name]</a></td>
      <td width=200>";
      if($data['active']){
       print "<a href='index.php?action=videos_cats_disable&id=$data[id]'>$phrases[disable]</a> - " ;
}else{
     print "<a href='index.php?action=videos_cats_enable&id=$data[id]'>$phrases[enable]</a> - " ;
           }
      print "<a href='index.php?action=videos_cat_edit&id=$data[id]&cat=$cat'>$phrases[edit] </a> - <a href=\"index.php?action=videos_cat_del&id=$data[id]&cat=$cat\" onClick=\"return confirm('$phrases[del_video_cat_warning]');\">$phrases[delete]</a></td>
      </tr></table></div>";

         }
       print "
       </div>
       
       
       
          <table width=100%><tr>
          <td width=2><img src='images/arrow_".$global_dir.".gif'></td>   
          <td>

          <a href='#' onclick=\"CheckAll('cats_form'); return false;\"> $phrases[select_all] </a> -
          <a href='#' onclick=\"UncheckAll('cats_form'); return false;\">$phrases[select_none] </a> 
          &nbsp;&nbsp; 
          <select name=action>
         
          <option value='videos_cat_move'>$phrases[move]</option>
           <option value='videos_cat_del'>$phrases[delete]</option>  
          </select>
           &nbsp;&nbsp;
           <input type=submit value=' $phrases[do_button] ' onClick=\"return confirm('".$phrases['are_you_sure']."');\">
          </td></tr></table>
          
        
          </td></tr>
          
          </table> </center><br> 
          
          </form>  
       
       <script type=\"text/javascript\">
        init_sortlist('videos_cats_list','set_videos_cats_sort');
</script>";
print "<br><hr width=90% class='separate_line' size=\"1\"><br>";   
       
 }else{
     $no_cats = true;
 }
//------------------------//

   
 if($cat > 0){
 print "<img src=\"images/add.gif\">&nbsp;<a href=\"index.php?action=video_add&cat=$cat\">$phrases[add_videos]</a><br><br>";
 }

    //------------ show videos ------------------//
      $qr=db_query("select * from songs_videos_data where cat='$cat' order by $settings[videos_orderby] $settings[videos_sort]");

      
      if(db_num($qr)){
            print "
    <center>
      <form action='index.php' method='post' name='submit_form'>
      <input type='hidden' name='cat' value='$cat'>
      
      <table class=grid width=90%>" ; 
            $i=0;
           while($data = db_fetch($qr)){
               
                if($tr_class == "row_1"){
         $tr_class = "row_2";
     }else{
         $tr_class = "row_1";
     }
     
     
                print "<tr class='$tr_class' id='video_item_{$i}'>
                      <td width=10><input type='checkbox' name=\"id[$i]\" value=\"$data[id]\" onClick=\"set_checked_color('video_item_{$i}',this,'$tr_class');\"></td>       
                      <td>$data[name]</td>
                <td align='$global_align_x'><a href='index.php?action=video_edit&id=$data[id]&cat=$cat'>$phrases[edit] </a> - 
               
                <a href='index.php?action=video_del&id=$data[id]&cat=$cat' onClick=\"return confirm('$phrases[are_you_sure]');\"> $phrases[delete] </a></td></tr>";

                $i++;
                   }

                 print "
                 </table><br>
     <table class=grid width=90%>            
     <tr><td width=2><img src='images/arrow_".$global_dir.".gif'></td>
          <td width=100%>


          <a href='#' onclick=\"CheckAll(); return false;\"> $phrases[select_all] </a> -
          <a href='#' onclick=\"UncheckAll(); return false;\">$phrases[select_none] </a>
        &nbsp;&nbsp;  
          <select name='action'>
          <option value='video_move'>$phrases[move]</option> 
          <option value='video_edit'>$phrases[edit]</option>
          <option value='video_del'>$phrases[delete]</option>
          </select>
          
          <input type='submit' value=' $phrases[do_button] ' onClick=\"return confirm('$phrases[are_you_sure]');\">
          </form>
         </td></tr>
          </table>";  
              }else{
                if($no_cats){
                      print_admin_table("<center>$phrases[err_no_videos]</center>");
                }
                      }
             

        }
//-------------- Cat Add -----------
if($action=="videos_cat_add"){
    $cat = intval($cat);
  
  if_videos_cat_admin($cat,false); 
  print_admin_videos_path($cat,"$phrases[add_cat]");

  
print "<center><p class=title>$phrases[add_cat] </p>
   <form method=\"POST\" action=\"index.php\" name=sender>

   <table width=60% class=grid><tr>
   <td> <b>$phrases[the_name] </b></td><td>
    <input type=hidden name='action' value='videos_cat_add_ok'>
      <input type=hidden name='cat' value='$cat'>
   <input type=text name=name size=30>
    </td></tr>
       <tr><td>
  <b>$phrases[the_image]</b></td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=img></td><td><a href=\"javascript:uploader('videos','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td></tr>
   <tr> <td>
                <b>$phrases[the_download]</b></td>
                                <td>";
         print_select_row("download_limit",array(0=>$phrases['download_for_all_visitors'],1=>$phrases['download_for_members_only']));
         
                       print "</td></tr>
 </table>";
  //-------------- Tags ------------//                 
                              print " <br><br>
                              <fieldset style=\"width:60%;text-align:$global_align\">
                              <legend><b>$phrases[page_custom_info]</b></legend>
                              <table width=100%>
                              
                              <tr><td><b>$phrases[the_title] : </td><td>
                              <input type=text size=30 name=page_title></td></tr>
                              
                              <tr><td><b>$phrases[the_description] : </td><td>
                              <input type=text size=30 name=page_description></td></tr>
                              
                              <tr><td><b>$phrases[the_page_keywords] : </td><td>
                              <input type=text size=30 name=page_keywords ></td></tr>
                              
                              
                              </table>
                              <br>
                              <font color='#808080'>* $phrases[leave_blank_to_use_default_settings] </font>
                              </fieldset><br>
                              ";
                              print "
        <input type=\"submit\" value=\"$phrases[add_button]\">

    </form>

   </center>";
   
   }
 //------------------------- Cat Edit------------------------
        if($action == "videos_cat_edit"){
        $id = intval($id);
        $cat=intval($cat);
        
        $qr =db_query("select * from songs_videos_cats where id='$id'");
        if(db_num($qr)){
          if_videos_cat_admin($id);
          print_admin_videos_path($id,"$phrases[edit]"); 
           
           $data=db_fetch($qr); 
               print "<center>

                <table border=0 width=\"90%\"  class=grid><tr>

                <form method=\"POST\" action=\"index.php\" name=sender>

                      <input type=hidden name=\"id\" value='$id'>
                      <input type=hidden name=\"cat\" value='$cat'>

                      <input type=hidden name=\"action\" value='videos_cat_edit_ok'> ";


                  print "  <tr>
                                <td width=\"50\">
                <b>$phrases[the_name]</b></td><td width=\"223\">
                <input type=\"text\" name=\"name\" value=\"$data[name]\" size=\"29\"></td>
                        </tr>
                  


                             <tr><td>
  <b>$phrases[the_image]</b></td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=img value='$data[img]'></td><td><a href=\"javascript:uploader('videos','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td></tr>";
       

                              print " <tr> <td>
                <b>$phrases[the_download]</b></td>
                                <td>";
         print_select_row("download_limit",array(0=>$phrases['download_for_all_visitors'],1=>$phrases['download_for_members_only']),$data['download_limit']);
               
                       print "</td></tr>
                                       </table>";
                                 //-------------- Moderators --------------//                      
                       if(if_admin("",true)){
                       
                       print "<br>
                        <table border=0 width=\"90%\"  style=\"border-collapse: collapse\" class=grid>
                        <tr><td><b>$phrases[the_moderators]</b></td>
                       <td>";
                       $users_array = get_videos_cat_users($id);
                           // print_r($users_array);
                    
                       $qro=db_query("select * from songs_user where group_id=2 order by id");
                        if(db_num($qro)){
                       print "<table width=100%><tr>";
                       $c=0;
                       while($datao=db_fetch($qro)){
   if($c==4){
    print "</tr><tr>" ;
    $c=0;
    }
    
                           print "<td><input type=\"checkbox\" name=\"user_id[]\" value=\"$datao[id]\"".iif($users_array[$datao['id']],' checked').iif($users_array[$datao['id']] && $users_array[$datao['id']] !=$id,' disabled').">$datao[username]</td>";
                           $c++;
                       }
                       print "</tr></table>";
                        }else{
                              print " $phrases[no_moderators]";
                        }
                       print "</td></tr>
                       </table><br>";
                       }
                       //-------------- Tags ------------//                 
                              print "    <br>
                              <fieldset style=\"width:90%;text-align:$global_align\">
                              <legend><b>$phrases[page_custom_info]</b></legend>
                              <table width=100%>
                              
                              <tr><td><b>$phrases[the_title] : </td><td>
                              <input type=text size=30 name=page_title value=\"$data[page_title]\"></td></tr>
                              
                              <tr><td><b>$phrases[the_description] : </td><td>
                              <input type=text size=30 name=page_description value=\"$data[page_description]\"></td></tr>
                              
                              <tr><td><b>$phrases[the_page_keywords] : </td><td>
                              <input type=text size=30 name=page_keywords value=\"$data[page_keywords]\"></td></tr>

                              
                              </table>
                              <br>
                              <font color='#808080'>* $phrases[leave_blank_to_use_default_settings] </font>
                              </fieldset><br>
                              ";
                              print "
        <input type=\"submit\" value=\"$phrases[edit]\">
                             



</form>    </center>\n";
}else{
     print_admin_table("<center> $phrases[err_wrong_url]</center>");
     
 }
                      }

//------------------------ Video Edit --------------------------------------
if($action == "video_edit"){

$id = (array) $id;
$cat = (int) $cat;
$id = array_map('intval',$id);
if_videos_cat_admin($cat);

 $qr=db_query("select * from songs_videos_data where id IN (".implode(",",$id).") and cat='$cat'"); 
 
 if(db_num($qr)){

print_admin_videos_path($cat,"$phrases[edit]");  

  /*  print "<script src=\"$scripturl/js/jquery.js\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script>jQuery.noConflict();</script>
    <script src=\"$scripturl/js/jquery.fcbkcomplete.js\" type=\"text/javascript\" charset=\"utf-8\"></script> "; */
    
    
       
    print "<center>" ;
       print "<form name=sender action=index.php method=post>
       <input type=hidden name=action value='video_edit_ok'>
       <input type=hidden name=cat value='$cat'>";
       
  $i=0;      
  while($data = db_fetch($qr)){ 
       
   
      print "
      <input type='hidden' name=\"id[$i]\" value=\"$data[id]\">
      <table class=grid width=99% >
     <tr><td colspan=2><b># ".($i+1)."</b></td></tr>
       <tr><td> $phrases[the_name] : </td><td><input type=text name=\"name[$i]\" size=30 value=\"$data[name]\"></td></tr>
    <tr><td>
  $phrases[the_url] :</td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=\"url[$i]\" value=\"$data[url]\"></td>
  <td><a href=\"javascript:uploader('videos','url[$i]');\"><img src='images/file_up.gif' border=0 title='$phrase[upload_file]'></a></td></tr></table>

   </td></tr>
      
       <tr><td>
  $phrases[the_image] :</td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=\"img[$i]\" value=\"$data[img]\"></td><td><a href=\"javascript:uploader('videos','img[$i]');\"><img src='images/file_up.gif' border=0 title='$phrases[upload_file]'></a></td></tr></table>

   </td></tr> ";
 
      /*
         <tr><td> $phrases[in_this_video] : </td><td><select id=\"tags_$i\" name=\"tags[$i]\">";
    
       $qra = db_query("select songs_singers.* from songs_singers,songs_videos_tags where songs_singers.id=songs_videos_tags.singer_id and songs_videos_tags.video_id='$data[id]' and songs_videos_tags.singer_id > 0 order by songs_singers.name asc");
        while($dataa = db_fetch($qra)){
                print "<option value=\"$dataa[id]\" class='selected'>$dataa[name]</option>";
        }
       
       
        $qra = db_query("select *  from songs_videos_tags where video_id='$data[id]' and singer_id='0' order by name asc");
        while($dataa = db_fetch($qra)){
                print "<option value=\"$dataa[name]\" class='selected'>$dataa[name]</option>";
        }
        
         
         
        print "</select> </td></tr>";  
      
   $video_song_id = valueof(db_qr_fetch("select id from songs_songs where video_id='$data[id]'"),"id");
      
      print "<tr><td>
 
        $phrases[song_id] : </td>
 <td><input type=text name=\"song_id[$i]\" id=\"song_id[$i]\" size=4 value=\"$video_song_id\" onBlur=\"get_song_name('song_name_{$i}',this.value);\">
 <input type=\"checkbox\" name=\"this_song_only[$i]\" value=1> $phrases[this_song_only]
        <div id='song_name_{$i}'></div>
        <div id='song_name_{$i}_loading'><img src='images/loading.gif' style='display:none;'></div> 
        ";
        if($video_song_id){
       print "
        <script>
        get_song_name('song_name_{$i}',$video_song_id);
        </script>";
        }
print "</td></tr> */
      print " </table><br>

  
  ";
       $i++;
 }
 print "
  
 <input type=submit value='$phrases[edit]'></form></center><br>";
 
    /*
 //----- tags js ------------       
  print "<script language=\"JavaScript\">

        jQuery(document).ready(function() 
        {";        
       
          for ($i=0;$i<count($id);$i++){
         
          print "
          jQuery(\"#tags_$i\").fcbkcomplete({
            json_url: \"ajax.php?action=get_singers_json\",
            cache: false,
            filter_case: false,
            filter_hide: true,
            firstselected: false,
            filter_selected: true,
            newel: true        
          });"; 
        
          }
         print " });  
    </script>";     */
  //---------------------------
 }else{
     print_admin_table("<center> $phrases[err_wrong_url]</center>");
     
 }
        }
        
        
//--------------------- Video Add ---------------------------
if($action=="video_add"){

if_videos_cat_admin($cat);  
print_admin_videos_path($cat,"$phrases[add_videos]"); 
    
 if(!$add_limit){
$add_limit = $settings['songs_add_limit'] ;
  }
  
  $add_limit = intval($add_limit);
  
  
     /*   print "<script src=\"$scripturl/js/jquery.js\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script src=\"$scripturl/js/jquery.fcbkcomplete.js\" type=\"text/javascript\" charset=\"utf-8\"></script>  */
    
print "<center>

<form method=\"POST\" action=\"index.php\">

      <input type=\"hidden\" name=\"cat\" value='$cat'>
      <input type=hidden name=action value='video_add'>
      <table width=30% class=grid>
      <tr><td align=center> $phrases[fields_count]  : <input type=text name='add_limit' value='$add_limit' size=3>
      &nbsp;&nbsp;<input type=submit value='$phrases[edit]'></td></tr></table></form>

      <br>
      
      
      <center>
       <form name=sender action=index.php method=post>
       <input type=hidden name=action value='video_add_ok'>
       <input type=hidden name=cat value='$cat'>";

      
       
           for ($i=0;$i<$add_limit;$i++){
        print "<table width=80% class=grid>  
        <tr><td colspan=2><b> #".($i+1)." </b></td>
        <tr>
       <tr><td> $phrases[the_name] : </td><td><input type=text name=\"name[$i]\" size=30></td></tr>
       <tr><td> $phrases[the_url] : </td><td>

       <table><tr><td><input type=text  dir=ltr size=30 name=\"url[$i]\"></td><td><a href=\"javascript:uploader('videos','url[$i]');\"><img src='images/file_up.gif' border=0 title='$phrases[upload_file]'></a></td></tr></table>
       </td></tr>
      <tr><td>
  $phrases[the_image] :</td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=\"img[$i]\"></td><td><a href=\"javascript:uploader('videos','img[$i]');\"><img src='images/file_up.gif' border=0 title='$phrases[upload_file]'></a></td></tr></table>

   </td></tr>  </table><br>";
   /*
     <tr><td> $phrases[in_this_video] : </td><td><select id=\"tags_$i\" name=\"tags[$i]\">
        </select></td></tr> </table><br>";  */   
     }
        
    print "<input type=submit value='$phrases[add_button]'>
      </form><br>";
       
       
    /*   //----- tags js ------------       
  print "<script language=\"JavaScript\">
  jQuery.noConflict();

        jQuery(document).ready(function() 
        {";        
       
          for ($i=0;$i<$add_limit;$i++){
         
          print "
          jQuery(\"#tags_$i\").fcbkcomplete({
            json_url: \"ajax.php?action=get_singers_json\",
            cache: false,
            filter_case: false,
            filter_hide: true,
            firstselected: false,
            filter_selected: true,
            newel: true        
          });"; 
        
          }
         print " });  
    </script>";     */
}


 //----------------- video Move -------
if($action == "video_move"){
    
$cat = intval($cat);


   $id = (array) $id;
   
 if(count($id)){

 print "<form action=index.php method=post name=sender>
 <input type=hidden name=action value='video_move_ok'>
 <input type=hidden name=cat_from value='$cat'>
 <input type=hidden name=confirm value='1'>
 <center><table width=60% class=grid><tr><td colspan=2><b> $phrases[move_from] : </b>";

//-----------------------------------------
$data_from['cat'] = $cat ;
while($data_from['cat']>0){
   $data_from = db_qr_fetch("select name,id,cat from songs_videos_cats where id='$data_from[cat]'");

        $data_from_txt = "$data_from[name] / ". $data_from_txt  ;

        }
   print "$data_from_txt";
//------------------------------------------

 print "</td></tr>";
 $c = 1 ;
foreach($id as $iid){
    $iid = (int) $iid;
$data_video=db_qr_fetch("select name from songs_videos_data where id='$iid'");
  print "<input type=hidden name=id[] value='$iid'>";
        print "<tr><td width=2><b>$c</b></td><td>$data_video[name]</td></tr>"  ;
        ++$c;
        }
  print "<tr><td colspan=2><b>$phrases[move_to] : </b><select name=cat>";
       $qr = db_query("select * from songs_videos_cats where id !='$cat' order by cat,ord, name asc");
   
    while($data=db_fetch($qr)){
    
   
        //-------------------------------
        $dir_content = "";
        $dir_data['cat'] = $data['cat'] ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from songs_videos_cats where id=$dir_data[cat]");

        $dir_content = "$dir_data[name] -> ". $dir_content  ;
        }
      $data['full_name'] = $dir_content .$data['name'];      
     //---------------------------------------
        
       print "<option value='$data[id]'>$data[full_name]</option>";   
    
    }
   
    
  print "</select>
  </td></tr>
 <tr><td colspan=2 align=center><input type=submit value=' $phrases[move] '></td></tr>
 </table>";
        }else{
                print_admin_table("<center>  $phrases[please_select_videos] </center>");
                }
        }

        
 //----------------- cat move ---------------------------
if($action == "videos_cat_move"){ 

$cat = intval($cat);

//if_products_cat_admin($cat,false);  

$id = (array) $id;
 if(count($id)){

 print "<form action=index.php method=post name=sender>
 <input type=hidden name=action value='videos_cat_move_ok'>
 <input type=hidden name=from_cat value='$cat'>
 <center><table width=60% class=grid><tr><td colspan=2><b> $phrases[move_from] : </b>";

//-----------------------------------------
$data_from['cat'] = $cat ;
while($data_from['cat']>0){
   $data_from = db_qr_fetch("select name,id,cat from songs_videos_cats where id='$data_from[cat]'");

  
        $data_from_txt = "$data_from[name] / ". $data_from_txt  ;
 
        }
   print "$data_from_txt";
//------------------------------------------

 print "</td></tr>";
 $c = 1 ;
foreach($id as $idx){
 
$data=db_qr_fetch("select name from songs_videos_cats where id='$idx'");
  print "<input type=hidden name=id[] value='$idx'>";
        print "<tr><td width=2><b>$c</b></td><td>$data[name]</td></tr>"  ;
        ++$c;
        $sql_ids[] = $idx;
        }
 print "<tr><td colspan=2><b>$phrases[move_to] : </b><select name=cat>".
 iif($cat != 0,"<option value='0'>$phrases[without_main_cat]</option>");
       $qr = db_query("select * from songs_videos_cats where id !='$cat' and id not IN(".implode($sql_ids).") order by cat,ord, name asc");
   
    while($data=db_fetch($qr)){
    
    $skip=0;    
    foreach($sql_ids as $par_id){
    $paths = explode(",",$data['path']);
    $indx = array_search($par_id,$paths);
    if($indx){
    $skip=1; 
    }
    }
    
    if(!$skip){
        //-------------------------------
        $dir_content = "";
        $dir_data['cat'] = $data['cat'] ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from songs_videos_cats where id=$dir_data[cat]");

        $dir_content = "$dir_data[name] -> ". $dir_content  ;
        }
      $data['full_name'] = $dir_content .$data['name'];      
     //---------------------------------------
        
       print "<option value='$data[id]'>$data[full_name]</option>";   
    } 
    }
   
    
  print "</select>
  </td></tr>
 <tr><td colspan=2 align=center><input type=submit value=' $phrases[move_the_cats] '></td></tr>
 </table>";
 }else{
                print "<center>  $phrases[please_select_cats_first] </center>";
                }
        } 