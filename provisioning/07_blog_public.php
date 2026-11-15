<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$DB->set_field('post', 'publishstate', 'public', ['module'=>'blog']);
foreach ($DB->get_records('post', ['module'=>'blog']) as $p) echo $p->id.' '.$p->publishstate."\n";
purge_all_caches();
