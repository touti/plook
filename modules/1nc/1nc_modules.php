<?php 
/************************************************\
 *  PLOOK					*
 *                                              *
 *  Copyright 2004-2010     			*
 *  Anne-lise Martenot, http//plook.fr		*
 *                                            	*
 *  Licence GNU/GPL.     			*
 *   						*
\************************************************/
/*		PLOOK 1.3 - janv 2010 		*/

//vous etes sur la page maitresse des modules
//module_plus.php surcharge par include admin/function.inc.php
//tout fichier module_nom.php dans le dossier modules est inclut automatiquement via une liste positive modifiable en ligne
//creez vos modules sur la base de ceux existants !

//secu
if (!defined("_DIR_TXT")) return;


//liste des modules dans config/1iste_modules.txt pour les inclure suivant choix utilisateur

$liste_modules=liste_modules();
$m_supp_page="1iste_modules";
$m_supp_file="$m_supp_page.txt";

if (is_dir(_DIR_CONF)){
if(!is_file(_DIR_CONF.$m_supp_file)) 
faire('creer',_DIR_CONF,'fichier',$m_supp_page."\n"."#liste des modules \n \n".$liste_modules."\n",$m_supp_file);
	else
	{
	//on demande inclusion des modules
	$listvalid =list_valid(_DIR_CONF.$m_supp_file);
		foreach ($listvalid as $modulvalid) {
		if(is_file(_DIR_MODULES.$modulvalid))
		require_once(_DIR_MODULES.$modulvalid);
		}
	}

}

//les modules actifs ayant une css
$cssmodules=css_modules();

$manque_module=manque_module();
$autres_modules=($manque_module)?"Autres modules disponibles:<br />".$manque_module:'';

//On affiche le lien dans la page des modules
$listactif=$thispage=="$m_supp_page" ? "class='actif'":'';
$lien_modules="<p $listactif><a href='?f=$m_supp_page&amp;module=view'>"._T('modules')."</a></p>".$lien_modules;


if($thispage==$m_supp_page){
	//on met un message dans admin
	$admin_message.="<h4 class='clear'>module_plus ($thispage) </h4>
	<ul>
	<li>"._T('1nc:Desactiver_avec_diese')." </li>
	<li>"._T('1nc:Supprimer_pour_recreer')."</li>
	</ul>
	<hr />
	$autres_modules
	";
}



function recursif_modules($path){
	if(is_dir($path)){
	$data = array();
        $dir = opendir($path);

        while (($el = readdir($dir)) !== FALSE){
		if ($el != '.' && $el != '..' && $el !='.DS_Store'){
			
		if (!is_dir($path."/".$el)){
			$countlettre=strlen(_DIR_MODULES)+1;
			$diroui=(substr($path, $countlettre)=='')?'':substr($path, $countlettre)."/";
			//On verifie que le debut commence par module_
			$isok = (strrpos($el,"module_")===FALSE) ?'non':'oui';
			if($el!="module_plus.php" && $isok=='oui')
			$data[] =$diroui.$el;	
                }	
                //si dossier rappel recursif
                else{
			$data= array_merge($data, recursif_modules($path."/".$el));
                }
		
		
		}
        }
        closedir($dir);
        return $data;
	}
}

function liste_modules(){
	$files = recursif_modules(_DIR_MODULES);
	$ret=NULL;
	foreach ($files as $page) {
		$ret.="$page \n";
	}
	return $ret;
}

//recupere et agrege les css
function css_modules(){
	global $m_supp_file;
	$listvalid =list_valid(_DIR_CONF.$m_supp_file);
	$ret=NULL;
	foreach ($listvalid as $page) {
		$explo=explode('/',$page,2);
		$module=$explo[0];
		$filecss=_DIR_MODULES.$module."/css_".$module.'.php';
		if(is_file($filecss))
		$ret.=lire('nobr',$filecss)."\n";
	}
	return $ret;
}



function manque_module(){
	global $m_supp_file;
//on compare les 2 array
//dossier modules
$listdispo = recursif_modules(_DIR_MODULES);
//fichier de conf
$listvalid =list_valid(_DIR_CONF."$m_supp_file");
	$ret=NULL;
	foreach ($listdispo as $module) {
		if (in_array("#".$module, $listvalid)) {
			#$ret.= "$module invalid <br />";
		}elseif (!in_array($module, $listvalid)) {
			$ret.= "$module<br />";
		}
	}
return $ret;
}

//array des modules non desactives
function list_valid($pathfile){
	$array = array();
	if(is_file($pathfile)){
	if ($f = fopen($pathfile, 'r')) 
	do {
	    $line = trim(fgets($f));
	    //php ok
	    $line=(substr($line,-4)==".php")?$line:'';
	    //no # ok
	    #$line=(strpos($line,"#")===FALSE)?$line:''; 
	    //go 
	    if($line!='') $array[] = $line;
	    
	} while (!feof($f));
	fclose($f);
	}
	return $array;
}



//qqs exemples possibles (pour tester supprimer le # devant)

//suppression du fil  d'ariane
#$ariane='';

//surcharge bloc extra
#$pipe_extra.='texte suppl&eacute;mentaire de pipe_extra';

//surcharge bas de page
#$pipe_footer.='texte suppl&eacute;mentaire de pipe_footer';

//surcharge de la balise head
#$pipe_head.='<link rel="alternate" type="application/rss+xml" title="Syndiquer tout le site" href="http://plook.elastick.net" />';


//css.txt (+2 css via plook)
/*
+modules/css_title.php == title of css.txt
entrez via plook vos class persos dans le champ texte de css.txt
construction sur modele http://blog.html.it/layoutgala
*/

//on envoie le pipeline
//previsu admin
$pipecontent_admin=$pipecontent;
//test public?
if(lire()=='') $pipecontent=NULL;

?>
