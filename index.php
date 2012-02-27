<?php
$debut = microtime(true);
require_once("admin/function.inc.php");
if($im=_rq('i')) headers('Content-Disposition: inline; filename='.$im,_DIR_IMG.$im); //favicon
else headers("Content-Type: text/html; charset=utf-8",$path_page);

/************************************************\
 *  PLOOK					*
 *                                              *
 *  Copyright 2004-2012     			*
 *  Anne-lise Martenot, http//plook.fr		*
 *                                            	*
 *  Licence GNU/GPL.     			*
 *   						*
\************************************************/

?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<?php echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='$lang' lang='$lang' dir='$ltr'>\n"; ?>
<head>
	<?php echo "
	<title>$lire_titre_site - $lire_titre_page</title>
	<meta name='description' content='$lire_meta' />
	$head_commun \n
	<link rel='shortcut icon' href='?i=favicon.ico' type='image/x-icon' />
	<link href='css_base.php?lang=$lang' rel='stylesheet' type='text/css' media='projection, screen, tv' />
	<script type='text/javascript' src='js_base.php'></script>
	$pipe_head \n";
	?>
</head>
<body class='lang-<?php echo "$lang $thispage"; ?>'>
<div id="page">

	<div id="entete">
	<h1><?php  echo "<a href='$url_site' rel='start home'>".$lire_titre_site."</a>"; ?></h1>

	<div id='nav'><?php echo $nav ?></div>
	</div>

	<div id="conteneur">
		<div class="minheight"> </div>
		<div id="content">
<?php

//docs de la page
		if($nbimg_f>0){
			$overflow=($nbimg_f>3)?"overflow":NULL;
			echo "<div class='docs $overflow'>$imagier</div>" ;
		}
//ID ariane
		echo $ariane;		
		
//miniplan si sous dossier
		echo $navsous;
		
//texte de la page
		echo $pagetext;
		
//pipeline
		echo $pipecontent;

?>
		</div>
	</div>
	
	<?php if(isset($pipe_extra)) echo "<div id='extra'>$pipe_extra</div>" ?>

	<div id="footer">
	<div id='toadmin'><?php echo $Tmodif ?></div>
	<div id='credit'><?php echo $credit ?></div>
	<?php echo $pipe_footer ?>
	</div>

</div>
<p>
<?php
//Temps d'execution
$fin = microtime(true);
echo 'Temps d\'execution : '.round($fin - $debut, 4);
?>
</p>
</body></html>
