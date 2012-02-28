<?php 
/************************************************\
 *  PLOOK					*
 *                                              *
 *  Copyright 2004-2012     			*
 *  Anne-lise Martenot, http//plook.fr		*
 *                                            	*
 *  Licence GNU/GPL.     			*
 *   						*
\************************************************/
/*		PLOOK 1.3.1 - fev 2012 		*/


if(strrpos(s('PHP_SELF'),'function.inc.php')!==FALSE)die('Access Denied');

$URI = htmlspecialchars(s('REQUEST_URI'));
$STRING = htmlspecialchars(s('QUERY_STRING'));

$http="http://".s('HTTP_HOST').$URI;
$clean=str_replace(array('modules/','admin/'),'',$http);
$replace=isset($STRING)?"?".$STRING:'';
$url_site=str_replace($replace,'',$clean);

// le repertoire admin/
define('_DIR_RESTREINT_ABS', 'admin/');
// actuellement dans admin/ ?
define('_DIR_RESTREINT',(!is_dir(_DIR_RESTREINT_ABS) ? "" : _DIR_RESTREINT_ABS));
// ou inversement ?
define('_DIR_RACINE', _DIR_RESTREINT ? '' : '../');
// les reps
define('_DIR_TXT',_DIR_RESTREINT.'textes/');
define('_DIR_CONF', _DIR_TXT.'config/');
define('_DIR_MODULES',!is_dir(_DIR_RESTREINT)?'../modules/':'modules/');
define('_DIR_IMG',_DIR_RACINE.'images/');
// admin
$isadmin = !is_dir(_DIR_RESTREINT) ?'oui':'';

//special css & js
$foradmin = (substr($URI, -5)=='admin') ?'oui':'';
//initialiser la date;
if(function_exists("date_default_timezone_set") && function_exists("date_default_timezone_get"))
@date_default_timezone_set(@date_default_timezone_get());


$plook_extern="http://plook.fr/plook_modules/"; //modules supp
$sitefile="site.txt"; //titre et descriptif du site
$sitepath=_DIR_CONF.$sitefile;
$accueil="accueil"; //1er page
$accfile="$accueil.txt";
$plook=_DIR_IMG."plook.gif"; //!delete
$lang="fr";
$ltr='ltr';

//variables à null
$mess=null;
$admin_message=null;
$pipe_head=null;
$pipecontent=null;
$pipe_footer=null;
$lien_modules=null;

//page ou rep actuel
$thispage = _rq('f');
$thisrep=_rq('d');
$thisfile=$thisrep?$thisrep:"$thispage.txt";
//secu, redirect to home
if (!$thispage && !$thisrep OR (strrpos($STRING,'\.')!==FALSE)) {$thisfile= $accfile; $thispage=$accueil;}

$path_page=findpath($thisfile);
$pathrep=chemin_rep($path_page);
$tetimage = trouveimage("site_tete.jpg","css");
$piedimage = trouveimage("site_pied.jpg","css");
$sitelogo = trouveimage("site_logo.jpg");



//appel module lang
if(is_file(_DIR_MODULES."lang/module_multilang.php") && !function_exists('traduire')){
	//$mess .="module lang existe > appel de traduire!";
require_once(_DIR_MODULES."lang/module_multilang.php");
}else{
	//mess .=" Plook en base francais";
}


function s($s) {
if (isset($_SERVER[$s])) return $_SERVER[$s];
else return NULL;
}

//post ou get
function _rq($var, $secu=false, $c=false) {
	if (is_array($c))
		return isset($c[$var]) ? $c[$var] : NULL;
		if (isset($_GET[$var])) {
			if ($secu) return NULL;
			else $a = $_GET[$var];
		}
	elseif (isset($_POST[$var])) $a = $_POST[$var];
	else return NULL;
	return $a;
}

// Enleve le numero des titres numerotes ("1. Titre" -> "Titre")
function supprimer_numero($texte) {
	return preg_replace(
	",^[[:space:]]*([0-9]+)([.)]|".chr(194).'?'.chr(176).")[[:space:]]+,S",
	"", $texte);
}

//js todo grrr php 4
function utf($str){
//if(function_exists("html_entity_decode"))
//return html_entity_decode($str, ENT_NOQUOTES,"UTF-8");  //
//else 
return $str; 
}


//lang, css ou module
function quel($rep,$file,$koi=''){
	$ret=null;
	if(is_file("$rep/$file")) $ret=lire("titre","$rep/$file");
	
	if($koi=='lang' OR $koi=='sens'){
	$lang =_rq('lang')?_rq('lang'):(isset($ret)?$ret:"fr");
	$GLOBALS['lang']=$lang;
	$ret = $lang;
	}
	
	if($koi=='css'){
	$css="$ret.php";
	if(is_file(_DIR_MODULES."css/$css"))
	$ret="$css";
	else return null;
	}

	$ltr=($ret=='ar'OR $ret=='he')?'rtl':'ltr';   
	if ($koi=='sens') return $ltr;
	else return $ret;
}


function _T($txt, $args=array()) {
	$trad = function_exists('traduire')?traduire($txt,$GLOBALS['lang']):'';
	if (!strlen($trad)OR($trad==$txt))
		//no trad?
		$trad = str_replace('_', ' ', (($n = strpos($txt,':')) === false ? $txt : substr($txt, $n+1)));
	if (is_array($args))
	foreach ($args as $name => $value)
		$trad = str_replace ("@$name@", $value, $trad);
	return $trad;
}

$lire_titre_page=lire("titre");
$lire_meta=is_file($sitepath)?lire("nobr", $sitepath):'';

//action formulaire
if($isadmin){
//module > form
# $module = _rq('module')? _rq('module') :'name.zip';
//module
#if ($extern = _rq('zipup')) $mess.=faire('zipup','',$extern);

	$textarea=lire('prive');
	$path=_rq('dossier')?_rq('dossier'):_DIR_CONF;
	$type=_rq('type');
	$titrenew=_rq('titrenew');
	$filenew=_rq('filenew');
	
# demo active ( interdit une liste de fichiers )
$modemo=_DIR_MODULES."demo/module_demo.php";
if(is_file($modemo)) {
	require_once($modemo);
	$protege=demo();
	if(isset($protege)){
	$path=$pathrep='demo';
	$mess.=$protege;
	}
}
# fin demo active

	//ecrire
	if ($txtarea=_rq('texte','adm')) $mess.=faire('ecrire',$pathrep,'textarea',$txtarea,$thisfile);
	if ($titresite=_rq('titresite','adm')) $mess.=faire('ecrire',$path,'titresite',$titresite,$sitefile);
	//rename or move
	if ($submit = _rq('rename','adm')) {
		$elem = _rq('elem');
			
		//to move
		if(($elem!=$filenew)OR($path!=$pathrep)) $mess.=faire('renommer',$path,$type,$elem,$filenew);
		elseif($path==$pathrep) $mess.=faire('ecrire',$path,'titre_page',$titrenew,$filenew);
	}
	//delete
	if ($submit = _rq('suppr','adm')) $mess.=faire('supprimer',$path,$type,'',$filenew);
	//create
	if ($submit = _rq('creer','adm')) $mess.=faire('creer',$path,$type,$titrenew,$filenew);

//actualiser..?todo
	$path_page=findpath($thisfile);
	$textarea=lire('prive');
	$lire_titre_page=lire("titre");
}

//public
$pagetext=lire();
$lire_titre_site=is_file($sitepath)?lire("titre",$sitepath):'PLOOK';
$pathrep=chemin_rep($path_page);
$nav= menu('nofollow');
$isariane=ariane();
$ariane=$isariane?"<div id='ariane'>".$isariane."</div>":'';
$navsous=menu('not_racine',$pathrep);


//img css entete et pied
function trouveimage($img,$css=''){
	$ext = array('.jpg', '.jpeg', '.png', '.gif','.JPG','.JPEG','.PNG', '.GIF'); 
	foreach($ext as $sinon) { 
	$trouve=str_replace('.jpg',$sinon,$img);
	$res=_DIR_IMG.$trouve;
		if (is_file($res)){
		$ret=$css==''?$res:"url('$res')";
		return $ret;
		}
	}
}

function findpath($file='',$pathdir=_DIR_TXT,$include=false){
if(is_dir($pathdir)){
	$path=$pathdir.$file;
	if (file_exists($path)) return $path;
	else{
	//sous rep1
	$explorep = explorer($pathdir);
	$sousreps=$explorep['sous_reps'];
		foreach ($sousreps as $sousdos){
			$path=findpath($file,$pathdir."$sousdos/",$include);
			if (file_exists($path)) return $path;
		}
	}
	}
}

function chemin_rep($page){
$rep=is_dir($page)?$page:dirname($page);
if ($rep && $rep!='.') return $rep.'/';
else return _DIR_TXT; // au pire on revient sur textes
}

//2 <br /> = <p>
function dop($str){
$str.= " <br /><br />";
$str= preg_replace('#(<br\s*\/>[\n\r\s]*){2,}(.*)#sU','</p><p>$2',$str); # 2 br = /p p
if(substr($str, -7)=="</p><p>") $str= substr_replace($str,' <p> ',strpos($str,"</p><p>"),7); # no first p
if(substr($str, -3)=="<p>") $str= substr_replace($str,'',strrpos($str, "<p>"),3); # no last p
$str= preg_replace('#</h([[:alnum:]])><br />#U', '</h$1>', $str); # h br == h
$str= preg_replace('#(<p>*?)<h([[:alnum:]])>(.+)</h([[:alnum:]])>#U', '<h$2>$3</h$2>$1', $str); # p h/h == h/h p
$str= preg_replace('#<p>[\n\r\s]*<\/p>|<p><\/p>|%5Cr|%5Cn#U', '', $str); # no p empty and clean code
return $str;	
}


function lire($quoi='public',$path_f=''){
global $isadmin, $thisfile,$path_page,$sitefile,$pathrep,$accfile;
$ret=null;
if (!$path_f) $path_f=$path_page;
	if (is_file($path_f)) {
	if (!$fr = fopen($path_f, "rb")){ exit; }
		else{if (!is_dir($path_f)){
			$titre_page = trim(fgets($fr,255)); //1er ligne
			$lon = filesize($path_f);
				if ($lon==0) return;
			$ch=@fread($fr,$lon);
			//public ?
			$testpub=preg_match("[^<!non publi]", $ch)?'nonpublic':'';
			if ($quoi=="testpublic") return $testpub; //rep = vide
			
			//config not public!
			if($pathrep==_DIR_CONF && !$isadmin && $quoi=="public") return;
			elseif(!$isadmin && $testpub=='nonpublic') return;
				elseif ($quoi=="nobr"){
				$ret= preg_replace("(\r\n|\n|\r)",'',$ch);
				}
				elseif ($quoi=="public"){
				$ret=dop(nl2br($ch));
				}
				elseif ($quoi=='titre') $ret = $titre_page;
				elseif ($quoi=='prive') 
				$ret =  $ch;
	
			 fclose($fr);
			}
		}
	}else{ if (is_dir($path_f)) return $thisfile;
		 //sous rep or 404
		 $sous=findpath($thisfile);
		 if (is_file($sous)) $ret=lire($quoi,$sous);
		 elseif (is_dir(_DIR_TXT)&& $thisfile!=$sitefile){
			 $ret = $thisfile==$accfile?"":"$thisfile 404 :( "._T('choisir_page');
		}
	}
//secu for only dir /textes
//$ret=(strrpos($path_f,_DIR_TXT)===FALSE)?'':$ret;
return $ret;
}

//fil d'ariane
function ariane($chevre=" &#9658; "){
	global $path_page, $isadmin, $accueil, $accfile, $thisfile;
	$ret=null;
	$acc =ucwords(lire("titre",_DIR_TXT.$accfile));
	$explode=explode('/',$path_page);
	$count=count($explode);
	if (!$isadmin) {	//public
	 	if ($count<4) return;
		 $list=array_shift($explode); 
		 $i=2; //- 1er rep (admin)
		} else {$i=1; if ($count<3) return; }	
  
	foreach ($explode as $f){	   
		if(strrchr($f,'.')==".txt"){
			$a="?f=".str_replace('.txt','',$f);
			$f=lire("titre",$f);
			$cl="fil";
		}else{
		$stock=explode('/',$path_page); 
		array_splice($stock, $i++);
		$pathdir=implode("/",$stock);
		if(is_dir($pathdir)){
		  $isok=isrepok($pathdir);
		   $a=$isadmin?"?d=$f":$isok['href']; 
			$cl="rep";
		} if(str_replace('textes',$acc,$f)!=$f){$a="?f=".$accueil; $f=$acc;  $cl='fil'; }
		}	

			$ret.="<a href='$a' class='$cl'>$f</a>$chevre";
		}
	 
	$ret=substr($ret, 0, -8); //  >
	return $ret;
}

//form select directories
function replist($type='',$rep=_DIR_TXT, $sous=''){
	global $pathrep,$path_page;
	$ret=NULL;
	if(!is_dir($rep)) return;
	
	$ex=explode('/',$pathrep);
	$c=count($ex);
	$r=$ex[$c-2];
		if ($type=='rep')
			$r=$ex[$c-3];
	$sous.="&nbsp; ";
	$explorep = explorer($rep);
	$sousreps=$explorep['sous_reps'];
	foreach ($sousreps as $d){
	if($type!='rep' OR($rep."$d/"!=$pathrep&&$type=='rep'))
	$ret.="<option value='$rep"."$d/'".($d == $r ? ' selected="selected"' : '').">$sous $d</option>"
	.replist($type,$rep."$d/",$sous);
	}

return $ret;
}		

//explore rep
function explorer($homedir='',$count='',$order=null) {
global $thispage;
	if(is_dir($homedir)){
	$ret=Array();
	$files=Array();
	$nb=Array();
	$sousrep=Array();

	    foreach (new DirectoryIterator($homedir) as $fileInfo) {
	    if($fileInfo->isDot()) continue;
	    $file=$fileInfo->getFilename();
	     
	    if ($fileInfo->isDir()) {
			$sousrep[] = $file;
			$nb = array_merge($nb,explorer($homedir."/".$file,$count));
	     }
	    elseif (preg_match("[^$thispage\_]", $file)){
			$nb[]= $file;
			}
	    $files[] = $file; //files + reps
	    
	    }
	    if(!$order){
	    ksort($files);
	    }
	    $ret['files'] = $files;
	    $ret['sous_reps'] = $sousrep;
	    $return=$count=="count"?$nb:$ret;
	    return $return;
    	}
}


function imagier($aff='public',$dir=_DIR_IMG){
	global $thispage, $URI;
	$retd=null;
	$ret=null;
	$classin=null;
	$form=null;
	$width=null;
	$mode=($dir==_DIR_IMG && $aff!='all')?'id="images_page"':'class="docs_page"';
	$tagopen="<div $mode>";
	$n=0;
	
	$array=Array();
	$explorep = explorer($dir);
	/*if($aff!='all') $array=$explorep['inpage'];
	else */
	$array=$explorep['files'];
	
	foreach($array as $file) {
	
		$fichier=$dir.$file;
	if(!is_dir($fichier)){
		$ext=str_replace('.','',strrchr($file,'.'));
		$nam=explode('_',$file);
		if (count($nam)>0){
		$p=$nam[0];
		$name=str_replace($p."_",'',$file);
		$name=str_replace(array('-', '_', '.'.$ext), ' ', $name);
		}else $name=$file;
		
		$sz_res =getimagesize($fichier);
		$fs_res= filesize($fichier);
		$info=($sz_res)?"$sz_res[0] x $sz_res[1]px /":strtoupper($ext);
		$infoct=in_oct($fs_res);
		$inpage=($p==$thispage)?'inpage':'outpage';
		$mime=($sz_res)?"type='".$sz_res['mime']."'":'';
		
		if($aff!="public"){
				if($inpage=='inpage') {
					$classin=" actifimg";
					$pagein="<a href='?f=$p'>"._T('page_courante')."</a>";
				} else {
					$path=findpath("$p.txt");
				if(!is_file($path)){
						$classin=" deadimg";
						$pagein=_T('page_aucune');
						} else {
						$classin=null;
						$pagein="<a href='?f=$p'>"._T('page_voir')."</a>";
						}
				}
				$infotop="<h4>$name</h4>
				<span dir='ltr'>$info $infoct</span>";
				$form=($sz_res)?$infotop:'';
				$form.='
				<form action="'.$URI.'#documents" method="post" class="enligne">
				<p> 
				<input type="text" dir="ltr" name="filenew" value="'.$file.'" size="22" class="bloc" />
				<input type="hidden" dir="ltr" name="elem" value="'.$file.'" />
				<input type="hidden" name="dossier" value="'.$dir.'" />
				<input type="hidden" name="type" value="image" />
				<input type="submit" name="rename" value="'._T("renommer_").'" class="renomme" />
				<input type="submit" name="suppr" value="x '._T("supprimer_").'" class="supprime" />
				</p>
				</form>
				';
				if ($aff=='all') $form.="<span class='pageou'>$pagein</span>";
				$width=($aff=='all'&& $sz_res[0]>199)?"class='pourcent'":"width='$sz_res[0]px'";
		}
		
			$reta='<div class="im'.$classin.'">
				<a href="'.$fichier.'" title="'.$name.'" '.$mime.'>';
				//? image < 320px garder taille orig sinon css %
				$im ='<img src="'.$fichier.'" alt="'.$name.'" '; 
				$im.=$sz_res[0]<320 ? $width:"width='320px' class='pourcent'";
				$im.=" />";
				//document
				$doc="<span>"._T('telecharger_')."</span><span dir='ltr'> $file ($info - $infoct)</span>"; 
				$reta.=($sz_res)?$im:$doc;
				$reta.="</a>";
				$reta.="$form </div>";
			//dans la page ?
			if(($aff=="page" OR $aff=="public") && $inpage!='inpage'){ 
			unset($reta); 
			} else { $ret.=$reta; $n++; }
			 
	}else
	//dir documents
	$retd.=imagier($aff,$fichier.'/');
	}
	$tagclose="</div>";
	//(($aff=="page" OR $aff=="public") && $n==0)?'':
	$retour=$tagopen.$ret.$tagclose.$retd;
	if($n==0) $retour=$ret.$retd;

return $retour;
}


//tester un dossier
//trouver son 1er fichier public et renvoyer le bon lien
function isrepok($hdir,$page=''){
	  //le rep est il public?
$explorep = explorer($hdir.$page);
	$sousf=$explorep['files']; 
	sort($sousf);
		$count=count($sousf);
	//au moins un fichier dedans qui est un .txt 
		if ($count>0 && strrchr($sousf[0],'.')==".txt"){
		//on dirige vers le fichier
		$a="?f=".str_replace('.txt','',$sousf[0]);
		//test si public
		$public=lire("testpublic",$hdir.$page."/".$sousf[0]);
		}else{
	//sinon le dossier est vide ou son premier fichier est un dossier
		$a = "?d=".$page;
		$public='nonpublic';
		}
	$ret['href'] = $a;
	$ret['ispublic'] = $public;
	return $ret;
		
}

/*MENU et SOUS MENU*/
function menu($mode='suite',$hdir='',$page=''){
	global $accfile, $isadmin, $thisfile, $path_page, $pathrep;
	$ret=null;
	if(!$hdir) $hdir=_DIR_TXT;
	if(is_dir($hdir)){
	if($mode=='not_racine' && $hdir==_DIR_TXT) return;
	
	$id=!$page?"class='$mode'":"id='$page"."1'";
	$ulfirt="\n<ul $id >\n";
	$ulast= "</ul>\n"; 
	
	$explorep = explorer($hdir);
	$files=$explorep['files'];
	
  foreach ($files as $num=>$page){
	  //txt?
	  	if(strrchr($page,'.')==".txt"){
		$a="?f=".str_replace('.txt','',$page);
		$public=lire('testpublic',$hdir.$page); 
		$titre=lire("titre",$hdir.$page);
		$ack1=($page==$accfile)?' accesskey="1" ':'';
		if ($titre){
		//public?
		$classpub=(!$public)?'':" class='$public' ";
		$ret .="<li".$classpub.">";
		$classa=$page==$thisfile ? " class='actif' ":'';
		$ret .= "<a href='$a'".$classa.$ack1." >$titre</a>";
		$ret .="</li>\n";
		}
	  }
	
	//REP sousrep? but not config!
           if (is_dir($hdir.$page)&& $hdir.$page.'/'!=_DIR_CONF) {
		   $isok=isrepok($hdir,$page);
		   $a=$isok['href'];
		   $public=$isok['ispublic'];
		   
		//on affiche tout si admin ou si public et si autorise
		if ($public!='nonpublic' OR $isadmin){
			$classpub=!$public?'':"class='$public'";
			$ret .="<li $classpub>";
			//actif?
			$explo= explode('/',$path_page);
			$classdir = in_array($page, $explo) ? 'actif':'';
			$js = $mode=='suite'?'onmouseover="javascript:swap(\''.$page.'\',\'1\');"':'';
			$ret .="<a href='$a' class='rep $classdir $public' $js >$page</a>";
			 //mode recursif pour menu deroulant
			 if($mode=='suite')
				 $ret .= menu($mode,$hdir."$page/",$page);
			$ret .= "</li>\n";
		}
		
            }
  }//end foreach
  //todo
    if($mode=='not_racine'&& count($files)<3 && !$isadmin) $ret='';
	if($ret!='') $ret = $ulfirt.$ret.$ulast;
	}
return $ret;
}

/*LES IMAGES*/

//joli octet
function in_oct($taille) {
	$taille = ($taille < 1024*1024) ? ((floor($taille / 102.4))/10).' Ko': ((floor(($taille / 1024) / 102.4))/10).' Mo';
	return $taille;
}

//form upload 
if ($dim = _rq('taille','post')) {	
	$_FILES ? $_FILES: $GLOBALS['HTTP_POST_FILES'];
	if(!$source=$_FILES["uploadfile"]["tmp_name"]) exit ;
	$sourcename = $_FILES["uploadfile"]["name"];
	if(!getimagesize($source)){
	if(!$mess = traitedoc($sourcename,$source)) die("doc :(");
	}else{
		if ($dim!="idem"){ 
			$explode=explode('X',$dim);
			$w = $explode[0];
			$h = $explode[1];					 
		} else { 
			$w = "*";  
			$h = "*";
		}
	if(!$mess = traiteim($sourcename,$source,$thispage."_",$w,$h)) die("image :(");
	 }
}

//documents
function traitedoc($name,$source,$extok=array()){
global $thispage;
$path_parts = pathinfo($name);
$ext = $path_parts ? $path_parts['extension'] : '';
$extok=array('rtf','doc','html','txt','pdf');

if (!(in_array($ext, $extok))) {
   return "extension :( $ext";
} elseif ($ext AND preg_match(',^\w+$,',$ext)){ 
	
	if(!is_dir(_DIR_IMG.$ext)) 
	faire('creer',_DIR_IMG,'dossier','',$ext); //create dir
	$dest = _DIR_IMG."$ext/$thispage"."_".$name;
	return deplace_upload($source, $dest);
}
}

//doc secu
function deplace_upload($source, $dest) {
if(!limit_dir($dest, _DIR_IMG)){
	return $mess = _DIR_IMG." impossible";
}else $dest = limit_dir($dest, _DIR_IMG);

		$ok = move_uploaded_file($source,$dest);
		if ($ok) chmod($dest, 0777 & ~0111);
		else unlink($dest);
	return $ok ? $dest : false;
}

//images
function traiteim($name, $im, $prefix,$w, $h){
$quality=60;
$iname = _DIR_IMG.$prefix.$name;

// return array to create image from upload
list($width, $height, $type, $attr) = getimagesize($im);	
	switch($type){
		case 1: $img = ImageCreateFromGif($im); break;
		case 2: $img = ImageCreateFromJpeg($im); break;
		case 3: $img = ImageCreateFromPng($im); break;
		default: die(_T('format_false'));
	}

	if( $width != $w && $height != $h){
	//new dimension
	$wh = get_sizes($width, $height, $w, $h);
	$img_res = im_resize($img, $width,$height,$wh["w"],$wh["h"],$type);
	} else 
	//same dimension
	$img_res = $img;
	
	if ($type=="1") imagegif($img_res,$iname);
	elseif ($type=="3") imagepng($img_res,$iname);
	else ImageJPEG($img_res,$iname, $quality);
	imagedestroy($img_res);
	return $iname;	
}

//ratio //"*" = no resize
function get_sizes($src_w, $src_h, $dst_w,$dst_h ){
	$mlt_w = $dst_w / $src_w;
	$mlt_h = $dst_h / $src_h;

	$mlt = $mlt_w < $mlt_h ? $mlt_w:$mlt_h;
	if(($dst_w == "*" && $dst_h == "*")OR($dst_w>$src_w)OR($dst_h>$src_h)) $mlt=1;

	$img_new_w =  round($src_w * $mlt);
	$img_new_h =  round($src_h * $mlt);
	return array("w" => $img_new_w, "h" => $img_new_h, "mlt_w"=>$mlt_w, "mlt_h"=>$mlt_h,  "mlt"=>$mlt);
}

//resize with good transparence
function im_resize($im_orig,$img_w,$img_h,$img_new_w,$img_new_h,$type=''){
	if(function_exists("imagecreatetruecolor")){
	$im_res = imagecreatetruecolor($img_new_w,$img_new_h) or die(_T('cree_false')."imagecreatetruecolor"); 
	}
	
	if($type=="3") { //png
        imagealphablending($im_res, false);
        $color = imagecolorallocatealpha($im_res, 0, 0, 0, 127);
        imagefill($im_res, 0, 0, $color);
        imagesavealpha($im_res, true);
		}
	
	if($type=="1") //gif
	$im_orig=transparent($im_orig,$im_res);
	
	if(function_exists("imagecopyresampled"))
	imagecopyresampled($im_res, $im_orig, 0, 0, 0, 0,$img_new_w, $img_new_h, $img_w,$img_h) or die(_T('cree_false')." ImageCopyResampled()"); 
	
	return $im_res;
}

//gif
function transparent($im,$res=''){
	$idx = imagecolorallocatealpha($im, 0, 0, 0, 127);
      	if ($idx >= 0) {
		$rgb = imagecolorsforindex($im, $idx);
		$idx = imagecolorallocate($res, $rgb['red'], $rgb['green'], $rgb['blue']);
		imagefill($res, 0, 0, $idx);
		imagecolortransparent($res,$idx);
		imagetruecolortopalette($res, true, 256);
		imagealphablending($res, false); 
		imagesavealpha($res,true); 		 			
	}
	return $im;	
}

//defaut images
if (!is_file($plook)&& is_dir(_DIR_IMG) && is_file(_DIR_TXT.$accfile)){
	//logo
	$im = imagecreate(1100,400);
	$bc =imagecolorallocate ($im, 0,172,207);
	$bf = imagecolorallocate ($im, 49,87,124);
	$wh =imagecolorallocate ($im, 255, 255, 255);	
	
	imagefill($im, 0, 0, $wh);
	imagefilledellipse( $im, 900, 200, 350, 350, $bf );
	imagefilledellipse( $im, 900, 200, 330, 330, $wh );
	imagefilledellipse( $im, 900, 240, 176, 176, $bc );
	imagefilledarc( $im, 900, 80, 272, 300, 54, 126, $bc, IMG_ARC_PIE );

	// + PLOOK
	$foo=im_resize($im,'1100','400','55','20','3');
	$txt = imagecolorallocate ($foo, 109, 159, 175);
 	imagestring($foo, 2, 4, 4, "PLOOK", $txt);
	imagegif($foo, $plook);
	
	// - PLOOK
	$foo = imagecreate(400,400); //crop
	imagecopy($foo, $im, -300, 0, 400, 0, 700, 400);
	$foo=im_resize($foo,'400','400','200','200','3');
	$foo=transparent($foo,$foo);
	imagegif($foo,_DIR_IMG.$accueil."_"."plook.gif");
	imagegif($foo,_DIR_IMG."site_logo.gif");
	
	//favicon
	$im=im_resize($foo,'200','200','32','32','3');
	imagepng($im, _DIR_IMG."favicon.ico" );

	imagedestroy($foo);
	imagedestroy($im);
}

$explorimgs = explorer(_DIR_IMG,"count");
$nbimg_f=count($explorimgs);

/*FIN IMAGES*/


//secu form
function limit_dir($dest, $limit) {
   		if (substr($dest,0,strlen($limit))==$limit)
			return $dest = $limit.preg_replace(',\.\.+,', '.', substr($dest,strlen($limit)));
		else return false;
}

//forms
function faire($action,$path='',$type='',$el='',$file=''){
	if($path=='demo') return;
global $lang, $langfile, $pathrep, $accfile, $lire_meta, $lire_titre_page, $textarea;

$mess=null;

#debug
//$mess.="<br>ACTION=$action <br>PATH= $path  <br>TYPE=$type <br>EL=$el <br>FILE=$file <br>dossier actuel=$pathrep<br>";

//secu
$limit=($type=="image" OR $path==_DIR_IMG)?_DIR_IMG:_DIR_TXT;
if(!limit_dir($path, $limit)){
	return $mess.="$path impossible";
}else $path = limit_dir($path, $limit);

//need clean file
$file=basename($file);

if (get_magic_quotes_gpc()) {
    $file= stripslashes($file);
    $el=stripslashes($el);
}

$concat=$path.$file;
//exist deja
$deja=($el!=$file && $el!='install' && $type!='textarea')?findpath($file):null;
//lien de page
$h=($type=="dossier")?'d':'f';
$a="?$h=".str_replace('.txt','',$file);

switch ($action){
		
	/*case 'zipup':
		global $plook_extern;
		$ext = "$plook_extern/$el";
		$int = _DIR_MODULES."$el";
		//if(file_exists($ext)){
	    if (!copy($ext,$int)) $mess.=_T('copy_false')." $ext -> $int";
	    $zip = new ZipArchive;
	    $zip->open($int);
	    $zip->extractTo(_DIR_MODULES);
	    $zip->close();
	    if(unlink($int)) $mess.=_T('extract_true')." -> $int";
		//} else $mess.=_T('no_exist')." $ext";
	break;*/
	
	case 'creer':
		if($deja) {$mess.="<a href='$a'>$file</a> "._T('existe_deja'); break;}
		if(!strpos($file, 'XX')){
			switch ($type){
			case'fichier':
			if (strrchr($file,'.')!='.txt') return $mess.= _T('conserver_ext')." ".'txt';
					if (!$fd = @fopen($concat,"w+")) $mess.=_T('droits_false')." $path <br />";
					else{
				//	flock($fd,LOCK_SH); // verrou
					if($file==$langfile) $el.="\n"."# "._T('titre_')._T('site_lang');
					if (strrpos($el,"\n")===FALSE) //no "title \n text"
					$el=$el."\n<!non public ("._T('remove_to_publish').")>";
					fwrite($fd, $el);
					$mess.= _T('cree_true')." $concat<h4>$el</h4><a href='$a'>"._T('aller_a_page')."</a>";
				//s	flock($fd,LOCK_UN); //retire verrou
					fclose($fd);
					}
			break;
			
			//creer rep
			case'dossier':
					if (!is_dir($concat)){
					if (!$cd = @mkdir($concat,0777)) $mess.=_T('droits_false')."<br />";
					elseif($el!='install') 
					$mess.= _T('cree_true')." $concat<br /><a href='?d=$file'>"._T('aller_a_page')."</a>";
					}else $mess.=_T('cree_false')."$path<br />"._T('existe_deja');
					
			break;
			}
		}else $mess.=_T('XX_invalid');
	break;
	
	case 'ecrire':
		if ($type=='titresite') {
		$el=$el."\n".$lire_meta;
		$mess.="<a href='?f=site'>"._T('modifier_meta')."</a>";
		}
		
		//titre + texte
		if ($type=="textarea") $el=$lire_titre_page."\n".$el;
	 	elseif ($type=="titre_page") $el=$el."\n".$textarea;
		
		if(!$fp = @fopen($concat,'w+')){ $mess.=_T('droits_false')." $concat"; break;}
		//efface et reinscrit dans l'ordre
		else {
			fwrite($fp,$el);
			fclose($fp);
		}
		
	break;
	
	case 'renommer':	
			if (($type=="dossier")&& is_dir($el)){
			$ok=true;
			}
			elseif (strrchr($file,'.')!=strrchr($el,'.')) return $mess.= _T('conserver_ext')." ".strrchr($el,'.');
			
			if ($type=="image"){
			$el=$path.$el;
			$ok=true;
			}
			
			if ($type=="fichier"){
			if($deja) {$mess.="<a href='$a'>$file</a> "._T('existe_deja'); break;}
			$el=$pathrep.$el;
			$ok=true;
			$mess.="<a href='$a'>"._T('aller_a_page')."</a><br />";
			}
			
			if($ok==true && $name=rename($el,$concat)) $mess.="$el "._T('renomme_en')." $concat";
	break;

	case 'supprimer': 
		if (is_dir($concat)) {if (!$suppr=rmdir($concat)) $mess.=_T('rep_non_vide'); } else $suppr=unlink($concat);
		$mess.=(!$suppr)?" $concat "._T('supprime_false'):"$concat "._T('supprime_ok');
	break;
}

return $mess;
}


//css
$openmodul=_rq('module')?'open':'';
$alertimg=(strrpos($URI,'gerer_docs')!==FALSE)?'alertimg':null;


function hex2bin($s) {
	$bin = '';
	for($i = 0; $i < strlen($s); $i += 2) {
		$bin .= pack("C",hexdec(substr($s, $i, 2)));
	}
	 return $bin;
}

function image($im) {
    $fp = fopen($im,"rb");
    $out = "";
    while (!feof($fp)){
        $buffer = fread($fp, 64);
        $out .= bin2hex($buffer) . "";
    }
    fclose($fp);   
return $out;
}

//css
function compress($buff){
    $buff = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buff);
    $buff = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buff);
return $buff;
}


function headers($content,$path=''){
	global $path_page,  $sitepath, $isadmin;
$image=(strrpos($content,'inline')!==FALSE)?true:null;
$sizim =($image && is_file($path))?getimagesize($path):null;
#ob_start('ob_gzhandler');
if(strpos($content,"text/css")!==FALSE) ob_start('compress');
if($sizim){
header("Content-type: {$sizim['mime']}");
header("$content");
}
	if (!$path OR !is_file($path)) $path=$path_page; //pour css.txt
	if((!$image)&& !headers_sent()) header("$content"); //ok
	//optionnel
	if(!headers_sent()&& file_exists($path) && is_file($sitepath)){
		$tf = filemtime($path);
		$ts = filemtime($sitepath);
		$tsc=($sizim)?$tf:filemtime(s('SCRIPT_FILENAME'));
#script > sitepath > path
		$t=($tf>$ts)?$tf:$ts; 
		$t=($tsc>$t)?$tsc:$t; 
			$modif = gmdate('D, d M Y H:i:s', $t);
			$etag = '"'.md5($path.$isadmin.$t.$content).'"';
		if (!$isadmin) header('Cache-Control: must-revalidate', TRUE);
		else header('Cache-Control: no-cache, must-revalidate', TRUE);
		header('ETag: ' .$etag, TRUE);
		header("Last-Modified: " .$modif. ' GMT', TRUE);
//envoi de ico		
		if($sizim){
//header("Expires: Mon, 26 Jul 2040 05:00:00 GMT");
		echo hex2bin(image($path));
		header('Content-Length: '.ob_get_length());
		ob_end_flush();
		exit;
		}
	}
}

/* + appel modules */
$freemodules=_DIR_MODULES."1nc/1nc_modules.php";
if(is_file($freemodules)) require_once($freemodules);
$langinstall=function_exists('traduire')&& !is_file(_DIR_CONF.$sitefile)?true:false;

/* textes par defaut */
$Tsite=_T('Plook_'); //titre site
$Tmeta=_T('plook_le_cms_facile'); // meta du site
$Taccueil=_T('accueil'); //titre accueil
$Tinstall=_T('bienvenue_sur_votre_site');//txt install
$Tmodif="\n"."<a href='admin/?$STRING'>"._T('modifier')."</a>";
$credit="<a href='http://plook.fr' title='plook cms'><span>". _T('plook_cms') ."</span></a>";

$head_commun="
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<meta http-equiv='content-language' content='$lang' />
	<meta name='robot' content='follow, index, all' />
	<meta name='generator' content='PLOOK CMS' />
	<link rel='start' title='$Taccueil' href='$url_site' />
	";


/* install */
if (!is_dir(_DIR_IMG)) 
$pipecontent .=faire('creer',_DIR_IMG,'dossier','install');
else $imagier=imagier();

if(!is_dir(_DIR_TXT)){	
	$pipecontent .=faire('creer',_DIR_TXT,'dossier','install');
	} else {
		if(!is_dir(_DIR_CONF))
		$pipecontent .=faire('creer',_DIR_CONF,'dossier','install');
	}

	if($langinstall) {
	//todo rediriger entete
	$pipecontent.=menu_languesT($lang);
	if(_rq('choixlang')){
		faire('creer',_DIR_CONF,'fichier',$Tsite."\n".$Tmeta,$sitefile);
		//NO GOOD $pipecontent.=faire('creer',_DIR_CONF,'fichier',$Tsite."\n".$Tmeta,$sitefile);
		if(!is_file(_DIR_TXT.$accfile)) faire('creer',_DIR_TXT,'fichier',$Taccueil."\n".$Tinstall."\n".$Tmodif,$accfile);
	}
	}else{
	 if(!is_file(_DIR_CONF.$sitefile)) faire('creer',_DIR_CONF,'fichier',$Tsite."\n".$Tmeta,$sitefile);
	 if(!is_file(_DIR_TXT.$accfile)) faire('creer',_DIR_TXT,'fichier',$Taccueil."\n".$Tinstall."\n".$Tmodif,$accfile);
	}

$metactif=$thispage=="site" ? "class='actif'":'';
$lien_modules.="<p $metactif><a href='?f=site&amp;module=view'>"._T('meta_desc')."</a></p>";

/* alert */
if($mess)
$mess= '<div id="alert" class="alert '.$alertimg.'"><a href="javascript:swap(\'alert\',\'0\');" class="close">x</a><span>'.$mess.'</span></div>';
?>