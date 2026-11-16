<?php
// Le filtre multilang doit s'appliquer aux titres : « stringfilters » reçoit la liste des filtres (et non 1).
// Les noms de pages et de cours restent en balises <span lang="..."> (syntaxe {mlang} abandonnée).
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
set_config('stringfilters', 'multilang');
set_config('filterall', 1);
function back($s) {
    return preg_replace_callback('~\{mlang fr\}(.*?)\{mlang\}\{mlang en\}(.*?)\{mlang\}~us',
        function ($m) { return '<span lang="fr" class="multilang">' . $m[1] . '</span><span lang="en" class="multilang">' . $m[2] . '</span>'; }, $s);
}
foreach ($DB->get_records('page', null, '', 'id,name') as $r) { $DB->set_field('page', 'name', back($r->name), ['id' => $r->id]); }
foreach ($DB->get_records('course', null, '', 'id,fullname') as $r) { if ($r->id > 1) { $DB->set_field('course', 'fullname', back($r->fullname), ['id' => $r->id]); } }
purge_all_caches();
echo "ok\n";