<?
require("global.php") ;
   if(!$re_link){$re_link=iif($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_REFERER'],"index.php");}
   
   $re_link = htmlspecialchars($re_link);
 
if($action=="login"){

if(trim($username) == "" || trim($password) == ""){
      site_header();
      open_table($phrases['login']);
        print "<center>$phrases[plz_enter_username_and_pwd]</center>";
       close_table();
       site_footer(); 
       die();
}

  
$qr = db_query("select * from ".members_table_replace("songs_members")." where ".members_fields_replace("username")."='".db_escape($username,false)."'",MEMBER_SQL);



if(db_num($qr)){
   
   $data = db_fetch($qr);

      if(member_verify_password($data[members_fields_replace('id')],$password,$md5pwd,$md5pwd_utf)){
               
        if(in_array($data[members_fields_replace('usr_group')],$members_connector['allowed_login_groups'])){
        
                
       set_cookie('member_data_id', $data[members_fields_replace('id')]);
       set_cookie('member_data_password', $data[members_fields_replace('password')]);

         js_redirect($re_link,true);  

       
  // ------------- Closed Account -----------------       
    }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['disallowed_login_groups'])){
            site_header();
      open_table($phrases['login']);
                print "<center> $phrases[this_account_closed_cant_login] </center>";
                close_table();
                 site_footer();
 
 //------------- Not Activated Member --------------------                
      }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['waiting_conf_login_groups'])){ 
                
                site_header();
                open_table($phrases['login']);
                print "<center>  $phrases[this_account_not_activated] </center>";
                close_table();
                
                //------ resend activation msg form ----------
                open_table($phrases['resend_activation_msg']);
                print "<form action=index.php method=post>
                <input type=hidden name=action value='resend_active_msg'>
                <center><table><tr><td>
                $phrases[your_email] : </td><td>
                <input type=text size=30 name=email dir=ltr>
                </td><td><input type=submit value=' $phrases[send] '>
                </td></tr></table></center></form>";
                close_table();
                site_footer();
                
 //-------- invalid group id -----------------
                }else{
                 
                      site_header();
      open_table($phrases['login']);
                print "<center> Invalid Member Group </center>";
                close_table();
                 site_footer();
                    
                }
                
                
     //---------- if not valid password ---------------------           
            }else{
            site_header();
                open_table($phrases['login']);
                print "<center> $phrases[invalid_pwd] </center>";
                close_table();
                 site_footer();
                    }

   //--------- if not valid username ---------------                 
        }else{
        site_header();
                open_table($phrases['login']);
                print "<center>  $phrases[invalid_username] </center>";
                close_table();
                    site_footer();
                }


  //--------------- Logout ---------------
}elseif($action=="logout"){

            set_cookie('member_data_id', "");
       set_cookie('member_data_password',"");
       js_redirect($re_link,true);

//---------- Login Form Redirect -----------------
}else{
login_redirect(true);
 }