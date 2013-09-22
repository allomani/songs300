
function listen(id,cat){
window.open(scripturl+"/listen_window.php?id="+id+"&cat="+cat,"displaywindow","toolbar=no,scrollbars=yes,width=600,height=400,top=200,left=200");
return false;
}


function banner_pop_open(url,name){
msgwindow=window.open(url,name,"toolbar=yes,scrollbars=yes,resizable=yes,width=650,height=300,top=200,left=200");
}

function banner_pop_close(url,name){
msgwindow=window.open(url,name,"toolbar=yes,scrollbars=yes,resizable=yes,width=650,height=300,top=200,left=200");
}

/*

function send(id)
{
msgwindow=window.open("send2friend.php?id="+id,"displaywindow","toolbar=no,scrollbars=no,width=400,height=320,top=200,left=200")
}   

function snd_vid(id)
{

msgwindow=window.open("send2friend.php?op=video&id="+id,"displaywindow","toolbar=no,scrollbars=no,width=400,height=320,top=200,left=200")
}    */

function vote(id,action)
{

msgwindow=window.open(scripturl+"/vote.php?id="+id+"&action="+action,"displaywindow","toolbar=no,scrollbars=no,width=350,height=250,top=200,left=200")
}

/*
function add2fav(id,type)
{
msgwindow=window.open("add2fav.php?id="+id+"&type="+type,"displaywindow","toolbar=no,scrollbars=no,width=350,height=150,top=200,left=200")
} */



// -------- fav --------------
function add_to_fav(id,type){
 
var url="ajax.php";
url=url+"?action=add_to_fav&id="+id+"&type="+type;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){

 
setContent = new Boxy("<div>"+t.responseText+"</div>"
 ,{modal:true,title:"<br>",unloadOnHide: true});           
    
}
 }); 

}

function add_to_fav_confirm(id,type){
setContent.hide();

var url="ajax.php";
url=url+"?action=add_to_fav&confirm=1&id="+id+"&type="+type;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){

 
setContent = new Boxy("<div>"+t.responseText+"</div>"
 ,{modal:true,title:"<br>",unloadOnHide: true});           
    
}
 }); 
}



//------- send to friend --------------
function send(id,type){
    
 
var url="ajax.php";
url=url+"?action=send2friend_form&id="+id+"&type="+type;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){
 
setContent = new Boxy("<div>"+t.responseText+"</div>"
 ,{modal:true,title:"<br>",unloadOnHide: true});  
 //setContent.centerAt(600 ,342);  
   

  
}
 }); 

} 
 

 
function send_submit(){

if($('name_from').value != "" && $('name_from').value != ""  && $('email_to').value != "" ){  
$('send_button').disabled=true;


$('send_submit_form').request({
  onSuccess: function(t){ 
  setContent.setContent("<div>"+t.responseText+"</div>");   
  //$('snd2friend_div').innerHTML= t.responseText; 
  }
});
}else{
alert('Please Fill All Fields');
}
return false;
} 
//---------------------------

function CheckAll(form_name){

 if(form_name===undefined){ var form_name = 'submit_form';}  
 
count = document.forms[form_name].elements.length;
    for (i=0; i < count; i++) 
    {
    if((document.forms[form_name].elements[i].checked == 1) ||(document.forms[form_name].elements[i].checked == 0))
        {document.forms[form_name].elements[i].checked = 1; }
  
    }
}
function UncheckAll(form_name){

if(form_name===undefined){ var form_name = 'submit_form';} 

count = document.forms[form_name].elements.length;
    for (i=0; i < count; i++) 
    {
    if((document.forms[form_name].elements[i].checked == 1) || (document.forms[form_name].elements[i].checked == 0))
        {document.forms[form_name].elements[i].checked = 0; }

    }
}

/* ---------------- AJAX --------------- */

function ajax_check_register_username(str)
{
var url="ajax.php";
url=url+"?action=check_register_username&str="+str;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){ $('register_username_area').innerHTML=t.responseText;}
 }); 

}

function ajax_check_register_email(str)
{
var url="ajax.php";
url=url+"?action=check_register_email&str="+str;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){$('register_email_area').innerHTML=t.responseText;}
 }); 

}

function init_playlist_sortlist(){
Sortable.create
(
    'playlist_div',{
tag:'div',

        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('playlist_div',{name:'sort_list'}) +'&action=set_playlist_sort'}
            );
        }
    }
);
}

function playlist_add_song(song_id){

var url="ajax.php";
url=url+"?action=playlist_add_song&song_id="+song_id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){

var new_id =  t.responseText;

var url="ajax.php";
url=url+"?action=playlist_get_item&id="+ new_id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){

if($('playlist_div').innerHTML=='---'){
$('playlist_div').innerHTML='';
}
var new_element = document.createElement('div');
new_element.id = 'playlist_item_'+new_id;
new_element.innerHTML =  t.responseText;
$('playlist_div').insertBefore(new_element, $('playlist_div').firstChild);
init_playlist_sortlist();
}
 }); 

}
 }); 
}

function playlist_delete_song(id){

var url="ajax.php";
url=url+"?action=playlist_delete_song&id="+id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){
var x=$('playlist_item_'+id).parentNode.childNodes.length;
$('playlist_item_'+id).parentNode.removeChild($('playlist_item_'+id));
if(x <=1){
$('playlist_div').innerHTML = '---';
}
}
 }); 
}


function get_playlist_items(id){
var url="ajax.php";
url=url+"?action=get_playlist_items&id="+id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){
$('playlist_div').innerHTML =  t.responseText;
init_playlist_sortlist();
}
 }); 
}

function playlists_add(){
if($('playlists_add_div').style.display == "inline"){
$('playlists_add_div').style.display = "none";
}else{
$('playlists_add_div').style.display = "inline";
}
}

function playlists_del(id){
var url="ajax.php";
url=url+"?action=playlists_del&id="+id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){
get_playlists();
get_playlist_items(t.responseText);
}
 });
}


function get_playlists(){
var url="ajax.php";
url=url+"?action=get_playlists&name="+name;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){

$('playlists_select_div').innerHTML =  t.responseText;
}
 });
}

function playlists_submit(name){
if(name){
var url="ajax.php";
url=url+"?action=playlists_add&name="+name;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){
get_playlists();
get_playlist_items(t.responseText);
$('playlists_add_div').style.display = "none";
$('playlist_name').value='';
}
 }); 
}
}

//---------- Comments Functions -------------------------------

function comments_add(type,id){
$('comment_add_button').disabled = true;
$('comment_content').disabled = true; 

var url="ajax.php?sid="+Math.random();

new Ajax.Request(url, {   
method: 'post',
parameters : {action:'comments_add',type: type,id: id,content: $('comment_content').value},   
onSuccess: function(t){

//$('comment_status').innerHTML = t.responseText; 
$('comment_add_button').disabled = false;
$('comment_content').disabled = false; 

 // alert(t.responseText);
          
                
var json = t.responseText.evalJSON();
   
if(json.status == 1){
$('comment_content').value = ''; 
$('comment_content').focus();
$('no_comments').innerHTML = "";

if(json.content == ""){
//$('comment_status').innerHTML = json.msg;
alert(json.msg); 
}else{
$('comments_div').innerHTML = $('comments_div').innerHTML + json.content;
}
}else{
alert(json.msg);
//$('comment_status').innerHTML = json.msg;
}

}
 }); 
 
   
}


function comments_delete(id){
    
var url="ajax.php";
url=url+"?action=comments_delete&id="+id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){

 $('comment_'+id).style.display="none";     

}
 });  
 
 
}
    
var comments_offset = 1;

function comments_get(type,id){
 $('comments_loading_div').style.display = "inline"; 
 $('comments_older_div').style.display = "none";    

var url="ajax.php?sid="+Math.random();

new Ajax.Request(url, {   
method: 'post',
parameters : {action:'comments_get',type: type,id: id,offset: comments_offset},      
onSuccess: function(t){
$('comments_div').innerHTML = t.responseText + $('comments_div').innerHTML; 
 $('comments_loading_div').style.display = "none"; 
 comments_offset++;   


}
 }); 
}


//----------- Rating -------------------
function rating_send(type,id,score){ 

$(type+id+'_rating_loading_div').style.display = "inline"; 
$(type+id+'_rating_status_div').style.display = "none";    

var url="ajax.php?sid="+Math.random();

new Ajax.Request(url, {   
method: 'post',
parameters : {action:'rating_send',type: type,id: id,score: score},      
onSuccess: function(t){

$(type+id+'_rating_status_div').innerHTML = t.responseText; 

 $(type+id+'_rating_loading_div').style.display = "none"; 
$(type+id+'_rating_status_div').style.display = "inline"; 
  


}
 }); 
 
 
}


//------------- Reports ---------------------
function report(id,report_type){

var url="ajax.php?sid="+Math.random();

new Ajax.Request(url, {   
method: 'post',
parameters : {action:'report',id: id,report_type: report_type},   
onSuccess: function(t){

setContent = new Boxy("<div>"+t.responseText+"</div>"
 ,{modal:true,title:"<br>",unloadOnHide: true }); 
 
 
}
});
}


function report_send(){

$('send_button').disabled=true;


$('report_submit').request({
  onSuccess: function(t){ 
  setContent.setContent("<div>"+t.responseText+"</div>");   
  }
});

}

    

function evalScript(scripts)
{    try
    {    if(scripts != '')    
        {    var script = "";
            scripts = scripts.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, function(){
                                        if (scripts !== null) script += arguments[1] + '\n';
                                         return '';});
            if(script) (window.execScript) ? window.execScript(script) : window.setTimeout(script, 0);
        }
        return false;
    }
    catch(e)
    {    alert(e)
    }
}



var last_index = 0;

function play_song(index){ 



if(index >= max_index){ 
    index = 0;
 
}
    
var song_name = songs_list[index][0];
var song_url = songs_list[index][1];

$('song_'+index).removeClassName('pl_song_stop');
$('song_'+index).addClassName('pl_song_play');
//alert("tog"+index+" : pl_song_play");

if(index != last_index){
    $('song_'+last_index).removeClassName('pl_song_play');
$('song_'+last_index).addClassName('pl_song_stop');
//alert("tog"+last_index+" : pl_song_stop");  
}

 

$('loading_div').style.display = "inline";  
                                                                                            

last_index = index; 
var url="ajax.php?sid="+Math.random();
 $('player_div').innerHTML="";
new Ajax.Request(url, {  
 
method: 'post',
parameters : {action:'get_playlist_player',url: song_url,cur_index: index},      
onSuccess: function(t){  
   
//if(index == 2){
//jwplayer('player_div').remove();

$('player_div').innerHTML = t.responseText;
//t.responseText.evalScripts(); 
//$('player_div').innerHTML.evalScripts();
//}else{ */
evalScript(t.responseText);
//eval(t.responseText); 
//}

 $('loading_div').style.display = "none"; 
  


}
 }); 

 
}



                     var idgen = 0;

var ElementTextCache = {};
var tblParentCache = {};
var JumpEl;
var jumpcount = 0;
function quicksearch(searchBoxEl) {

    var searchText = searchBoxEl.value.toLowerCase();
   
  //  var liEls = document.getElementsByTagName('div');
  var liEls = document.getElementsByClassName('song_div');
    var matchCount = 0;
    var allParentUls = {};
    var matchedParentUls = {};
    var matchEl;
    for(var i = 0;i < liEls.length;i++) {
        var innerText;

        if (!ElementTextCache[liEls[i].id] || ElementTextCache[liEls[i].id] == "") {
            var AddedsearchText = liEls[i].getAttribute('searchtext');
            innerText = liEls[i].innerHTML.replace(/\<[^\>]+\>/g,'').toLowerCase();
            if (AddedsearchText) { innerText += " " + AddedsearchText; }
            ElementTextCache[liEls[i].id] = innerText;
        } else {
            innerText = ElementTextCache[liEls[i].id];
        }


        var tblParent = getTblParent(liEls[i]);
        if (innerText.match(searchText)) {
            matchEl = liEls[i];
            matchCount++;
            liEls[i].style.display='inline';
            matchedParentUls[tblParent.id] = 1;
            allParentUls[tblParent.id] = tblParent;
        } else {
            allParentUls[tblParent.id] = tblParent;
            liEls[i].style.display='none';
        }
    }
    
    if (matchCount == 0) {
        $('no_quicksearch_results').style.display='inline';
        $('select_and_listen').style.display='none'; 
    } else {
        $('no_quicksearch_results').style.display='none';
        $('select_and_listen').style.display='inline'; 
    }  
    if (searchText) {
        $('clearlnk').style.display='';
    } else {
        $('clearlnk').style.display='none';
    }
    /*
    for(var i in allParentUls) {
        if (matchedParentUls[i]) {
                allParentUls[i].style.display='inline';
        } else {
                allParentUls[i].style.display='none';
        }
    }  */
}

function getTblParent(tagEl) {
    if (!tagEl.id) {
        tagEl.id = 'idgen' + idgen++;
    }
    if (tblParentCache[tagEl.id]) { return tblParentCache[tagEl.id]; }

    var thisEl = tagEl;
    while(thisEl.tagName != "UL" && thisEl.parentNode) {
        thisEl = thisEl.parentNode;
    }
    tblParentCache[tagEl.id] = thisEl;
    return thisEl;
}


function quicksearch_clear() {
    var quickJumpEl = document.getElementById('quick_search');
    quickJumpEl.value='';
    quicksearch(quickJumpEl);

}    