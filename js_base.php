<?php 
require_once("admin/function.inc.php");
headers("Content-Type: text/javascript; charset=utf-8");

if(!defined('_DIR_TXT')) die('Un probleme?');

?>
/************************************************\
 *  PLOOK					*
 *                                              *
 *  Copyright 2004-2012     			*
 *  Anne-lise Martenot, http//plook.fr		*
 *                                            	*
 *  Licence GNU/GPL.     			*
 *   						*
\************************************************/



/* POPUP render=
<div id="photo">
<div></div>
<dl>
	<dt>Titre</dt>
	<dd><img id="big_pict" src="" alt="" /></dd>
</dl>
</div>
*/

//POPUP Calcul ratio
function sizes(src_w,src_h,dst_w,dst_h){
	var mlt_w = dst_w / src_w;
	var mlt_h = dst_h / src_h;
if(mlt_w < mlt_h)
	var mlt = mlt_w
	else var mlt = mlt_h;

	img_new_w =  src_w * mlt;
	return img_new_w;
}

//POPUP ACT
function displayPics(){	
var photos = document.getElementById('images_page');
if(!photos){return;}
	var liens = photos.getElementsByTagName('a') ;
	
var div = document.createElement("div");
var idphoto = document.createAttribute("id");
document.body.appendChild(div);
     idphoto.nodeValue = "photo";
     div.setAttributeNode(idphoto);

var dlphoto = document.createElement("dl");
div.appendChild(dlphoto);
var dtphoto = document.createElement("dt");
var ddphoto = document.createElement("dd");

var im = document.createElement("img");
var idim = document.createAttribute("id");
idim.nodeValue = "big_pict";
im.setAttributeNode(idim);
ddphoto.appendChild(im);

var montexte = document.createTextNode("");
dtphoto.appendChild(montexte);

var trans = document.createElement("div");
var photo = document.getElementById('photo');
photo.appendChild(trans);
dlphoto.appendChild(dtphoto);
dlphoto.appendChild(ddphoto);
photo.appendChild(dlphoto);

	photo.style.display='none';
	
	var big_photo = document.getElementById('big_pict') ;
	//big_pict=photo en taille normale
	var titre_photo = document.getElementById('photo').getElementsByTagName('dt')[0] ;
	
	photo.onclick = function(){
	photo.style.display='none';
	}
	
	for(var i = 0 ; i < liens.length ; i++)
	// boucle pour ensemble des liens au clic de images_page
	{
		liens[i].onclick = function()
		{
			big_photo.style.width=null;
			big_photo.src = this.href ; // change src
			if (big_photo.height > window.innerHeight-50){
			size=sizes(big_photo.width,big_photo.height,window.innerWidth,window.innerHeight-50);
			big_photo.style.width=size+'px';
			}
			photo.style.display='block';
			big_photo.alt = this.title ; // change alt
			titre_photo.firstChild.nodeValue = this.title ; // change titre
			return false ;
		}
	}
}
	
	
/* http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html */
var clientPC = navigator.userAgent.toLowerCase();
var clientVer = parseInt(navigator.appVersion);
var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_win = ((clientPC.indexOf("win") != -1) || (clientPC.indexOf("16bit") != -1));


/* http://www.massless.org/mozedit */
function mozWrap(txtarea, open, close){
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if (selEnd == 1 || selEnd == 2) {
		selEnd = selLength;
	}
	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd)
	var s3 = (txtarea.value).substring(selEnd, selLength);
	txtarea.value = s1 + open + s2 + close + s3;
	return;
}	
 

function rTypo(barfield, begin, end){

	var txtarea = barfield;
	txtarea.focus();
	
	if(begin =="lien"){ 
	begin='';
	var lien = prompt(end, "http://");
	end='';
	}
	
	if(begin =="mail"){ 
	begin='';
	var mail = prompt(end, "");
	end='';
	}
	
	else if(begin !='') { 
		begin="<"+begin+">";
		if(end !='') end="</"+end+">";
		
		if(begin.indexOf("!non public")!=-1){
		begin=begin+"\n";
		txtarea.select();
		}
	}		

	if ((clientVer >= 4) && is_ie && is_win) {
		var str = document.selection.createRange().text;
		var sel = document.selection.createRange();
		if (lien != null)
		sel.text = "<a href=\""+lien+"\">" + str + "</a>";
		if (mail != null)
		sel.text = "<a href=\"mailto:"+mail+"\">" + str + "</a>";
		else sel.text = begin + str + end;
	
	} else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)) {
		if (lien != null)
		mozWrap(txtarea, "<a href=\""+lien+"\">","</a>");
		if (mail != null)
		mozWrap(txtarea, "<a href=\"mailto:"+mail+"\">","</a>");
		else mozWrap(txtarea, begin, end);
	}
	
	return;
}




/*  ouvrir et fermer un div cf alert */
/* if num == 0 close */
function swap(quoi,num) {

<?php
/*only IE & admin*/
$actif='var d = document.getElementById(quoi+num);';
$d=$foradmin?$actif:"if ((clientVer >= 4) && is_ie && is_win) $actif else return;";
echo $d;
?>

if(num=='0'){
	var d = document.getElementById(quoi);
	d.style.display='none'; 
	}
else for (var i = 1; i<=10; i++) {
		var ob = document.getElementById(quoi+i);
		if (ob){
			if (ob.id == d.id){
				if(d.style.display!='block')
					ob.style.display='block';
				else ob.style.display='none'; /*swap*/
			} else ob.style.display='none';
		}
	}
}


function open(){
var navact=document.getElementById('navact');
if(!navact) return;
	var nav_a = navact.getElementsByTagName('a') ;

		for(var i = 0 ; i < nav_a.length ; i++)
	// boucle pour ensemble des liens au clic de navact
	{
		nav_a[i].onclick = function()
		{	
			var str=this.href;
			var long=str.length;
			var verif=str.substring(long-9,long-2);
			if(verif!='deplier')return;
			var y=str.substring(long-1,long);
			swap('deplier',y);
			return false; 
		}
	}	
	
}

window.onload = function() {
    swap();
    open();
    displayPics();
    }
