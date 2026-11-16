<?php
// Le résumé du cours est repris par les cartes d'Elan dans un attribut HTML : il doit rester un simple paragraphe.
// Le détail du cours est dans la page « fiche du cours » (13_fiche_du_cours.php).
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$DB->set_field('course', 'summary', "<p>Cours gratuit en ligne : 7 chapitres, 18 h de contenu, vidéos, ressources PDF et questions d'évaluation, avec certification.</p>", ['id' => 2]);
$DB->set_field('course', 'summaryformat', 1, ['id' => 2]);
purge_all_caches();
echo "résumé du cours simplifié\n";