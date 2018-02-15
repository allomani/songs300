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

function cache_init(){
    global $cache_srv;
if (!function_exists('xcache_get')) {    
  die("Xcache is not Installed");
}  

}

function cache_set($name,$data){
       global $cache_srv;   
     return xcache_set($cache_srv['prefix'].$name,$data,$cache_srv['expire']);
}

function cache_get($name){
    global $cache_srv;
    
   $data =   xcache_get($cache_srv['prefix'].$name);
   if($data == NULL){
       return false;
   }else{
     return $data;  
   }
}

function cache_del($name){
    global $cache_srv;
  return xcache_unset($cache_srv['prefix'].$name);
}
