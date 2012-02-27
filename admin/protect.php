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
require_once("function.inc.php");
headers("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<?php echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='$lang' lang='$lang' dir='$ltr'>\n";?>
<head>
	<?php echo "
	<title>"._T('gen_htaccess')." $lire_titre_site </title>
	<meta name='description' content='$lire_meta' />
	$head_commun \n
	<link href='../css_base.php?&lang=$lang&admin' rel='stylesheet' type='text/css' media='projection, screen, tv' />
	";
	?>
</head>
<?php
function gen_htaccess(){
	global $URI;
    $out = "ErrorDocument 401 \"<div style='margin:0 auto; padding:20px; width:550px; background:#ccc; border: 1px solid #000;'><h3>"._T('reconnu_false')."</h3>"._T('recreer_pwd', array('url'=>"http://".s('SERVER_NAME').$URI))."</div> "."\n\n";
    $out .= 'AuthUserFile '.getcwd()."/.htpasswd"."\n";
    $out .= 'AuthName "admin plook"'."\n\n";
    $out .= 'AuthType Basic'."\n";
    $out .= '<limit GET POST>'."\n";
    $out .= 'require valid-user'."\n";
    $out .= '</limit>';
    return $out;
}

function show_file($pass){
    global $_POST;
    $out = '<h4>'._T('cree_false').'</h4>';
    $out .= _T('save_yourself', array('fichier'=>'.htaccess'));
    $out .= '<textarea name="access">'.gen_htaccess().'</textarea>';
    $out .= _T('save_yourself', array('fichier'=>'.htpasswd'));
    $out .= '<textarea name="passwd">'.$_POST['login'].':'.$pass['pwd'].'</textarea>';
    return $out;
}

function save_file($fichier, $contenu){
    $fp = @fopen($fichier, "w+");
    if(!$fp){
        $out = FALSE;
    }else{
        fputs($fp, $contenu);
        fclose($fp);
        $out = _T('cree_true')." $fichier <br />";
    }
    return $out;
}

function set_pass(){
    global $_POST;
    if( ereg('win', strtolower(s('SERVER_SOFTWARE'))) && !ereg('Darwin',s('SERVER_SOFTWARE')) ){ // Windows
        $pass['pwd'] = $_POST['passwd'];
        $pass['msg'] = _T('crypt_false');
    }else{ // DES
        $pass['pwd'] = crypt(trim($_POST['passwd']));
        $pass['msg'] = _T('crypt_true');
    }    
    return $pass;
}

//.htaccess .htpasswd
if(isset($_POST['login'])){
    $pass = set_pass();
    $res_htac = save_file('.htaccess', gen_htaccess());
    $res_htpa = save_file('.htpasswd', $_POST['login'].':'.$pass['pwd']);
    
   if($res_htac == FALSE) 
    $res_htac = show_file($pass);

    $mess.= $res_htac .$res_htpa;
    $mess.= '<em>'.$pass['msg'].'</em>';
}
?>
<body id="adminbody">
<div class="htacc">
<?php
	echo "<h3>"._T('gen_htaccess')."</h3>"._T('noter_pwd').
	"<form action='".$URI."' method='post' name='generateur'><div>
		<p><label>"._T('login_')."</label><input name='login' type='text' ></p>
		<p><label>"._T('mot_de_passe_')."</label><input name='passwd' type='password' ></p>
		
		<div class='bouton'>
		<input  type='reset' value='"._T('annuler_')."'> 
		<input  type='submit' value='"._T('generer_')."'>
		</div>
	</div></form>
	$mess
	<a href='../admin/'>". _T('retour_redac') ."</a>
	";
?>
</div></body>
</html>