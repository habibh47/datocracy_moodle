<?php
// Correctif : « completionstatusrequired » vaut 6 (réussi ou terminé) ; la valeur 3 posée auparavant ne correspondait à aucun statut,
// donc aucune leçon ne s'achevait. On recalcule ensuite l'achèvement de tous les apprenants inscrits.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->libdir . '/completionlib.php');
global $DB;
$course = get_course(2);
$DB->set_field('scorm', 'completionstatusrequired', 6, ['course' => 2]);
rebuild_course_cache(2, true);
$info = get_fast_modinfo($course);
$ci = new completion_info($course);
foreach ($info->get_cms() as $cm) {
    if ($cm->modname !== 'scorm') { continue; }
    foreach ($DB->get_records('scorm_attempt', ['scormid' => $cm->instance], '', 'DISTINCT userid') as $a) {
        $ci->update_state($cm, COMPLETION_UNKNOWN, $a->userid);
        echo "recalcul cm {$cm->id} user {$a->userid}\n";
    }
}
purge_all_caches();
echo "ok\n";