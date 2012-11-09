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

function show_options(box){

nms = box.options[box.selectedIndex].value;

if (nms == 'song_album_set') {
document.getElementById("albums_set_div").style.display = "inline";
document.getElementById("comments_set_div").style.display = "none";
}else if(nms == 'song_ext_set'){
document.getElementById("albums_set_div").style.display = "none";
document.getElementById("comments_set_div").style.display = "inline";
}else{
document.getElementById("albums_set_div").style.display = "none";
document.getElementById("comments_set_div").style.display = "none";
}

}

/*
function show_adv_options(box){

    nms = box.options[box.selectedIndex].value;

if (nms == 'menu') {
document.getElementById("add_after_menu").style.display = "inline";
document.getElementById("banners_pages_area").style.display = "inline";
}else if(nms == 'listen') {
document.getElementById("add_after_menu").style.display = "none";
document.getElementById("banners_pages_area").style.display = "none";
}else{
document.getElementById("add_after_menu").style.display = "none";
document.getElementById("banners_pages_area").style.display = "inline";
}

}

function show_banner_code(){

document.getElementById("banners_code_area").style.display = "inline";

document.getElementById("banners_img_area").style.display = "none";
document.getElementById("banners_url_area").style.display = "none"
}
   
    

function show_banner_img(){

document.getElementById("banners_code_area").style.display = "none";

document.getElementById("banners_img_area").style.display = "inline";
document.getElementById("banners_url_area").style.display = "inline"
}     */ 


function show_adv_options(){

nms = $('type').value;

if (nms == 'menu') {
document.getElementById("add_after_menu").style.display = "inline";
document.getElementById("banners_pages_area").style.display = "inline";
document.getElementById("bnr_content_type").style.display = "inline";

show_banner_img_or_code();

}else if(nms == 'offer') {
document.getElementById("add_after_menu").style.display = "none";
document.getElementById("banners_pages_area").style.display = "none";
document.getElementById("bnr_content_type").style.display = "none";

document.getElementById("banners_code_area").style.display = "inline";
document.getElementById("banners_img_area").style.display = "inline";
document.getElementById("banners_url_area").style.display = "inline"


}else{
document.getElementById("add_after_menu").style.display = "none";
document.getElementById("banners_pages_area").style.display = "inline";
document.getElementById("bnr_content_type").style.display = "inline";

show_banner_img_or_code();
}

}



function show_banner_img_or_code(){

if($('c_type').value=="code"){
document.getElementById("banners_code_area").style.display = "inline";
document.getElementById("banners_img_area").style.display = "none";
document.getElementById("banners_url_area").style.display = "none"

}else{

document.getElementById("banners_code_area").style.display = "none";

document.getElementById("banners_img_area").style.display = "inline";
document.getElementById("banners_url_area").style.display = "inline"
}
}


function set_checked_color(id,box,old_class){
if(box.checked == true){
   
document.getElementById(id).className='row_checked';
}else{
document.getElementById(id).className=old_class;
}
}

function set_tr_color(tr,color){

if(tr.style.backgroundColor !='#efefef'){
tr.style.backgroundColor=color;
}
}


function set_menu_pages(box){

nms = box.options[box.selectedIndex].value;

if (nms == 'c') {
count = document.submit_form.elements.length;
    for (i=0; i < count; i++) 
    {
    if((document.submit_form.elements[i].checked == 1) ||(document.submit_form.elements[i].checked == 0))
        {
if(document.submit_form.elements[i].name == 'pages[0]'){
document.submit_form.elements[i].checked = 1; 
}else{
document.submit_form.elements[i].checked = 0; 
}
}

  
    }
}else{
count = document.submit_form.elements.length;
    for (i=0; i < count; i++) 
    {
    if((document.submit_form.elements[i].checked == 1) ||(document.submit_form.elements[i].checked == 0))
        {document.submit_form.elements[i].checked = 1; }
  
    }
}

}

function uploader(folder,f_name,id)
{
if ( id === undefined ) {
      id = 'win0';
   }


msgwindow=window.open("uploader.php?folder="+folder+"&f_name="+f_name+"&win_name="+id,id,"toolbar=no,scrollbars=no,width=520,height=300,top=200,left=200")
}

function uploader2(folder,f_name,frm)
{

msgwindow=window.open("uploader.php?folder="+folder+"&f_name="+f_name+"&frm="+frm,"popup","toolbar=no,scrollbars=no,width=520,height=300,top=200,left=200")
}

function singers_list()
{

msgwindow=window.open("singers_list.php","displaywindow","toolbar=no,scrollbars=yes,resizable=yes,width=600,height=500,top=200,left=200")
}

function show_hide_preview_text(box){
if(box.checked == true){
document.getElementById('preview_text_tr').style.display = "none";
}else{
document.getElementById('preview_text_tr').style.display = "inline";
}
}


    function show_snd_mail_options(){

if ($('mailing[send_to]').value == 'all') {
   $("when_one_user_email").style.display = "none";
           }else{
   $("when_one_user_email").style.display = "inline";
  }
  }

function show_snd_mail_options2(){
      

if ($('mailing[op]').value == 'msg') {
   $("sender_email_tr").style.display = "none";
   
           }else{
   $("sender_email_tr").style.display = "inline";
  }
  }


function show_uploader_options(box){

if (box == '1') {
   document.getElementById("file_field").style.display = "none";
    document.getElementById("url_field").style.display ="inline";

           }else{
   document.getElementById("file_field").style.display = "inline";
document.getElementById("url_field").style.display =  "none";
 
  }
}


function select_singer(id){
opener.sender.elements['cat'].value = id;
opener.sender.elements['type'].selectedIndex = 0;
window.close();
}

function select_album(id){
opener.sender.elements['cat'].value = id;
opener.sender.elements['type'].selectedIndex =1;
window.close();
}


//------------------- Ajax --------------------------------


function init_blocks_sortlist(){
Sortable.create
(
    'blocks_list_r',{
tag:'div',
containment:["blocks_list_r","blocks_list_c","blocks_list_l"],
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('blocks_list_r') +'&action=set_blocks_sort'}
            );
        }
    }
);
Sortable.create
(
    'blocks_list_c',{
tag:'div',
containment:["blocks_list_r","blocks_list_c","blocks_list_l"],
        constraint: false,
        onUpdate: function()
        {
            new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('blocks_list_c') +'&action=set_blocks_sort'}
            );
        }
    }
);

Sortable.create
(
    'blocks_list_l',{
tag:'div',
containment:["blocks_list_r","blocks_list_c","blocks_list_l"],
        constraint: false,
             onUpdate: function()
        {
            new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('blocks_list_l') +'&action=set_blocks_sort'}
            );
        }
    }
);

}



function init_cats_sortlist(){
Sortable.create
(
    'cats_list',{
tag:'div',
handle:'handle',
        constraint: false,
        onUpdate: function()
        {
       
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('cats_list',{name:'sort_list'}) +'&action=set_cats_sort'}
            );
        }
    }
);
}


function init_new_songs_sortlist(){
Sortable.create
(
    'new_songs_list',{
tag:'div',
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('new_songs_list',{name:'sort_list'}) +'&action=set_new_songs_sort'}
            );
        }
    }
);
}




function init_songs_custom_fields_sortlist(){
Sortable.create
(
    'songs_custom_fields_list',{
tag:'div',
handle:'handle',
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('songs_custom_fields_list',{name:'sort_list'}) +'&action=set_songs_custom_fields_sort'}
            );
        }
    }
);
}

function init_urls_fields_sortlist(){
Sortable.create
(
    'urls_fields_list',{
tag:'div',
handle:'handle',
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('urls_fields_list',{name:'sort_list'}) +'&action=set_urls_fields_sort'}
            );
        }
    }
);
}

function init_sortlist(div_name,action_name){
Sortable.create
(
    div_name,{
tag:'div',
handle:'handle',
        constraint: false,
        onUpdate: function()
        {
     //   alert(Sortable.serialize(div_name,{name:'sort_list'}));
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize(div_name,{name:'sort_list'}) +'&action='+action_name}
            );

        }
    }
);

}


function get_new_menu_add_form(cat,singer){
  
$('ajax_loading').style.display = "inline";    

var url="ajax.php?sid="+Math.random();

new Ajax.Request(url, {   
method: 'post',
parameters : {action:'get_new_menu_add_form',cat: cat,singer: singer},      
onSuccess: function(t){
$('new_menu_add_form').innerHTML = t.responseText; 
$('ajax_loading').style.display = "none";     

}
 }); 
   
}



function get_songs_new_menu_add_form(cat,singer,album){
  
$('ajax_loading').style.display = "inline";    

var url="ajax.php?sid="+Math.random();

new Ajax.Request(url, {   
method: 'post',
parameters : {action:'get_songs_new_menu_add_form',cat: cat,singer: singer,album: album},      
onSuccess: function(t){
$('songs_new_menu_add_form').innerHTML = t.responseText; 
$('ajax_loading').style.display = "none";     

}
 }); 
   
}


function get_select_song(div_name,field_name,cat,singer,album){
  
//$('ajax_loading').style.display = "inline";    

var url="ajax.php?sid="+Math.random();

new Ajax.Request(url, {   
method: 'post',
parameters : {action:'get_select_song',div_name: div_name,field_name: field_name,cat: cat,singer: singer,album: album},      
onSuccess: function(t){
$(div_name).innerHTML = t.responseText; 
//$('ajax_loading').style.display = "none";     

}
 }); 
   
}


function get_song_name(div_name,id){
         
$(div_name+'_loading').style.display = "inline";    

var url="ajax.php?sid="+Math.random();

new Ajax.Request(url, {   
method: 'post',
parameters : {action:'get_song_name',id: id},      
onSuccess: function(t){
$(div_name).innerHTML = t.responseText; 
$(div_name+'_loading').style.display = "none";     
}
 }); 
}
   
 
 
function video_select(field_id){

win = window.open("video_select.php?field_id="+field_id,'winv0',"toolbar=no,scrollbars=no,width=520,height=300,top=200,left=200");
}  
   
function get_video_name(div_name,id){

$(div_name+'_loading').style.display = "inline";      

var url="ajax.php?sid="+Math.random();

new Ajax.Request(url, {   
method: 'post',
parameters : {action:'get_video_name',id: id},      
onSuccess: function(t){
$(div_name).innerHTML = t.responseText; 
$(div_name+'_loading').style.display = "none";    
}
 }); 
}

