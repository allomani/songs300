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

//----------- Database Settings -------------- 

$db_host = "localhost";
$db_name = "songs300";
$db_username = "root";
$db_password = "";
$db_charset = "utf8"; 


//---------- Script Settings ---------- 

$blocks_width = "17%" ;

$editor_path  = "ckeditor";       // no_editor : to remove editor 

$global_lang = "arabic" ;

$copyrights_lang = "arabic";

$preview_text_limit = 300 ;

$online_visitor_timeout = 800; // in seconds 

$use_editor_for_pages = 1 ;  // 1 enable - 0 disable

$sitemap_perpage = 40000;

$arabic_date_months = true;
$arabic_date_days = true;

$access_log_expire=90 ; // days

$admin_referer_check = true;

 
//$default_uploader_chmod = "777";

//$disable_backup = "���� , ��� ������� ��� ����� �� ������ ���������" ;
//$disable_repair = "���� , ��� ������� ��� ����� �� ������ ���������" ;

//------------ Cache --------------
$cache_srv['engine'] = "nocache" ; // memcache - xcache - filecache - nocache 
$cache_srv['expire'] = 3600 ; //seconds
$cache_srv['memcache_host'] = "localhost";
$cache_srv['memcache_port'] = 11211;
$cache_srv['filecache_dir'] = "cache";
$cache_srv['prefix'] = "main:";

//----------- Error Handling  ---------
$custom_error_handler = false;

$display_errors = false;
$log_errors  = false;
 
 
$show_mysql_errors = false ;
$log_mysql_errors = false;


$logs_path = "uploads/logs";
$log_max_size = 1024*1024;

$debug = false;

//----------- Auto Search -------------
$auto_search_default_exts = "rm,mp3,ra,wav,amr,3gp";
$auto_search_exclude_exts = "exe,php,html,php4,php4,php5,cgi,htm,cnf,ini";
$autosearch_folders = array('uploads',
                            'images'
                            );
//---------- to use remote members database ----------
$members_connector['enable'] = 0;
$members_connector['db_host'] = "localhost";
$members_connector['db_name'] = "forum";
$members_connector['db_username'] = "root";
$members_connector['db_password'] = "";
$members_connector['db_charset'] = "utf8";
$members_connector['custom_members_table'] = "";
$members_connector['connector_file'] = "vbulliten.php";

//--------------- to use SMTP Server ---------
$smtp_settings['enable'] = 0;
$smtp_settings['host_name']="mail.allomani.com";
$smtp_settings['host_port']= 25;
$smtp_settings['ssl']=0;
$smtp_settings['username'] = "info@allomani.com";
$smtp_settings['password'] = "password_here";
$smtp_settings['timeout'] = 10;
$smtp_settings['debug'] = 0;
$smtp_settings['show_errors'] = 1;


//-------- Cookies Settings  -----------
$cookies_prefix = "songs_";
$cookies_timemout = 365 ; //days
$cookies_path = "/" ;
$cookies_domain = "";

?>
