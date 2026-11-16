<?php
// (1) Second cours « Devenir une MPME pilotée par les données » (bientôt) dans la catégorie « MPME », comme dans le Figma ;
// (2) biographies des formateurs ajoutées sous leur rôle.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->dirroot . '/course/lib.php');
global $DB;
$fs = get_file_storage();

// (1) Catégorie et cours
$cat = $DB->get_record('course_categories', ['name' => 'MPME']);
if (!$cat) {
    $cat = core_course_category::create(['name' => 'MPME']);
    $catid = $cat->id;
} else {
    $catid = $cat->id;
}
$course = $DB->get_record('course', ['shortname' => 'mpme']);
if (!$course) {
    $data = (object) ['fullname' => 'Devenir une MPME pilotée par les données', 'shortname' => 'mpme', 'category' => $catid, 'visible' => 1,
        'summary' => '<p>Outils et données mobiles</p>', 'summaryformat' => FORMAT_HTML, 'format' => 'topics'];
    $course = create_course($data);
    echo "cours créé : {$course->id}\n";
}
$cctx = context_course::instance($course->id);
$fs->delete_area_files($cctx->id, 'course', 'overviewfiles');
$fs->create_file_from_pathname(['contextid' => $cctx->id, 'component' => 'course', 'filearea' => 'overviewfiles', 'itemid' => 0,
    'filepath' => '/', 'filename' => 'cover.png'], '/tmp/photo-code.png');
// Les deux cours dans le bloc « Nos cours »
foreach ($DB->get_records('block_instances', ['blockname' => 'elan_course_filter']) as $bi) {
    $c = unserialize(base64_decode($bi->configdata));
    $c->courses = [2, (int) $course->id];
    $DB->set_field('block_instances', 'configdata', base64_encode(serialize($c)), ['id' => $bi->id]);
    echo "bloc Nos cours : cours 2 et {$course->id}\n";
}

// (2) Biographies
$bios = [
 'Dr. Ronda Železný-Green' => "Changeuse numérique qui conçoit des systèmes pour renforcer les personnes noires, les femmes, les personnes en situation de handicap et les communautés historiquement exclues. Deux décennies sur cinq continents.",
 'Jessica Brown' => "Plus de six ans à diriger le marketing à l'intersection de la technologie, du genre, du climat et de la justice sociale.",
 'Yaovi Habib Lazare Houndekindo' => "Professionnel IT avec plus de 10 ans en formation, gestion de projet et développement. Master en transmission de données et sécurité de l'information ; Cisco CCNA ; bilingue FR/EN.",
 'Maathangi Mohan' => "Analyste de données devenue professionnelle du développement, spécialisée dans les stratégies sensibles au genre pour les communautés marginalisées.",
];
$default = "Membre de l'équipe de facilitation des cours de données et d'IA de datocracy.";
$cm = $DB->get_field('course_modules', 'id', ['idnumber' => 'page-formateurs', 'course' => 1]);
$ctx = context_module::instance($cm);
foreach ($DB->get_records('block_instances', ['parentcontextid' => $ctx->id, 'blockname' => 'elan_instructor_area']) as $bi) {
    $c = unserialize(base64_decode($bi->configdata));
    for ($n = 1; $n <= 3; $n++) {
        $name = $c->{"instructor_name$n"} ?? '';
        if ($name === '') { continue; }
        $role = preg_replace('~<br.*$~s', '', $c->{"instructor_designation$n"});
        $c->{"instructor_designation$n"} = $role . '<br><span class="dato-bio">' . ($bios[$name] ?? $default) . '</span>';
    }
    $DB->set_field('block_instances', 'configdata', base64_encode(serialize($c)), ['id' => $bi->id]);
}
echo "biographies ajoutées\n";
purge_all_caches();
echo "ok\n";
