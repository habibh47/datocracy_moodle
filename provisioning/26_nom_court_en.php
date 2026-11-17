<?php
// Nom court du cours 2 traduit (affiché dans l'onglet du navigateur) ; filtre multilang actif sur les titres.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$DB->set_field('course', 'shortname', '<span lang="fr" class="multilang">Fracture numérique de genre</span><span lang="en" class="multilang">Gender digital divide</span>', ['id' => 2]);
purge_all_caches();
echo "ok\n";