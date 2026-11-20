<?php
// Lecteur de cours datocracy : barre marine (titre, progression, Quitter), contenu de l'activité, sommaire du cours à droite.
// S'applique aux pages d'activité d'un cours pour un apprenant connecté hors mode édition ; sinon, mise en page d'Elan.
defined('MOODLE_INTERNAL') || die();

global $DB, $USER, $PAGE, $CFG, $OUTPUT;

$course = $PAGE->course;
if (!isloggedin() || isguestuser() || $PAGE->user_is_editing() || empty($course) || $course->id == SITEID) {
    require($CFG->dirroot . '/theme/elan/layout/drawers.php');
    return;
}

include($CFG->dirroot . '/theme/elan/inc/elan_themehandler.php');
require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->libdir . '/completionlib.php');

array_push($extraclasses, 'elan_context_frontend', 'dato-player-page');
$bodyclasses = implode(' ', $extraclasses);
$bodyattributes = $OUTPUT->body_attributes($bodyclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

include($CFG->dirroot . '/theme/elan/inc/elan_themehandler_context.php');

$modinfo = get_fast_modinfo($course);
$completion = new completion_info($course);
$format = course_get_format($course);
$currentcmid = $PAGE->cm ? $PAGE->cm->id : 0;

// Utilisateur connecté mais pas inscrit au cours : les leçons ne lui sont pas accessibles (leurs liens ramèneraient à la page d'inscription).
$coursectx = context_course::instance($course->id);
$enrolled = is_enrolled($coursectx, $USER, '', true) || has_capability('moodle/course:view', $coursectx);

$chapters = [];
$n = 0;
foreach ($modinfo->get_section_info_all() as $section) {
    if (!$section->uservisible) {
        continue;
    }
    $lessons = [];
    foreach (($modinfo->sections[$section->section] ?? []) as $cmid) {
        $cm = $modinfo->cms[$cmid];
        if (!$cm->is_visible_on_course_page() || $cm->modname === 'label') {
            continue;
        }
        $done = false;
        if ($completion->is_enabled($cm) != COMPLETION_TRACKING_NONE) {
            $d = $completion->get_data($cm, false, $USER->id);
            $done = in_array($d->completionstate, [COMPLETION_COMPLETE, COMPLETION_COMPLETE_PASS]);
        }
        $lessons[] = [
            'name' => format_string($cm->name),
            'url' => $cm->url ? $cm->url->out(false) : '',
            'done' => $done,
            'current' => $cm->id == $currentcmid,
            'locked' => !$cm->uservisible || !$enrolled,
        ];
    }
    if (!$lessons) {
        continue;
    }
    $n++;
    $title = trim($format->get_section_name($section));
    $chapters[] = ['title' => ($title !== '' && $section->section > 0) ? $title : ((current_language() === 'en' ? 'Chapter ' : 'Chapitre ') . $n), 'lessons' => $lessons];
}

$pct = 0;
if ($completion->is_enabled()) {
    $p = \core_completion\progress::get_course_progress_percentage($course, $USER->id);
    $pct = $p === null ? 0 : (int) round($p);
}

$templatecontext += [
    'course_title' => format_string($course->fullname),
    'progress' => $pct,
    'chapters' => $chapters,
    'not_enrolled' => !$enrolled,
    'url_exit' => (new moodle_url('/my/'))->out(false),
    'logo_url' => (new moodle_url('/theme/elan/pix/brand/Logo_Primary_White.png'))->out(false),
    'activity_title' => $PAGE->cm ? format_string($PAGE->cm->name) : '',
];

echo $OUTPUT->render_from_template('theme_datocracy/dato_player', $templatecontext);
