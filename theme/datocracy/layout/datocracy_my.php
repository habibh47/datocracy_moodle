<?php
// Tableau de bord datocracy : coque applicative (menu marine, salutation, statistiques, cours en cours, à venir).
defined('MOODLE_INTERNAL') || die();

global $DB, $USER, $PAGE, $CFG, $OUTPUT;

include($CFG->dirroot . '/theme/elan/inc/elan_themehandler.php');

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->libdir . '/completionlib.php');

array_push($extraclasses, 'elan_context_frontend', 'dato-dashboard');
$bodyclasses = implode(' ', $extraclasses);
$bodyattributes = $OUTPUT->body_attributes($bodyclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

include($CFG->dirroot . '/theme/elan/inc/elan_themehandler_context.php');

// ---- Données du tableau de bord ------------------------------------------------------------
$courses = enrol_get_my_courses(['summary', 'summaryformat'], 'fullname ASC');
$cards = [];
$inprogress = 0;
$completed = 0;
foreach ($courses as $c) {
    $completion = new completion_info($c);
    $pct = 0;
    $iscomplete = false;
    if ($completion->is_enabled()) {
        $p = \core_completion\progress::get_course_progress_percentage($c, $USER->id);
        $pct = $p === null ? 0 : (int) round($p);
        $iscomplete = $completion->is_course_complete($USER->id);
    }
    if ($iscomplete) {
        $completed++;
    } else {
        $inprogress++;
    }
    $img = \core_course\external\course_summary_exporter::get_course_image($c);
    $cards[] = [
        'name' => format_string($c->fullname),
        'url' => (new moodle_url('/course/view.php', ['id' => $c->id]))->out(false),
        'img' => $img ?: '',
        'progress' => $pct,
        'complete' => $iscomplete,
    ];
}
$activitiesdone = $DB->count_records_select('course_modules_completion', 'userid = ? AND completionstate IN (1,2)', [$USER->id]);
$badges = $DB->count_records('badge_issued', ['userid' => $USER->id]);
$first = $cards[0] ?? null;

// Prochaines échéances (30 jours) : événements de l'utilisateur et de ses cours.
$upcoming = [];
$params = [time(), time() + 30 * DAYSECS, $USER->id];
$sql = 'timestart BETWEEN ? AND ? AND (userid = ?';
if ($courses) {
    [$insql, $inparams] = $DB->get_in_or_equal(array_keys($courses));
    $sql .= ' OR courseid ' . $insql;
    $params = array_merge($params, $inparams);
}
$sql .= ')';
foreach ($DB->get_records_select('event', $sql, $params, 'timestart ASC', 'id,name,timestart', 0, 3) as $e) {
    $upcoming[] = ['name' => format_string($e->name), 'date' => userdate($e->timestart, '%d %b')];
}

$templatecontext += [
    'firstname' => $USER->firstname,
    'fullname' => fullname($USER),
    'userpicture' => $OUTPUT->user_picture($USER, ['size' => 44, 'link' => false, 'class' => 'dato-avatar']),
    'hascourse' => !empty($cards),
    'firstcourse' => $first,
    'cards' => array_slice($cards, 0, 3),
    'stat_inprogress' => $inprogress,
    'stat_completed' => $completed,
    'stat_activities' => $activitiesdone,
    'stat_badges' => $badges,
    'progress_pct' => $first ? $first['progress'] : 0,
    'hasupcoming' => !empty($upcoming),
    'upcoming' => $upcoming,
    'url_dashboard' => (new moodle_url('/my/'))->out(false),
    'url_courses' => (new moodle_url('/my/courses.php'))->out(false),
    'url_assignments' => (new moodle_url('/calendar/view.php', ['view' => 'upcoming']))->out(false),
    'url_certificates' => (new moodle_url('/badges/mybadges.php'))->out(false),
    'url_profile' => (new moodle_url('/user/profile.php'))->out(false),
    'url_logout' => (new moodle_url('/login/logout.php', ['sesskey' => sesskey()]))->out(false),
    'url_lang_fr' => (new moodle_url($PAGE->url, ['lang' => 'fr']))->out(false),
    'url_lang_en' => (new moodle_url($PAGE->url, ['lang' => 'en']))->out(false),
    'lang_is_fr' => current_language() === 'fr',
    'lang_is_en' => current_language() === 'en',
    'logo_url' => (new moodle_url('/theme/elan/pix/brand/Logo_Primary_White.png'))->out(false),
];

echo $OUTPUT->render_from_template('theme_datocracy/dato_my', $templatecontext);
