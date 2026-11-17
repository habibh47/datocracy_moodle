<?php
// Menu utilisateur : identifiants de chaînes Moodle (traduits automatiquement) au lieu de libellés français écrits en dur.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
set_config('customusermenuitems', "myhome,moodle|/my/\nmycourses,moodle|/my/courses.php\ngrades,grades|/grade/report/overview/index.php\ncalendar,calendar|/calendar/view.php?view=month\nprofile,moodle|/user/profile.php");
purge_all_caches();
echo "ok\n";