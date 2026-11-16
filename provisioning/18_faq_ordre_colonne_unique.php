<?php
// FAQ en une seule colonne : le bloc Elan met les questions paires à gauche et impaires à droite ; en colonne unique
// l'ordre de lecture serait « paires puis impaires ». On permute donc la numérotation pour obtenir l'ordre voulu.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
foreach ($DB->get_records('block_instances', ['blockname' => 'elan_faq']) as $bi) {
    $c = unserialize(base64_decode($bi->configdata));
    if (!empty($c->dato_reordered)) { continue; }
    $n = (int) $c->faqnumber;
    $d = [];
    for ($i = 1; $i <= $n; $i++) { $d[$i] = [$c->{"item_title$i"} ?? '', $c->{"item_content$i"} ?? '']; }
    $k = intdiv($n, 2);
    $new = [];
    for ($j = 1; $j <= $k; $j++) { $new[2 * $j] = $d[$j]; }
    for ($j = 1; $j <= $n - $k; $j++) { $new[2 * $j - 1] = $d[$k + $j]; }
    foreach ($new as $i => $pair) { $c->{"item_title$i"} = $pair[0]; $c->{"item_content$i"} = $pair[1]; }
    $c->dato_reordered = 1;
    $DB->set_field('block_instances', 'configdata', base64_encode(serialize($c)), ['id' => $bi->id]);
    echo "FAQ bloc {$bi->id} : $n questions réordonnées\n";
}
purge_all_caches();