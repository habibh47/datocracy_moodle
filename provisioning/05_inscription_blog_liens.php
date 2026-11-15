<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->dirroot.'/blog/lib.php');
require_once($CFG->dirroot.'/blog/locallib.php');
global $DB, $USER;
$w = 'https://staging.datocracy.ai';
// 1. liens du pied de page (colonne 2) vers le domaine
$b = get_config('theme_elan','footer_col_2_body');
set_config('footer_col_2_body', str_replace('http://<ancienne-adresse-ip>:8085', $w, $b), 'theme_elan');
// 2. auto-inscription par e-mail (page d'inscription du Figma)
set_config('registerauth', 'email');
// 3. icones sociales : garder LinkedIn et X
foreach (['facebook','instagram','pinterest','dribbble','google'] as $n) set_config("elan_{$n}_url", '', 'theme_elan');
set_config('elan_linkedin_url', 'https://www.linkedin.com/company/datocracy', 'theme_elan');
set_config('elan_twitter_url', 'https://x.com/', 'theme_elan');
// 4. categorie de cours
$DB->set_field('course_categories', 'name', 'Cours en ligne', ['id'=>1]);
// 5. formulaire de contact du site (page native Moodle)
set_config('supportavailability', 2);
// 6. articles de blog
set_config('enableblogs', 1);
$USER = get_admin();
\core\session\manager::set_user($USER);
$posts = [
 ['The AI-Powered Minority Report: Truth IS Stranger than Fiction', 'photo-code.png'],
 ['Access Without Power: What the UNESCO South Asia AI and Gender Report Actually Found', 'photo-woman.png'],
 ['Consultation at The End is not Participation, It Is Notification', 'photo-meeting.png'],
];
$DB->delete_records('post', ['module'=>'blog']);
$ids = [];
$fs = get_file_storage(); $uctx = context_user::instance($USER->id);
foreach ($posts as $p) {
  $e = new stdClass();
  $e->module = 'blog'; $e->userid = $USER->id; $e->courseid = 0; $e->groupid = 0; $e->moduleid = 0;
  $e->subject = $p[0]; $e->summary = '<p>Ronda Železný-Green, PhD</p>'; $e->summaryformat = FORMAT_HTML;
  $e->content = ''; $e->uniquehash = ''; $e->publishstate = 'site';
  $e->created = time(); $e->lastmodified = time();
  $e->id = $DB->insert_record('post', $e);
  $fs->create_file_from_pathname(['contextid'=>$uctx->id,'component'=>'blog','filearea'=>'attachment','itemid'=>$e->id,'filepath'=>'/','filename'=>$p[1]], '/tmp/'.$p[1]);
  $ids[] = $e->id; echo "post {$e->id}\n";
}
$bi = $DB->get_record('block_instances', ['blockname'=>'elan_blog_area']);
$cfg = unserialize(base64_decode($bi->configdata)); $cfg->posts = $ids;
$DB->set_field('block_instances', 'configdata', base64_encode(serialize($cfg)), ['id'=>$bi->id]);
purge_all_caches();
echo "ok\n";
