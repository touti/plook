<?php
require_once("function.inc.php");
headers("Content-Type: text/html; charset=utf-8",$path_page);

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

?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<?php echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='$lang' lang='$lang' dir='$ltr'>\n";?>
<head>
	<?php echo "
	<title>$lire_titre_site "._T('modif_actuel')." $lire_titre_page</title>
	$head_commun \n
	<link rel='shortcut icon' href='../images/favicon.ico' type='image/x-icon' />
	<link href='"._DIR_RACINE."css_base.php?lang=$lang&amp;admin' rel='stylesheet' type='text/css' media='projection, screen, tv' />
	<script type='text/javascript' src='"._DIR_RACINE."js_base.php?lang=$lang&amp;admin'></script>
	$pipe_head \n";
	?>
	
</head> 
	
<body id="adminbody" class='lang-<?php echo "$lang $thispage"; ?>'>
<?php	
//menu actions
$ask=$URI;
$ask.= strstr($ask,'?') ? '&amp;' : '?';
//voir?
$askvoir=(_rq('voir'))?"":$ask."voir=oui";
$lien=(_rq('gerer_docs'))?"":$ask."gerer_docs=oui";

//meme sans JS
$open1=_rq('deplier')==1?"class='open1'":'';
$open2=_rq('deplier')==2?"class='open2'":'';
$open3=_rq('deplier')==3?"open3":'';

echo "
<ul id='navact'>
<li><a href='../?".$STRING."'>&#8592; "._T("voir_page_publique")."</a></li>
<li><a href='".$ask."deplier=1'>+ "._T("ajouter_une_page")."</a></li>
<li><a href='".$ask."deplier=2'>&#8595; "._T("modifier_cette_page")."</a></li>
<li><a href='$lien#documents'>&#9788; "._T('documents')."</a></li>
<li><a href='".$ask."deplier=3'>&#9674; "._T('modules_')."</a></li>
<li><a href='protect.php'>&#3665; "._T('mot_de_passe_')."</a></li>
</ul>

<div class='depli'>";
//creer page
		echo"<div id='deplier1' $open1>
		<form action='$URI' method='post'>
		 <p>
		<label>"._T('titre_')."</label>
		<input type='text' name='titrenew' value='"._T('nouvelle_page')."' size='20' />
		<label>"._T('nom_fichier_')."</label>
		<input type='text' name='filenew' value='pageXX.txt' size='20' />
		<input type='hidden' name='type' value='fichier' />
		<label> "._T('dans_dossier')." </label>
		<select name='dossier' size='1'>
		<option value='"._DIR_TXT."'>"._T('racine_')."</option>"
		.replist().
		"</select>
		<input type='submit' name='creer' value='"._T('creer_')."' class='cree'/>
		</p>
		</form>";
//creer rep
		echo"<hr class='clear' />";
		
		echo"
		<form action='$URI' method='post'>
		 <p>
		<label class='rep'>"._T('nom_dossier_')."</label>
		<input type='text' name='filenew' value='"._T('dossierXX')."' />
		<input type='hidden' name='type' value='dossier' />
		<label> "._T('dans_dossier')." </label>
		<select name='dossier' size='1'>
		<option value='"._DIR_TXT."'>"._T('racine_')."</option>"
		.replist().
		"</select>
		<input type='submit' name='creer' value='"._T('creer_')."' class='cree'/>
		</p>
		</form>
		</div>
		";

//modif page
		if($path_page){
		echo "<div id='deplier2' $open2>";
	if($thispage){
		echo "
		<form action='$URI' method='post'>
		<p>
		<label>"._T('titre_')."</label>
		<input type='text' name='titrenew' value='$lire_titre_page' class='yel' size='18' />
		<input type='hidden' name='titreold' value='$lire_titre_page' />
		<label>"._T('nom_fichier_')."</label>
		<input type='text' name='filenew' value='$thispage.txt' size='18' />
		<input type='hidden' name='elem' value='$thispage.txt' />
		<input type='hidden' name='type' value='fichier' />";
		//fichiers de base
if (($thispage!=$accueil)&&($pathrep!=_DIR_CONF)){
		echo"
		<label>"._T('dans_dossier')."</label>
		<select name='dossier' size='1'>
		<option value='"._DIR_TXT."'>"._T('racine_')."</option>'
		".replist()."
		</select>";		
}else echo "<input type='hidden' name='dossier' value='$pathrep' />";
		echo"
		<input type='submit' name='rename' value='"._T('renommer_')."' class='renomme' />
		<input type='submit' name='suppr' value='x "._T('supprimer_')."' class='supprime' />
		</p>
		</form>";
	}
		
//modif rep
	if($thisrep && $pathrep!=_DIR_CONF){
		echo "
		<form action='$URI' method='post'> 
		 <p>
		<label class='rep'>"._T('nom_dossier_')."</label>
		<input type='text' name='filenew' value='$thisrep' size='18' />
		<input type='hidden' name='elem' value='$pathrep' />
		<input type='hidden' name='type' value='dossier' />
		<label> "._T('dans_dossier')." </label>
		<select name='dossier' size='1'>
		<option value='"._DIR_TXT."'>"._T('racine_')."</option>"
		.replist('rep'). //rep parent en select
		"</select>
		<input type='submit' name='rename' value='"._T('renommer_')."' class='renomme'/>
		<input type='submit' name='suppr' value='x "._T('supprimer_')."' class='supprime' />
		</p>
		</form>";
	}

	echo"</div>";
		}
		
//modules
		echo"<div id='deplier3'  class='modul $openmodul $open3'>
		$lien_modules
		<br class='clear' />
		</div>";
		//<a href='$plook_extern'>"._T('lien_modules')."</a>
		
		/*
		<form action='$URI' method='post'>
		<p>
		<label>"._T('download_module')."</label>
		<input type='text' name='zipup' value='$module' class='yel' size='20' />
		<input type='submit' value='"._T('telecharger_')."' class='valider' />
		</p>
		</form>
		*/
?>
</div>

<div id="page">
<?php
echo"<div id='entete'>";
//alert
echo $mess;

//titre du site
echo "<form action='$URI' method='post' class='textetitre'>
	<p>
	<input type='text' name='titresite' size='29' class='bigtitre' value='$lire_titre_site' />
	<input type='submit' value='"._T('valider_le_titre_du_site')."' class='valider' id='vtit' />
	</p>
	</form>";

//menu de navigation
echo "<div id='nav'>".$nav."</div>";

echo "</div>";
	
//texte
echo "<div>
<div class='minheight'> </div>
	<div id='content' class='contentprive'>";

//menu sous rep
echo "<div id='adm'>$ariane ".menu('not_racine',$pathrep);
echo $admin_message;
echo"</div>";


if($path_page && !is_dir($path_page)){ //si page existe mais n'est pas un dossier 
	$hide=($pathrep==_DIR_CONF)? 'hide':'';
	
	if (_rq('voir')){
	echo"<div id='voirpage'>
	<div class='voir'>".lire()."$pipecontent_admin</div>
	<a href='?f=$thispage'>"._T('modifier')."</a>
	</div>";
	} else {
	$hjs="<a href=\"javascript:rTypo(document.getElementById('text_area')," ;
	echo " <form action='$askvoir' method='post' id='editpage'>	
		<p class='toolbar $hide'>
		$hjs'strong','strong');\"><strong>"._T('G_')."</strong></a> 
		$hjs'em','em');\"><em>"._T('I_')."</em></a> 
		$hjs'lien','"._T('saisir_adresse')."');\">"._T('lien_')."</a> 
		$hjs'mail','"._T('saisir_email')."');\">"._T('email_')."</a> 
		$hjs'h2','h2');\"><strong>"._T('h2')."</strong></a> 
		$hjs'!non public ("._T('remove_to_publish').")', '');\" class='toolpublic'>"._T('nonpublic')."</a> 
		</p>
		<p>
		<textarea name='texte' id='text_area' class='fondtextarea' rows='20' cols='50' >$textarea</textarea>
		<input type='submit' value='"._T('valider_ce_texte')."' class='valider' />
		</p>
		</form>
		";	
	}
}

//si page existe
if(is_dir($path_page)) echo "<div class='blocinfo infodos'><div class='info'>*</div>"._T('info_dossier')."</div>";
if(!$path_page) echo "<div>$textarea</div>"; //404


if($path_page && !is_dir($path_page) && $pathrep!=_DIR_CONF){
//images de la page
$nb=($nbimg_f==0)?'noimage':'';
echo "<div class='docs docsprive $nb'>".imagier('page')."</div>";
$nbi=($nbimg_f>0)?_T('pour_cette_page', array('nombre' => $nbimg_f)):_T('aucun_document');

//upload
echo "
<div class='blocinfo infoimg'>
<div class='info'>&#9788;</div>$nbi<br />"._T('choisir_vos_documents')."
<form action='$URI' method='post' enctype='multipart/form-data' class='upload'>
	<div>
	<input maxlength='128' name='uploadfile' type='file' />
   <p><label>"._T('taille_')."</label>
   <select name='taille' size='1' dir='ltr'>
   		<option value='800 X 600'>800 X 600</option>
   		<option value='400 X 300'>400 X 300</option>
		<option value='200 X 150'>200 X 150</option>
		<option value='32 X 32'>32 X 32 (ico)</option>
		<option value='idem'>"._T('meme_dimension')."</option>
   </select>
	<input type='submit' value='"._T('telecharger_')."' />
	<input type='reset' value='"._T('annuler_')."' />
	</p></div>
</form>
</div>";

//info page
echo "<div class='blocinfo infopage'> 
	<div class='info'>*</div>"
	._T('modif_actuel')." ".$lire_titre_page."<br />"
	._T('nom_fichier_')." $thispage.txt<br />"
	._T('modif_le')." ".date("d-m-Y - H".'\h'.":i", filemtime($path_page))
	."</div>";
}

echo "</div></div>";


echo "<div id='extra'> </div>
	<div id='footer'> </div>";			

//dossier images
if(_rq('gerer_docs')){
echo "
<div id='documents'>	
<a href='?f=$thispage' class='gestionimg'>&#9788; "._T('fermer_documents')."</a>
	<div class='geredossierimage'>";
		//info dossier
		echo"<div class='blocinfo'>
		<div class='info'>*</div>
		<div class='txtleft'>
		<h4>"._T('nommer_images')."</h4>
		"._T('info_nommer_images') ." "._T('exemple_')." <strong>$thispage"."_image.png</strong>
		</div>
		</div>";
		echo"<div id='gerimgs'>".imagier('all')."<br class='clear' /></div>
		
	</div>
</div>
";
}

?>
</div><!-- fin #page -->
</body> 
</html>
