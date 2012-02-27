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


//modules/module_contact.php est appellé par module_plus.php
//secu
if (!defined("_DIR_TXT")) return;


$mailpage="contact_dest";
$mailfile="$mailpage.txt"; //fichier contenant le mail de contact
$mailfilepath=_DIR_CONF."$mailfile";
//mail par defaut, modifier via plook /?f=destmail
$dest='toutati@free.fr';
//la page contenant le formulaire 'mail-contact'
$pagecontact="mail-contact"; 
$pagecontactfile="$pagecontact.txt";
$pagecontactpath=_DIR_TXT.$pagecontactfile;

//creation des fichiers
if (is_dir(_DIR_CONF)){
if(!is_file($pagecontactpath)) 
faire('creer',_DIR_TXT,'fichier',"Contact",$pagecontactfile);
if(!is_file($mailfilepath)) 
faire('creer',_DIR_CONF,'fichier',"contact_dest"."\n"."contact@plook.fr",$mailfile);
else $dest=lire("nobr",$mailfilepath);
}

//On ajoute le lien module
$contactactif=$thispage==$mailpage ? "class='actif'":'';
$lien_modules.="<p $contactactif><a href='?f=".$mailpage."&amp;module=view'>"._T('contact:email')."</a></p>";

if($thispage==$mailpage){
//on met un message dans admin
$admin_message.="<h4 class='clear'>module_contact ($thispage)</h4>
<span class='clear'>&#60; "._T('contact:destinataire')."</span>
<span class='clear'><a href='?f=$pagecontact'>"._T('contact:retour_form')." $pagecontact</a></span>";
}

if($thispage==$pagecontact){
//on met un message dans admin
$admin_message.="<h4 class='clear'>module_contact ($thispage) </h4>
<span class='red'>"._T('contact:inclusion_formulaire_contact')."</span>
<span class='clear'><a href='?f=$mailpage&amp;module=view'>"._T('contact:destinataire')." $dest</a></span>";


$texte_m=null;
$email_m=null;
$sujet_m=null;
$ok=null;
	
//traitement du formulaire
//un 2em parametre refuse le get
if($envoi=_rq('envoi','post')){
	$texte_m=_rq('texte_m');
	$email_m=_rq('email_m');
	$sujet_m=_rq('sujet_m');
	$date=_rq('date');
		$email_regex = '`^[[:alnum:]]([-_.]?[[:alnum:]_?])*@[[:alnum:]]([-.]?[[:alnum:]])+\.([a-z]{2,6})$`';
		if (!$texte_m OR !$email_m OR !$sujet_m) $pipecontent.= "<div class='red'>"._T('contact:champ_oubli')."</div>";
		elseif (!preg_match( $email_regex , $email_m) ) {
		$pipecontent.=  "<div class='red'>"._T('contact:email_invalid')."</div>";
		}
		else { 
			$m=$date."\n".dop(nl2br(htmlspecialchars($email_m)))."\n".dop(nl2br(htmlspecialchars($sujet_m)))."\n".dop(nl2br(htmlspecialchars($texte_m)));
			  $header = 'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset=UTF-8'
    . "\n" . 'From: '.$email_m.'<' . $email_m . ">\n";
    if(mail($dest, '=?UTF-8?B?'.base64_encode("[$lire_titre_site] ".$sujet_m).'?=', $m, $header)) {
			$pipecontent = "<div class='red'>"._T('contact:merci')."</div>";
			$ok=true;
			}else $pipecontent.= "<div class='red'>"._T('contact:envoi_impossible')."</div>";
		}
}
	
//le form
if(!$ok)
$pipecontent.="
<form action='$URI' class='formcontact' method='post' enctype='multipart/form-data'>	
<input name='date' id='date' value='".date("d-m-Y - H".'\h'.":i")."' type='hidden' />	
	<fieldset>
		<legend>"._T('contact:envoyer_message')."</legend>
		<ul>
			<li>
			<label for='email_m'>"._T('contact:votre_email')."</label>
			<input type='text' class='text' name='email_m' id='email_m' value='".$email_m."' size='30' />
			</li>
			<li>
			<label for='sujet_m'>"._T('contact:sujet')."</label>
			<input type='text' class='text' name='sujet_m' id='sujet_m' value='".$sujet_m."' size='30' />
			</li>
			<li>
			<label for='texte_m'>"._T('contact:votre_texte')."</label>
			<textarea name='texte_m' id='texte_m' rows='8' cols='40'>".$texte_m."</textarea>
			</li>
		</ul>
	</fieldset>
	
	<p style='display: none;'>

		<label for='nobot'>"._T('contact:nobot')."</label>
		<input type='text' class='text' name='nobot' id='nobot' value='' size='10' />
	</p>
	<p class='boutons'><input type='submit' name='envoi' value='"._T('contact:envoyer_message')."' /></p>
</form>
";
}

?>

