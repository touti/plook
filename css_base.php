<?phprequire_once("admin/function.inc.php");headers("Content-type:text/css; charset=UTF-8;");if($ltr=='ltr') {$left='left'; $right='right';} else {$left='right'; $right='left';} ?>/************************************************\ *  PLOOK					* *                                              * *  Copyright 2004-2010     			* *  Anne-lise Martenot, http//plook.fr		* *                                            	* *  Licence GNU/GPL.     			* *   						*\************************************************//*		PLOOK 1.3 - janv 2010 		*//*  http://blog.html.it/layoutgala/ */html{font-size: 100%}html,body{margin:0; padding:0}body {font: 0.8125em verdana, arial, sans-serif; background:#FFFFF8; text-align:center}a, img {text-decoration:none; color:#555; border:none}/*Link - Visited - Hover - Active*/#content a:link{color:#00384D}#content a:visited{color:#4E7474}a:hover{text-decoration:underline}a[href^="mailto:"]:before {content:"✉ "}#content a[href^="http://"]:before {content:"→ "}#content a.nofleche:before {content:" "}h1,h2,h3,h4,h5{margin:.5em 0; padding:0}h1{font-size:1.8em}h2{font-size:1.6em}h3{font-size:1.4em}h4{font-size:1.2em}h5{font-size:1em}#entete h1{margin:0; font-weight:normal; font-size:100%; height:61px}#entete{height:110px; text-align:<?php echo $left ?>; background:#B5C9CF <?php echo $tetimage ?>no-repeat right top}#entete h1 a, #adminbody input.bigtitre{display:block; font-size:3em; color:#FFF; text-decoration:none; padding:0.3em}#extra {float:right; padding:0.5em; width:200px; margin-left:-100%; color:#999; font:italic small-caps 300 1em "Lucida Grande",sans-serif}#footer {clear:both; height:60px; text-align:center; margin:0 auto; padding:0; background:#B5C9CF <?php echo $piedimage ?>no-repeat left top}#page {clear:both; background:#D1DFDF; position:relative; width:800px; border:1px solid #77B3BF; margin:2em auto; text-align:center}#conteneur{margin:0 auto; position:relative; padding:20px; float:left; width:760px}#content{text-align:<?php echo $left ?>; width:756px}/*menu*/#nav {font-size:0.9em; clear:both}#nav li, #nav ul{list-style:none; margin:0 auto; padding:0; border:none}#nav ul {position:relative; margin:0 10px 24px; height:auto; white-space:nowrap}#nav li {position:relative; border:1px #9BB7BF solid; border-bottom:0; display:block; float:left; margin-right:3px; background:#F4F4F4}#nav a {color:#999; display:block; width:auto; padding:4px; height:1.4em; white-space:normal}#nav a:hover, #nav li:hover , #nav a.rep:hover{background-color:#8FB5C1; color:#F8F8F8; text-decoration:none}#nav .nonpublic{background:#DDD}#nav .active, #nav .actif{color:#222; background:#D1DFDF; border:1px solid #80AAB7; border-bottom:0}/*niv 1*/#nav .nonpublic a.actif ,#nav .nonpublic a.active{color:#5A90A0; background:#DDD; border:1px solid #80AAB7; border-width:1px 0}/*sous menu*/.not_racine .nonpublic a:before{content:"≠ " !important}.not_racine{text-align:left; width:160px; padding:0 0 0.5em; float:left; list-style:none; margin:0}.not_racine li{margin:0; padding:0}.not_racine ul ul a{color:#fff}.not_racine ul ul ul li a{margin-left:1em; color:#555}.not_racine .actif, #content a.actif{text-decoration:underline}/*+IE*/.rep:before{content:"≡ " !important}.rep, #nav .nonpublic a.rep, #nav .actif{border:none !important; border-left:4px solid #689CAC}#ariane{position:relative; top:-1em;  font-size:90%}#content #ariane a {color:#333}.hide{display:none}/*IMAGES*/.docs{float:right; width:320px; margin-left:2em; font-size:0.8em}.docs div{text-align:center; margin-right:4px; margin-bottom:1em}#content #images_page div{margin-bottom:3em}.docs div a{text-align:center; display:block; margin:0 auto}.im img{margin:0 auto}.im span{display:block; font-style:italic}img.pourcent{width:98%}/*SOUS FOOTER*/#toadmin,#credit {position:relative; top:62px; width:180px; padding:0; margin:0 auto; font-size:0.8em}#toadmin{float:left; text-align:left}#credit{float:right; text-align:right}#toadmin a, #credit a {color:#CCC; padding:4px 4px 4px 0; display:block}#credit a:hover span {display:none}#credit a:hover, #deplier3 a{background: <?php echo "url('".$plook."')" ?> no-repeat right top;  padding-right:24px; display:block; height:24px}#toadmin a:hover, #credit a:hover{color:#333; text-decoration:none}	.minheight{height:280px; float:left; width:0px; margin:0; padding:0; overflow:hidden}.overflow{overflow:auto; height:300px}.clear {display:block; clear:both; margin:0; padding:0}/*right to left languages to use in textarea*/.rtl{text-align:right; direction: rtl; font:125% Tahoma}.red{color:#F5003D}/*pophoto*/#photo, #photo dl{z-index:99; display:block; position: fixed; top: 0; left: 0;  width: 100%; height: 100%; text-align:center; color:#FFF}#photo img{background:#fff; border: 4px solid #777}#photo div{ overflow: hidden; z-index: 1; background-color: rgb(0, 0, 0); position: absolute; top: 0; left: 0; height: 100%; width: 100%; 	filter: alpha(opacity=75); opacity : 0.8 }#photo dd,#photo dt{z-index: 100;text-align:center; padding:0; margin:0; position:relative}#photo dl{z-index: 98; margin:0 auto; margin-top:1%}<?php/* POUR ADMIN */if($foradmin=='oui') echo "#adminbody {background:#868F8D}#adminbody #page {margin:0 auto}#page .contentprive {margin-top:1em; float:left; width:790px; text-align:center}#navact {font-size:0.8em; width:802px; list-style:none; margin:0 auto; padding:0}#navact li {float:left; white-space:nowrap; margin:0 2px 0 0}#navact a, .gestionimg{display:block; height:1.4em; color:#666; padding:4px}#navact a{float:left;  border-bottom:1px solid #78A8AF}#navact a:hover,#navact a:focus,.gestionimg:hover{background:#B2E9F2; color:#000; text-decoration:none}#navact li, #documents {background:#D7F7FD}form p{margin:0; padding:0}.renomme,.valider,.supprime,.cree{color:#000; border:1px outset #999;background:#FFC0CB}.supprime{background:#FF5559}#adminbody input, #adminbody select{width:auto; font: 90% verdana, sans-serif}#adminbody .docsprive input, #adminbody .blocinfo input, #gerimgs input, #adminbody select{font-size:100%}#adminbody input.bigtitre{ float:left; background:transparent; color:#FFF; padding:0; margin:0.2em 0.2em 0.65em 0.2em; width:600px}#vtit{ margin:0; margin-top:2em; float:left; width:18%}.depli div{width:802px; display:none; clear:both; text-align:center; margin:0 auto; background:#F4F4F4}.depli p, .enligne, .enligne p{list-style:none; display:inline; margin:0}.depli form{padding:4px 0; display:block; font-size:0.85em; margin:0}/*modules*/#deplier3 a{background-position:-34px 0; padding:2px 2px 2px 25px; line-height:1.5em; display:block; float:left; font-size:0.8em}#deplier3 .actif a{color:#FF5A00}#deplier3 p{margin-top:0.5em; display:block; float:left; margin-left:1em; line-height:2.2em}#deplier3 form{display:inline}#menulang{ margin:0 auto}#editpage, #voirpage{float:left; width:60%; margin-bottom:1em}.voir {border:2px solid #ccc; margin:0 1em; text-align:$left; height:300px; overflow:auto}.toolbar{margin:0; padding:0; height:auto; margin-left:10px; text-align:left}.toolbar a{background:#999; float:left; font-size:12px; padding:4px; color:#000; border:1px outset #999}.toolbar a:hover{background:#ccc; text-decoration:none}.toolbar a.toolpublic{background:#ccc}.fondtextarea {background:#EFEFEF; font-size:14px; border:solid #666 2px; width:95%; margin:0; padding:0}form:hover input.valider{background:#B8FF9F}.depli .open, .css #deplier2, .css #deplier3, .lang #deplier2{display:block}.css #deplier2 input.yel, .lang #deplier2 input.yel, .css #text_area, .site #text_area, .contact_dest #text_area{background:#F2FFCC; border: 2px solid #FF9900}.depli .open1, .depli .open2, .depli .open3{display:block}#adm{width:40%; float:right; text-align:left}#adm .not_racine{margin-left:1em}#adm .active{text-decoration:underline}.blocinfo{font-size:0.8em; margin:0 auto; padding:5px; background:#FFEE9F; margin-bottom:0.5em}.info{color:#FF7200; font-size:4em; float:left; margin: -0.2em 0}.infoimg{clear:right; float:right; width:285px; margin-right:1em}.infopage, .infodos{clear:left; float:left; width:300px; position:relative; left:80px}.infodos{margin-top:2em}#documents .blocinfo{clear:both; width:600px}.upload, .upload select{margin:0.5em 0}.upload label{ margin:1em 0; white-space:nowrap}.docsprive{float:right; width:300px; height:265px; overflow:auto; margin:0 0.5em 1em 0}.docsprive div {padding:0.4em 0}.noimage {height:100px}.docsprive .im{clear:both; border:2px solid #666}.bloc{display:block; margin:0 auto; padding:0; text-align:left}#alert{z-index:100; background:#FF5559;  width:450px; color:#FFF; text-align:center}.alert {position:absolute; top:200px; left:10px; border:2px solid #FF0004}.alertimg {top:650px; left:100px}#alert span {display:block; padding:20px}#alert a {color:#FFF; text-decoration:underline}#alert a:hover {color:#FFFF00}#alert strong {color:#000; white-space:nowrap}#alert .close{display:block; float:right; height:12px; width:12px; background:#FFF; color:#666; text-decoration:none; padding:0 2px 6px 4px}.gestionimg {font-size:0.9em}.geredossierimage{margin:0 auto; position:relative; height:auto; padding:0; margin-left:6px}.txtleft{text-align:left}#documents {border-top:1px dotted #333; text-align:center; margin:0 auto}#gerimgs {padding-bottom:0.5em}#gerimgs #images_page div, #gerimgs .docs_page div{float:left; border:1px solid #000; width:185px; padding:5px; height:180px; font-size:0.8em; text-align:center}#gerimgs span {clear:both; display:block}#adminbody h4{margin:0; padding:0}#gerimgs .im{ background:#BBB}#adminbody .im h4{font-size:1em}#gerimgs img {background:#CCC}#gerimgs img.pourcent{width:60%}#gerimgs .actifimg{background:#F8F8F8}#gerimgs .deadimg{background:#777}#gerimgs .pageou{font-weight:700; margin-top:0.5em}#gerimgs .im a{display:block; max-height:90px; overflow:hidden}/* htaccess */.htacc {width:360px; margin:6em auto}.htacc a, .htacc h3 {color:#FFF; padding:5px; display:block}.htacc h3 {background: #333}.htacc form {padding:5px 10%}.htacc form p, .htacc .msg {background: #eee; border:2px solid #ccc; padding:5px}.htacc label, .htacc .bouton {margin:5px}.htacc textarea {width:360px; height:250px; border:1px solid #999}";/* + 3 modes css, to modify in plook via css module*//* +modules/css/title.php ssi== title of css.txt*//* liste agregate for modules *//* +content of textes/css.txt*/if (isset($cssplus)) include(_DIR_MODULES."css/$cssplus"); if (isset($cssmodules)) echo $cssmodules;if (isset($lirecss)) echo $lirecss;?>