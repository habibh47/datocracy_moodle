<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$DB->set_field('course','fullname','Datocracy',['id'=>1]);
$DB->set_field('course','shortname','Datocracy',['id'=>1]);
set_config('custommenuitems', "Accueil|/\nÀ propos|/\nCours|/course/index.php\nContact|/\nBlog|/", null);
foreach (['frontpage','frontpageloggedin'] as $k) echo $k.'='.get_config('core',$k)."\n";
echo 'lang='.get_config('core','lang')."\n";
echo 'langs='.implode(',',array_keys(get_string_manager()->get_list_of_translations()))."\n";
foreach (['header_btn_text','header_btn_url','header_search','search_placeholder','hide_banner','banner_bg'] as $k) echo $k.'='.var_export(get_config('theme_elan',$k),true)."\n";
$bi=$DB->get_records('block_instances',null,'id','id,blockname,pagetypepattern,defaultregion,defaultweight');
foreach($bi as $b) echo "blk {$b->id} {$b->blockname} {$b->pagetypepattern} {$b->defaultregion}\n";
purge_all_caches();
