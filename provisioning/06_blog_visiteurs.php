<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
echo "bloglevel=".get_config('core','bloglevel')." enableblogs=".get_config('core','enableblogs')." autologinguests=".get_config('core','autologinguests')."\n";
set_config('bloglevel', 5);
$guest = $DB->get_record('role', ['shortname'=>'guest']);
assign_capability('moodle/blog:view', CAP_ALLOW, $guest->id, context_system::instance()->id, true);
$authuser = $DB->get_record('role', ['shortname'=>'user']);
assign_capability('moodle/blog:view', CAP_ALLOW, $authuser->id, context_system::instance()->id, true);
purge_all_caches();
echo "ok\n";
