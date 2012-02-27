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


//merci à SPIP

//secu
if (!defined("_DIR_TXT")) return;

$langfile="lang.txt"; //son titre definit la langue du site

//global
$langues = array (
		'ar' => '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;',
		'br' => 'brezhoneg',
		'ca' => 'catal&agrave;',
		'cs' => '&#269;e&#353;tina',
		'de' => 'Deutsch',
		'en' => 'English',
		'eo' => 'Esperanto',
		'es' => 'español',
		'fr' => 'français'
);

function surcharger_langue($module,$lang,$fichier=false) {
	if (!isset($GLOBALS['idx_lang'])) return;
	if (is_file($fichier)){
	$idx_lang_normal = $GLOBALS['idx_lang'];
	$idx_lang_surcharge = $GLOBALS['idx_lang'] = 'i18n_'.$module.'_'.$lang;
	include($fichier);

	if (is_array($GLOBALS[$idx_lang_surcharge])) {
		$GLOBALS[$idx_lang_normal] = array_merge(
			$GLOBALS[$idx_lang_normal],
			$GLOBALS[$idx_lang_surcharge]
		);
	}
	unset ($GLOBALS[$idx_lang_surcharge]);
	$GLOBALS['idx_lang'] = $idx_lang_normal;
	} else {
	//module non traduit > francais
	$fichier = _DIR_MODULES."$module/lang/$module"."_fr".'.php';
	surcharger_langue($module,$lang,$fichier);
	}
}

	
function language($lang,$module=false){
	
	$filang=_DIR_MODULES.'lang/'."lang_$lang.php";

	if (is_file($filang)) {
	$GLOBALS['idx_lang']="i18n_lang_".$lang;
	include($filang);
	}

	if($module){
	$fichier = _DIR_MODULES."$module/lang/$module"."_".$lang.'.php';
	surcharger_langue($module,$lang,$fichier);
	}	
	
	else return '';
}

// merci SPIP
function traduire($ori, $lang, $askfor='') {
	static $deja_vu = array();
	$modules=null;
	
	if (isset($deja_vu[$lang][$ori])) return $deja_vu[$lang][$ori];
	//module:chaine 
	if (strpos($ori,':')) {
		list($modules,$code) = explode(':',$ori,2);
		$modules = explode('/', $modules);
		$ori=$code;
	}
	
	$ask=language($lang,$modules[0]);
	
	$var = "i18n_lang_".$lang;

	//no lang && no fr
	$trad = isset($GLOBALS[$var][$ori]) ? $GLOBALS[$var][$ori] :$trad = (($askfor!='fr')&&(!$ask))?traduire($ori, "fr", $lang):'';
	$deja_vu[$lang][$ori] = $trad;
	return $trad;
}

function menu_languesT($lang){
	global $STRING, $isadmin, $thispage;
	$tlang=($isadmin)?_T('menu_lang'):_T('choix_lang');
	$page=($isadmin)?'f=lang&amp;module=view':$STRING;
		$r = "<form action='?$page' method='post' id='menulang'>
		<p><label> $tlang </label>";
	
		$r .= "<select name='lang' onchange=\"window.location='?$page"."&amp;lang='+this.value; \">";
		foreach ($GLOBALS['langues'] as $l => $nom){
		$flg = _DIR_MODULES."lang/lang_$l.php";
		$rlg= '<option value="'.$l.'"'.($l == $lang ? ' selected="selected"' : ''). '>'.$nom."</option>\n";
		if ((!$isadmin)&&(is_file($flg))) $r.=$rlg;
		elseif($isadmin=="oui") $r.=$rlg;
		}
		$r .= '</select>';
	
		$r .= '<input type="submit" name="choixlang" value="'._T('ok_').'" />
		</p></form>';
		return $r;
}

//lang
if ($choixlang = _rq('choixlang','secu')) {
	$lang=_rq('lang');
	$flg=_DIR_MODULES."lang/lang_$lang.php";
	$tlg=$GLOBALS['langues'][$lang];
	//$charger="<br /><a href='?$STRING&amp;module=lang_$lang.php.zip'>"._T('download_module')."</a>";
	$envoi=is_file($flg)?$flg:""._T('charge_lang', array('tlg'=>$tlg, 'filang'=>$flg));
	if ($isadmin){
	$mess=$envoi;
	}
}

$lang=quel(_DIR_CONF,$langfile,'lang');
$ltr=quel(_DIR_CONF,$langfile,'sens');


if($thispage=="lang"){
$tlg=$GLOBALS['langues'][$lang];
//on met un message dans admin
$admin_message.="<h4 class='clear'>module_multilang ($thispage)</h4>
<span>"._T('modif_lang', array('lg'=>$lang, 'tlg'=>$tlg))."</span>";
}

//reecrire la langue
if(is_dir(_DIR_CONF) && !is_file(_DIR_CONF.$langfile)) 
faire('ecrire',_DIR_CONF,'titre_page',$lang,$langfile);
if (_rq('choixlang','secu') && _rq('lang'))
$mess.=faire('ecrire',_DIR_CONF,'titre_page',_rq('lang')."\n"."# "._T('titre_')._T('site_lang'),$langfile);


//On ajoute la liste des langues dans la page des modules
$lien_modules.=is_dir(_DIR_MODULES."lang/")?menu_languesT($lang):'';

?>

