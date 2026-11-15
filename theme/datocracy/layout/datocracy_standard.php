<?php
// Mise en page « standard » : coque applicative pour l'espace apprenant connecté, Elan pour tout le reste.
defined('MOODLE_INTERNAL') || die();

global $PAGE;

$apppages = '/^(calendar-|badges-|grade-report-overview|user-preferences|message-)/';
if (isloggedin() && !isguestuser() && preg_match($apppages, $PAGE->pagetype)) {
    require(__DIR__ . '/datocracy_app.php');
} else {
    require($CFG->dirroot . '/theme/elan/layout/drawers.php');
}
