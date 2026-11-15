<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
$fs = get_file_storage();
$ctx = context_system::instance();
function setfile($name, $src, $fname) {
  global $fs, $ctx;
  $fs->delete_area_files($ctx->id, 'theme_elan', $name);
  $fs->create_file_from_pathname(['contextid'=>$ctx->id,'component'=>'theme_elan','filearea'=>$name,'itemid'=>0,'filepath'=>'/','filename'=>$fname], $src);
  set_config($name, '/'.$fname, 'theme_elan');
  echo "$name ok\n";
}
setfile('main_logo', '/tmp/Logo_no-tagline.png', 'Logo_no-tagline.png');
setfile('mobile_logo', '/tmp/Icon.png', 'Icon.png');
setfile('main_footer_logo', '/tmp/Logo_no-tagline_White.png', 'Logo_no-tagline_White.png');
setfile('favicon', '/tmp/Icon.png', 'Icon.png');
foreach (['logo_visibility','footer_logo_visibility','header_logos','footer_logo_sec'] as $k) echo $k.'='.var_export(get_config('theme_elan',$k),true)."\n";
purge_all_caches();
