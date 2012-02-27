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


//secu
if (!defined("_DIR_TXT")) return;

//on ecrit le resultat dans config/rss.xml.txt
//il faut supprimer le fichier pour le reecrire! bof (todo?)
//on regarde si il y a changement dans les fichiers et on le dit
$pathlogo=$url_site.str_replace('../','',$sitelogo);
$ask_rss="rss.xml";
$page_rss=_DIR_CONF."$ask_rss.txt";
$content_rss=do_rss();

//on verifie que accueil existe
if(!is_file($page_rss) && is_file($sitepath)){
//$textarea=null; //todo sinon inscrit 404
faire('creer',_DIR_CONF,'fichier',"rss"."\n".$content_rss,"$ask_rss.txt");
}


//automatic header only for public page
$pipeheader=pipeheader($page_rss);
//pipe insert into <head>
$pipe_head.='<link rel="alternate" type="application/rss+xml" title="Flux RSS" href="'.$url_site.'?f='.$ask_rss.'" />';

/* liens admin */
if($thispage==$ask_rss){
	//on met un message dans admin
	$admin_message.="<h4 class='clear'>module_rss ($thispage)</h4>
	<span class='clear red'>".difference()."</span>
	<span>"._T('rss:supprimer_pour_recreer')."</span>";
}

//Ajoute le lien dans la page des modules
$classactif=$thispage=="rss.xml" ? "class='actif'":'';
$lien_modules.="<p $classactif><a href='?f=rss.xml&amp;module=view'>"._T('RSS')."</a></p>";

/* functions */
function difference(){
	global $page_rss,$content_rss;
	$rss_encache=lire('nobr',$page_rss);
	if($rss_encache!=''){
	$contenu_rss=preg_replace("(\r\n|\n|\r)",'',$content_rss);
	$rss_encache=preg_replace("(\r\n|\n|\r)",'',$rss_encache);
	if($contenu_rss!=$rss_encache){
		return _T('rss:version_differente');
	}
	} 
}


function pipeheader($page_rss){
	global $URI, $ask_rss;
	$foradmin = (strrpos($URI,'admin')!==FALSE) ?'oui':'';
	$r=_rq('f');
	if (is_file($page_rss) && $r==$ask_rss) { 
		if(!$foradmin && !headers_sent()){
		ob_start('ob_gzhandler');	
		header("Content-disposition: inline; filename=$page_rss; Content-Type: application/rss+xml;");
		echo lire('nobr',$page_rss);
		ob_end_flush();
		}
		else return;
	}
	
}


function do_rss(){
global $lang,$lire_titre_site,$url_site,$lire_meta,$pathlogo;
$items=items();
if(!isset($items)) return false;
$ret='<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" 
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
>';
$ret.='<channel xml:lang="'.$lang.'">
<title>'.$lire_titre_site.'</title>
<link>'.$url_site.'</link>
<description>'.$lire_meta.'</description>
<language>'.$lang.'</language>
<generator>PLOOK www.plook.fr</generator>
';
$ret.='<image>
	<title>'.$lire_titre_site.'</title>
	<url>'.$pathlogo.'</url>
	<link>'.$url_site.'</link>
	<height>200</height>
	<width>200</width>
</image>
';

$ret.= $items;

$ret.= "
</channel>
</rss>";
return $ret;
}


function clean($texte) {

	static $apostrophe = array("&#8217;", "'"); # n'allouer qu'une fois
	$texte = preg_replace(",<[^>]*>,US", " ", $texte);
	// ne pas oublier un < final non ferme
	// mais qui peut aussi etre un simple signe plus petit que
	$texte = str_replace('<', ' ', $texte);
	// echapper les tags &gt; &lt;
	$texte = preg_replace(',&(gt|lt);,S', '&amp;\1;', $texte);

	// " -> &quot; et tout ce genre de choses
	$texte = str_replace("&nbsp;"," ", $texte);

	// l'apostrophe curly pose probleme a certains lecteure de RSS
	// et le caractere apostrophe alourdit les squelettes avec PHP
	// ==> on les remplace par l'entite HTML
	$texte = str_replace($apostrophe, "'", $texte);
	return htmlspecialchars(stripslashes($texte));
}



function recursif($path){
	if(is_dir($path)){
	$data = array();
        $dir = opendir($path);
				
	$interdit = (substr($path, -strlen(_DIR_CONF))==_DIR_CONF) ?'stop':'';
        while (($el = readdir($dir))!== FALSE && $interdit!='stop'){
		if ($el != '.' && $el != '..' && $el !='.DS_Store'){
			
		if (is_file($path.$el)){
                        //si fichier, on enregistre dans le tableau SSI public ok
			if(strrchr($el,'.')==".txt") 
			$titre=lire("testpublic",$path.$el)?'':lire("titre",$path.$el);
			if ($titre){
			$time = filemtime($path.$el);
			$data[] = array('file' => $el, 'time' => $time,'dossier'=>$path);
			}	
			
                }	
			
                //si dossier rappel recursif en modifiant la racine du dossier à ouvrir
                elseif(is_dir($path.$el.'/')){
		  $isok=isrepok($path.$el.'/');
			$public=$isok['ispublic'];
		
                        //fusion des résultats récursifs SSI public ok
			if ($public!='nonpublic'){
			$data= array_merge($data, recursif($path.$el.'/'));
			}
                }
		
		
		}
        }
        closedir($dir);
        return $data;
	}
}

//les documents 
# todo etendre aux sous dossiers
function docpage($page){
	global $url_site;
	$ret=NULL;
	$explorimg = explorer(_DIR_IMG);
	$array=$explorimg['files'];
	foreach($array as $file) {
		$fichier=_DIR_IMG.$file;
		$fs_res= filesize($fichier);
		$ext=str_replace('.','',strrchr($file,'.'));
		$type="image/$ext"; //todo!
		$url=$url_site."images/$file";
		$page=str_replace('.txt','',$page);
		$nam=explode('_',$file);
		if (count($nam)>0) $p=$nam[0];
		if($p==$page){
	$ret.='<enclosure url="'.$url.'" length="'.$fs_res.'" type="'.$type.'" />';	
		}		
	}
	return $ret;
}

//un item = un fichier texte
function items(){
		global $lang,$url_site;
	$files = recursif(_DIR_TXT);
	$text=NULL;
	$ret=NULL;
	foreach ($files as $key => $row) {
	    $text[$key]  = $row['file'];
	    $heure[$key] = $row['time'];
	    $dossier[$key] = $row['dossier'];
	}
	
	if (!is_array($text)) return;
	// ranger par date
	array_multisort($heure, SORT_NUMERIC , SORT_DESC, $text, SORT_ASC, $dossier, SORT_ASC, $files);
	
	foreach ($files as $key => $row) {
		$date =date('D, d M Y H:i:s O', $heure[$key]);
		$page = $text[$key];
		$lien=$url_site."?f=".str_replace('.txt','',$page);
		$chemin=findpath($page);
		$titre=lire('titre',$chemin);
		
	$ret .='
	<item xml:lang="'.$lang.'">
	<title>'.$titre.'</title>
	<link>'.$lien.'</link>
			<guid isPermaLink="true">'.$lien.'</guid>
			<dc:date>'.$date.'</dc:date>
			<dc:format>text/html</dc:format>
			<dc:language>'.$lang.'</dc:language>
			<dc:creator>plook</dc:creator>
	<description>'.clean(lire('public',$chemin)).'</description>
	'.docpage($page).'
	</item>
	';
	
	}
	return $ret;
}

?>

