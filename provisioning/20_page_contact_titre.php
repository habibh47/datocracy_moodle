<?php
// Page Contact : le titre du bloc double le titre de la page (bandeau) ; on le retire.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$cm = $DB->get_field('course_modules', 'id', ['idnumber' => 'page-contact', 'course' => 1]);
$ctx = context_module::instance($cm);
foreach ($DB->get_records('block_instances', ['parentcontextid' => $ctx->id, 'blockname' => 'elan_contact_area']) as $bi) {
    $c = unserialize(base64_decode($bi->configdata));
    $c->title = '';
    $DB->set_field('block_instances', 'configdata', base64_encode(serialize($c)), ['id' => $bi->id]);
    echo "titre du bloc contact retiré\n";
}
purge_all_caches();