<?php
// Version anglaise du site : les textes français sont enveloppés avec leur traduction anglaise dans des balises
// « multilang » (filtre Moodle multilang) ; Moodle affiche la langue choisie par le visiteur.
// Les traductions viennent des pages « EN » du Figma (provisioning/data/traductions_fr_en.json, copié dans /tmp/trans.json),
// complétées à la main (provisioning/data/traductions_complement.json, copié dans /tmp/trans2.json).
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->libdir . '/filterlib.php');
global $DB;
$dict = json_decode(file_get_contents('/tmp/trans.json'), true);
if (is_readable('/tmp/trans2.json')) { $dict = array_merge($dict, json_decode(file_get_contents('/tmp/trans2.json'), true)); }
$unmatched = [];

function dato_ml($fr, $en) { return '<span lang="fr" class="multilang">' . $fr . '</span><span lang="en" class="multilang">' . $en . '</span>'; }
function dato_key($s) { return trim(preg_replace('/\s+/u', ' ', str_replace(['&nbsp;', "\xC2\xA0", '&#39;', '’'], [' ', ' ', "'", "'"], $s))); }

/** Traduit une chaîne (texte ou HTML) ; retourne la chaîne avec balises multilang. */
function dato_tr($s) {
    global $dict, $unmatched;
    if (!is_string($s) || trim($s) === '' || strpos($s, 'class="multilang"') !== false) { return $s; }
    if (preg_match('~^(https?:|/|ri-|#|<img)~', $s) || preg_match('~\.(png|jpg|jpeg|gif|svg)$~i', $s) || !preg_match('/\p{L}{2}/u', $s)) { return $s; }
    $k = dato_key($s);
    if (isset($dict[$k])) { return dato_ml($s, $dict[$k]); }
    if (strpos($s, '<') !== false) {
        $parts = preg_split('/(<[^>]*>)/u', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
        $changed = false;
        foreach ($parts as $i => $p) {
            if ($p === '' || $p[0] === '<') { continue; }
            $kk = dato_key($p);
            if ($kk !== '' && isset($dict[$kk])) { $parts[$i] = dato_ml($p, $dict[$kk]); $changed = true; }
            elseif ($kk !== '' && preg_match('/\p{L}{3}/u', $kk)) { $unmatched[$kk] = 1; }
        }
        return $changed ? implode('', $parts) : $s;
    }
    $unmatched[$k] = 1;
    return $s;
}
function dato_walk($v) {
    if (is_string($v)) { return dato_tr($v); }
    if (is_array($v)) { foreach ($v as $k => $x) { $v[$k] = dato_walk($x); } return $v; }
    if (is_object($v)) { foreach (get_object_vars($v) as $k => $x) { $v->$k = dato_walk($x); } return $v; }
    return $v;
}

// 1. Filtre multilang sur le contenu et les titres
filter_set_global_state('multilang', TEXTFILTER_ON);
set_config('stringfilters', 1);
set_config('filterall', 1);

// 2. Blocs Elan
foreach ($DB->get_records('block_instances') as $bi) {
    if (strpos($bi->blockname, 'elan_') !== 0) { continue; }
    $c = unserialize(base64_decode($bi->configdata));
    if (!is_object($c)) { continue; }
    $keep = ['img', 'shape', 'class', 'btn_link', 'link', 'video', 'courses', 'posts', 'btn_icon', 'top_icon'];
    foreach (get_object_vars($c) as $k => $v) {
        if (in_array($k, $keep) || preg_match('~(img|icon|link|image)~i', $k)) { continue; }
        $c->$k = dato_walk($v);
    }
    $DB->set_field('block_instances', 'configdata', base64_encode(serialize($c)), ['id' => $bi->id]);
}
echo "blocs traduits\n";

// 3. Pages (noms et contenu), cours, catégories
foreach ($DB->get_records('page') as $p) {
    $DB->update_record('page', (object) ['id' => $p->id, 'name' => dato_tr($p->name), 'content' => dato_tr((string) $p->content)]);
}
foreach ($DB->get_records('course', null, '', 'id,fullname,summary') as $c) {
    if ($c->id == 1) { continue; }
    $DB->update_record('course', (object) ['id' => $c->id, 'fullname' => dato_tr($c->fullname), 'summary' => dato_tr((string) $c->summary)]);
}
foreach ($DB->get_records('course_categories') as $cat) {
    $DB->update_record('course_categories', (object) ['id' => $cat->id, 'name' => dato_tr($cat->name)]);
}
echo "pages, cours et catégories traduits\n";

// 4. Réglages du thème Elan (pied de page, bouton d'en-tête)
foreach (['footer_col_1_title', 'footer_col_1_body', 'footer_col_2_title', 'footer_col_2_body', 'footer_col_3_title', 'footer_col_3_body',
          'footer_col_4_title', 'footer_col_4_body', 'footer_col_5_title', 'footer_col_5_body', 'footer_copyright', 'header_btn_text'] as $k) {
    $v = get_config('theme_elan', $k);
    if (is_string($v) && $v !== '') { set_config($k, dato_tr($v), 'theme_elan'); }
}
// 5. Menu principal : une ligne par langue (4e champ = langue)
$base = get_config('core', 'custommenuitems');
if (strpos($base, '||') === false) {
    $out = [];
    foreach (preg_split('/\r?\n/', $base) as $line) {
        $f = explode('|', $line);
        if (count($f) < 2) { continue; }
        $en = $dict[dato_key($f[0])] ?? $f[0];
        $out[] = $f[0] . '|' . $f[1] . '||fr';
        $out[] = $en . '|' . $f[1] . '||en';
    }
    set_config('custommenuitems', implode("\n", $out));
}
purge_all_caches();
echo "réglages du thème et menu traduits\n";
ksort($unmatched);
echo "--- Non traduits (" . count($unmatched) . ") :\n" . implode("\n", array_keys($unmatched)) . "\n";
