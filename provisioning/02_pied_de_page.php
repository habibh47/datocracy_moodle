<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
$fs = get_file_storage(); $ctx = context_system::instance();
$fs->delete_area_files($ctx->id, 'theme_elan', 'main_footer_logo');
$fs->create_file_from_pathname(['contextid'=>$ctx->id,'component'=>'theme_elan','filearea'=>'main_footer_logo','itemid'=>0,'filepath'=>'/','filename'=>'Logo_Primary_White.png'], '/tmp/Logo_Primary_White.png');
set_config('main_footer_logo', '/Logo_Primary_White.png', 'theme_elan');
function ul($items){ $h='<ul class="dato-footer-list">'; foreach($items as $t=>$u){ $h.='<li><a href="'.$u.'">'.$t.'</a></li>'; } return $h.'</ul>'; }
$w = $CFG->wwwroot;
$cols = [
 1 => ['', "Là où l'upskilling data et la démocratie se rencontrent."],
 2 => ['Explorer', ul(['Accueil'=>$w.'/','À propos'=>'#','Cours'=>$w.'/course/index.php','Blog'=>'#'])],
 3 => ['Ressources', ul(['FAQ'=>'#','Formateurs'=>'#','Histoires de réussite'=>'#','Témoignages'=>'#','Galerie'=>'#'])],
 4 => ['Légal', ul(['Conditions générales'=>'#','Politique de confidentialité'=>'#','Politique de cookies'=>'#'])],
 5 => ['Suivez-nous', ul(['LinkedIn'=>'https://www.linkedin.com/company/datocracy','X'=>'https://x.com/'])],
];
foreach ($cols as $i=>$c) { set_config("footer_col_{$i}_title", $c[0], 'theme_elan'); set_config("footer_col_{$i}_body", $c[1], 'theme_elan'); }
set_config('footer_copyright', '© 2026 Datocracy. Tous droits réservés.', 'theme_elan');
set_config('footer_menu', '', 'theme_elan');
set_config('scss', 'body,p,li,input,select,textarea{font-family:"Open Sans",sans-serif;} h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6,.btn,.nav-link,.navbar a{font-family:"Montserrat",sans-serif !important;} .footer-area{background:#192e4e !important;} .footer-area h1,.footer-area h2,.footer-area h3,.footer-area h4,.footer-area h5,.footer-area h6{color:#f18619 !important;} .dato-footer-list{list-style:none;padding:0;margin:0;} .dato-footer-list li{margin-bottom:14px;} .footer-area a,.footer-area p{color:#e8ecf2;}', 'theme_elan');
purge_all_caches(); echo "done\n";
