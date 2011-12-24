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

//fichier de log remplit automatiquement
//modules/module_log.php est appellé par modules/modules_plus.php
//secu
if (!defined("_DIR_TXT")) return;

//On ajoute le lien module
$classlog=$thispage=="log" ? "class='actif'":'';
$lien_modules.="<p $classlog><a href='?f=log&amp;module=view'>"._T('log')."</a></p>";

$logpath=_DIR_CONF."log.txt";
if(!is_file($logpath)) faire('creer',_DIR_CONF,'fichier',"Log PLOOK"."\n"."log!","log.txt");

/* $errno : type de l'erreur
$errstr : message d'erreur
$errfile : fichier correspondant à l'erreur
$errline : ligne correspondante à l'erreur */

function recuperer_erreur($errno,$errstr,$errfile,$errline)
{
    
    // On définit le type de l'erreur
	switch($errno)
	{
		case E_USER_ERROR :
			$type = "Fatal:";
			break;
		case E_USER_WARNING :
			$type = "Erreur:";
			break;
		case E_USER_NOTICE :
			$type = "Warning:";
			break;
		case E_ERROR :
			$type = "Fatal";
			break;
		case E_WARNING :
			$type = "Erreur:";
			break;
		case E_NOTICE :
			$type = "Warning:";
			break;
		default :
			$type = "Inconnu:";
			break;
	}

	// On définit l'erreur.
	$erreur = "\n".$type." Message d'erreur : [".$errno."]".$errstr.
	"\n"."Ligne :".$errline."\n"." Fichier :".$errfile."\n";

	/* Pour passer les valeurs des différents tableaux, fonction serialize()
        Le rapport d'erreur contient le type de l'erreur, la date, l'ip, et les tableaux. */

	$info = "log PLOOK"."\n". date("d/m/Y H:i:s",time()).
	":".$_SERVER['REMOTE_ADDR'].
	"GET:".serialize($_GET)."\n".
	"POST:".serialize($_POST)."\n".
	"SERVER:".serialize($_SERVER)."\n".
	"COOKIE:".(isset($_COOKIE)? serialize($_COOKIE) : "Undefined")."\n".
	"SESSION:".(isset($_SESSION)? serialize($_SESSION) : "Undefined")."\n";
	
	//que les erreurs
	$info="log PLOOK"."\n". date("d/m/Y H:i:s",time());
	
	$logpath=_DIR_CONF."log.txt";
	// On ouvre le fichier
	//au dela de 10000 oct on efface tout pour réecrire
	$poids =filesize($logpath);
	if ($poids<10000)
	$handle = fopen($logpath, "a");
	else 
	$handle = fopen($logpath, "r+");

	// On écrit
	if ($handle){
	fwrite($handle,$info."\n".$erreur."\n poids= ".$poids." oct \n");
	fclose($handle);
	}
}

error_reporting(E_ALL);
set_error_handler('recuperer_erreur');

?>
