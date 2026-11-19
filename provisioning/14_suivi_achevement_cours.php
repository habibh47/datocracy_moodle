<?php
// Active le suivi d'achèvement du cours id 2.
// Les 6 activités sont des paquets SCORM : une activité est terminée quand le paquet SCORM de la leçon
// signale « terminé » ou « réussi » (la simple consultation d'une page SCORM ne valide pas l'achèvement).
// Le cours est terminé quand toutes les activités le sont.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->dirroot . '/completion/criteria/completion_criteria_activity.php');
require_once($CFG->dirroot . '/completion/completion_aggregation.php');
global $DB;
$courseid = 2;
set_config('enablecompletion', 1);
$DB->set_field('course', 'enablecompletion', 1, ['id' => $courseid]);

$cmids = [];
foreach ($DB->get_records('course_modules', ['course' => $courseid, 'deletioninprogress' => 0]) as $cm) {
    $DB->update_record('course_modules', (object) ['id' => $cm->id, 'completion' => COMPLETION_TRACKING_AUTOMATIC, 'completionview' => 0, 'completionexpected' => 0]);
    // Statut SCORM requis : 1 = réussi, 2 = terminé (les deux acceptés).
    $DB->set_field('scorm', 'completionstatusrequired', 6, ['id' => $cm->instance]);
    $cmids[] = $cm->id;
}
rebuild_course_cache($courseid, true);

$DB->delete_records('course_completion_criteria', ['course' => $courseid, 'criteriatype' => COMPLETION_CRITERIA_TYPE_ACTIVITY]);
$data = new stdClass();
$data->id = $courseid;
$data->criteria_activity = $cmids;
$criterion = new completion_criteria_activity();
$criterion->update_config($data);
foreach ([COMPLETION_CRITERIA_TYPE_ACTIVITY, null] as $type) {
    $agg = new completion_aggregation(['course' => $courseid, 'criteriatype' => $type]);
    $agg->setMethod(COMPLETION_AGGREGATION_ALL);
    $agg->save();
}
// Nettoyage d'un essai : aucun achèvement enregistré pour les comptes de test.
$DB->delete_records_select('course_modules_completion', 'coursemoduleid ' . $DB->get_in_or_equal($cmids)[0], $DB->get_in_or_equal($cmids)[1]);
purge_all_caches();
echo 'activités suivies : ' . count($cmids) . "\n";
