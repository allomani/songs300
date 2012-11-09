<?
require("global.php");

require(CWD . "/includes/framework_start.php");   
//----------------------------------------------------

//------------------------- Statics --------------------------
if($action=="statics"){
      $year = intval($year);
$month = intval($month);
 require(CWD . '/includes/functions_statics.php');


 //-------- browser and os statics ---------
if($settings['count_visitors_info']){
open_table("$phrases[operating_systems]");
get_statics_info("select * from info_os where count > 0 order by count DESC","name","count");
close_table();

open_table("$phrases[the_browsers]");
get_statics_info("select * from info_browser where count > 0 order by count DESC","name","count");
close_table();

$printed  = 1 ;
}

//--------- hits statics ----------
if($settings['count_visitors_hits']){
$printed  = 1 ;

if (!$year){$year = date("Y");}

open_table("$phrases[monthly_statics_for] $year ");

for ($i=1;$i <= 12;$i++){

$dot = $year;

if($i < 10){$x="0$i";}else{$x=$i;}


$sql = "select * from info_hits where date like '%-$x-$dot' order by date" ;
$qr_stat=db_query($sql);

if (db_num($qr_stat)){
$total = 0 ;
while($data_stat=db_fetch($qr_stat)){
$total = $total + $data_stat['hits'];
}

$rx[$i-1]=$total  ;

}else{
        $rx[$i-1]=0 ;
        }

  }

    for ($i=0;$i <= 11;$i++){
    $total_all = $total_all + $rx[$i];
         }

         if ($total_all !==0){

         print "<br>";

  $l_size = @getimagesize("images/leftbar.gif");
    $m_size = @getimagesize("images/mainbar.gif");
    $r_size = @getimagesize("images/rightbar.gif");


 echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">";
 for ($i=1;$i <= 12;$i++)  {

    $rs[0] = $rx[$i-1];
    $rs[1] =  substr(100 * $rx[$i-1] / $total_all, 0, 5);
    $title = $i;

    echo "<tr><td>";



   print " $title:</td><td dir=ltr align='$global_align'><img src=\"images/leftbar.gif\" height=\"$l_size[1]\" width=\"$l_size[0]\">";
    print "<img src=\"images/mainbar.gif\"  height=\"$m_size[1]\" width=". $rs[1] * 2 ."><img src=\"images/rightbar.gif\" height=\"$r_size[1]\" width=\"$l_size[0]\">
    </td><td>
    $rs[1] % ($rs[0])</td>
    </tr>\n";

}
print "</table>";
 }else{
        print "<center>$phrases[no_results]</center>";
        }
  print "<br><center>[ $phrases[the_year] : ";
  $yl = date('Y') - 3 ;
  while($yl != date('Y')+1){
      print "<a href='index.php?action=statics&year=$yl'>$yl</a> ";
      $yl++;
      }
  print "]";
close_table();

if (!$month){
        $month =  date("m")."-$year" ;
        }else{
                $month= "$month-$year";
                }

open_table("$phrases[daily_statics_for] $month ");
$dot = $month;
get_statics_info("select * from info_hits where date like '%$dot' order by date","date","hits");

print "<br><center>
          [ $phrases[the_month] :
          <a href='index.php?action=statics&year=$year&month=1'>1</a> -
          <a href='index.php?action=statics&year=$year&month=2'>2</a> -
          <a href='index.php?action=statics&year=$year&month=3'>3</a> -
          <a href='index.php?action=statics&year=$year&month=4'>4</a> -
          <a href='index.php?action=statics&year=$year&month=5'>5</a> -
          <a href='index.php?action=statics&year=$year&month=6'>6</a> -
          <a href='index.php?action=statics&year=$year&month=7'>7</a> -
          <a href='index.php?action=statics&year=$year&month=8'>8</a> -
          <a href='index.php?action=statics&year=$year&month=9'>9</a> -
          <a href='index.php?action=statics&year=$year&month=10'>10</a> -
          <a href='index.php?action=statics&year=$year&month=11'>11</a> -
          <a href='index.php?action=statics&year=$year&month=12'>12</a>
          ]";
          close_table();
}

if(!$printed){
    open_table();
   print "<center>$phrases[no_results]</center>";
    close_table();
    }

        }


 //---------------------------- Pages -------------------------------------
if($action=="pages"){
        $qr = db_query("select * from songs_pages where active=1 and id='".intval($id)."'");

         compile_hook('pages_start');

         if(db_num($qr)){
         $data = db_fetch($qr);
          compile_hook('pages_before_data_table');
         open_table("$data[title]");
          compile_hook('pages_before_data_content');
                  run_php($data['content']);
           compile_hook('pages_after_data_content');
                  close_table();
          compile_hook('pages_after_data_table');
                  }else{
                  open_table();
                          print "<center> $phrases[err_no_page] </center>";
                          close_table();
                          }
             compile_hook('pages_end');
             }
//--------------------- Copyrights ----------------------------------
 if($action=="copyrights"){
     global $global_lang;

     open_table();
if($global_lang=="arabic"){
     print "<center>
     „—Œ’ ·‹ : $_SERVER[HTTP_HOST]   „‰ <a href='http://allomani.com/' target='_blank'>  «··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ… </a> <br><br>

   Ã„Ì⁄ ÕﬁÊﬁ «·»—„Ã… „Õ›ÊŸ…
                        <a target=\"_blank\" href=\"http://allomani.com/\">
                       ··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ…
                        © 2011";
  }else{
       print "<center>
     Licensed for : $_SERVER[HTTP_HOST]   by <a href='http://allomani.com/' target='_blank'>Allomani&trade; Programming Services </a> <br><br>

   <p align=center>
Programmed By <a target=\"_blank\" href=\"http://allomani.com/\"> Allomani&trade; Programming Services </a> © 2011";
      }
     close_table();
         }

//---------------------------- Forget Password -------------------------
 if($action == "forget_pass" || $action=="lostpwd" ||  $action=="rest_pwd"){
     if($action == "forget_pass"){$action="lostpwd";}

        connector_members_rest_pwd($action);
         }
//-------------------------- Resend Active Message ----------------
if($action=="resend_active_msg"){

   $qr = db_query("select * from ".members_table_replace('songs_members') ." where ".members_fields_replace('email')."='".db_clean_string($email)."'",MEMBER_SQL);
   if(db_num($qr)){
           $data = db_fetch($qr) ;
           open_table();
   if(in_array($data[members_fields_replace('usr_group')],$members_connector['allowed_login_groups'])){
    print "<center> $phrases[this_account_already_activated] </center>";
    }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['disallowed_login_groups'])){
            print "<center> $phrases[closed_account_cannot_activate] </center>";
    }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['waiting_conf_login_groups'])){
   snd_email_activation_msg($data[members_fields_replace('id')]);
   print "<center>  $phrases[activation_msg_sent_successfully] </center>";
   }
   close_table();
   }else{
           open_table();
           print "<center>  $phrases[email_not_exists] </center>";
           close_table();
           }
        }
//-------------------------- Active Account ------------------------
if($action == "activate_email"){
        open_table("$phrases[active_account]");
        $qr = db_query("select * from songs_confirmations where code='".db_clean_string($code)."'");
if(db_num($qr)){
$data = db_fetch($qr);

$qr_member=db_query("select ".members_fields_replace('id')." from ".members_table_replace('songs_members') ." where ".members_fields_replace('id')."='$data[cat]'  and ".members_fields_replace('usr_group')."='".$members_connector['waiting_conf_login_groups'][0]."'",MEMBER_SQL);

 if(db_num($qr_member)){
      db_query("update ".members_table_replace('songs_members') ." set ".members_fields_replace('usr_group')."='".$members_connector['allowed_login_groups'][0]."' where ".members_fields_replace('id')."='$data[cat]'",MEMBER_SQL);
      db_query("delete from songs_confirmations where code='".db_clean_string($code)."'");
    print "<center> $phrases[active_acc_succ] </center>" ;
 }else{
      print "<center> $phrases[active_acc_err] </center>" ;
 }
        }else{
      print "<center> $phrases[active_acc_err] </center>" ;
 }
        close_table();
        }

//-------------------------- Confirmations ------------------------
if($action == "confirmations"){
    //----- email change confirmation ------//
if($op=="member_email_change"){
open_table();
$qr=db_query("select * from songs_confirmations where code='".db_clean_string($code)."' and type='".db_clean_string($op)."'");

if(db_num($qr)){
$data = db_fetch($qr);

      db_query("update ".members_table_replace('songs_members')." set ".members_fields_replace('email')."='".$data['new_value']."' where ".members_fields_replace('id')."='$data[cat]'",MEMBER_SQL);
      db_query("delete from songs_confirmations where code='".db_clean_string($code)."'");
    print "<center> $phrases[your_email_changed_successfully] </center>" ;
}else{
     print "<center> $phrases[err_wrong_url] </center>" ;
}
 close_table();
}

        }

//------------------------ Members Login ---------------------------
 if($action=="login"){
 if(@file_exists("login_form.php")){
     include "login_form.php";
 }else{
    $re_link = htmlspecialchars($re_link) ;

         open_table();
print "<script type=\"text/javascript\" src=\"js/md5.js\"></script>

<form method=\"POST\" action=\"login.php\" onsubmit=\"md5hash(password, md5pwd, md5pwd_utf, 1)\">

<input type=hidden name='md5pwd' value=''>
<input type=hidden name='md5pwd_utf' value=''>


<input type=hidden name=action value=login>
<input type=hidden name=re_link value=\"$re_link\">

<table border=\"0\" width=\"200\">
        <tr>
                <td height=\"15\"><span>$phrases[username] :</span></td>
                <td height=\"15\"><input type=\"text\" name=\"username\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"12\"><span>$phrases[password]:</span></td>
                <td height=\"12\" ><input type=\"password\" name=\"password\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"23\" colspan=2>
                <p align=\"center\"><input type=\"submit\" value=\"$phrases[login]\"></td>
        </tr>
        <tr>
                <td height=\"38\" colspan=2><span>
                <a href=\"index.php?action=register\">$phrases[newuser]</a><br>
                <a href=\"index.php?action=forget_pass\">$phrases[forgot_pass]</a></span></td>
        </tr>
</table>
</form>\n";
close_table();
 }
         }

//---------------------------------------------------
require(CWD . "/includes/framework_end.php");   
?>