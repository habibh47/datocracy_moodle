<?php
// Coque applicative datocracy (menu marine) pour les pages de l'espace apprenant :
// Mes cours, profil, et (via datocracy_standard.php) calendrier, badges, notes, préférences.
defined('MOODLE_INTERNAL') || die();

global $DB, $USER, $PAGE, $CFG, $OUTPUT;

include($CFG->dirroot . '/theme/elan/inc/elan_themehandler.php');
require_once($CFG->libdir . '/behat/lib.php');

array_push($extraclasses, 'elan_context_frontend', 'dato-dashboard', 'dato-app');
$bodyclasses = implode(' ', $extraclasses);
$bodyattributes = $OUTPUT->body_attributes($bodyclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

include($CFG->dirroot . '/theme/elan/inc/elan_themehandler_context.php');

$path = $PAGE->has_set_url() ? $PAGE->url->get_path() : '';
$on = [
    'nav_on_dashboard' => $path === '/my/' || $path === '/my/index.php',
    'nav_on_courses' => strpos($path, '/my/courses.php') === 0,
    'nav_on_assignments' => strpos($path, '/calendar/') === 0,
    'nav_on_certificates' => strpos($path, '/badges/') === 0,
    'nav_on_profile' => strpos($path, '/user/profile.php') === 0 || strpos($path, '/user/view.php') === 0,
];

$templatecontext += $on + [
    'pagetitle' => $PAGE->heading ?: format_string($PAGE->title),
    'fullname' => fullname($USER),
    'userpicture' => $OUTPUT->user_picture($USER, ['size' => 44, 'link' => false, 'class' => 'dato-avatar']),
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

echo $OUTPUT->render_from_template('theme_datocracy/dato_app', $templatecontext);
