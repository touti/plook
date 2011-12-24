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

//secu
if (!defined("_DIR_TXT")) return;

$cssfile="css.txt"; //son titre dŽfinit la surcharge css

if(!is_file(_DIR_CONF.$cssfile)) 
faire('creer',_DIR_CONF,'fichier',"css_+name"."\n"."/* css+ */",$cssfile);

if($thispage=="css"){
//on met un message dans admin
$admin_message.="<h4 class='clear'>module_css ($thispage)</h4>
<ul>
<li>"._T('css:surcharge_css_titre')."</li>
<li>"._T('css:surcharge_css_texte')."</li>
</ul>";

}

//affichage dans css_base.php
$cssplus=quel(_DIR_CONF,$cssfile,'css');
$lirecss="\n".lire('nobr',_DIR_CONF.$cssfile);

//On ajoute le lien dans les modules
$cssactif=$thispage=="css" ? "class='actif'":'';
$lien_modules.=(is_dir(_DIR_MODULES."css/"))?"<p $cssactif><a href='?f=css&amp;module=view'>"._T('css')."</a></p>":'';

?>
