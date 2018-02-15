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
    global $memcache,$cache_srv;
if (class_exists('Memcache')) {    
$memcache = new Memcache;
$memcache->connect($cache_srv['memcache_host'], $cache_srv['memcache_port']) or die ("Could not connect to Memcache"); 
}else{
    die("Memcache is not Installed");
}  

}

function cache_set($name,$data){
       global $memcache,$cache_srv;   
    
   return  $memcache->set($cache_srv['prefix'].$name, $data,MEMCACHE_COMPRESSED,$cache_srv['expire']) ;
}

function cache_get($name){
      global $memcache,$cache_srv; 
      
      return $memcache->get($cache_srv['prefix'].$name);   
}

function cache_del($name){
     global $memcache,$cache_srv;   
       return  $memcache->delete($cache_srv['prefix'].$name);
}
