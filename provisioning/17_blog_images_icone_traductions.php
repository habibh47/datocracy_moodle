<?php
// Retours de relecture : (1) les images des articles de blog doivent être dans le contexte SYSTÈME (sinon le bloc Elan
// n'obtient pas d'URL) ; (2) icône manquante de « Nous vous comprenons » ; (3) « Read More » du bloc blog en français.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB, $CFG;
require_once($CFG->dirroot . '/blog/lib.php');
require_once($CFG->dirroot . '/blog/locallib.php');
$fs = get_file_storage();
$sys = context_system::instance();

// (1) Images des articles de blog
foreach ($DB->get_records('post', ['module' => 'blog']) as $e) {
    $uctx = context_user::instance($e->userid);
    foreach ($fs->get_area_files($uctx->id, 'blog', 'attachment', $e->id, 'id', false) as $f) {
        if (!$fs->file_exists($sys->id, 'blog', 'attachment', $e->id, '/', $f->get_filename())) {
            $fs->create_file_from_storedfile(['contextid' => $sys->id], $f);
        }
    }
    $be = new blog_entry($e->id);
    $att = $be->get_attachments();
    echo "article {$e->id} : " . count($att) . ' image(s)' . (count($att) ? ' ' . $att[0]->url : '') . "\n";
}

// (2) Icône manquante
foreach ($DB->get_records('block_instances', ['blockname' => 'elan_features_area_two']) as $bi) {
    $c = unserialize(base64_decode($bi->configdata));
    if (($c->title ?? '') === 'Vos besoins en data et IA, relevons-les ensemble') {
        $c->features_icon2 = 'ri-time-line';
        $DB->set_field('block_instances', 'configdata', base64_encode(serialize($c)), ['id' => $bi->id]);
        echo "icône corrigée (bloc {$bi->id})\n";
    }
}

// (3) Traduction du libellé « Read More » (surcharge de langue conservée dans moodledata)
$strings = get_string_manager()->load_component_strings('block_elan_blog_area', 'en', true);
$dir = $CFG->dataroot . '/lang/fr_local';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
$out = "<?php\n\$string = [];\n";
foreach ($strings as $k => $v) {
    if (stripos($v, 'read more') !== false) {
        $out .= "\$string['$k'] = 'Lire la suite';\n";
        echo "traduction : $k\n";
    }
}
file_put_contents($dir . '/block_elan_blog_area.php', $out);
purge_all_caches();
echo "ok\n";
