<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$fp = $DB->get_record('course', ['id'=>1]);
$ctx = context_course::instance(1);
function addblock($name, array $cfg, $weight) {
  global $DB, $ctx;
  $o = new stdClass(); foreach ($cfg as $k=>$v) $o->$k = $v;
  $DB->insert_record('block_instances', (object)[
    'blockname'=>$name, 'parentcontextid'=>$ctx->id, 'showinsubcontexts'=>0,
    'pagetypepattern'=>'site-index', 'subpagepattern'=>null, 'defaultregion'=>'fullwidth-top',
    'defaultweight'=>$weight, 'configdata'=>base64_encode(serialize($o)), 'timecreated'=>time(), 'timemodified'=>time()]);
  echo "added $name\n";
}
function clearblocks() { global $DB, $ctx;
  $DB->delete_records('block_instances', ['parentcontextid'=>$ctx->id, 'pagetypepattern'=>'site-index']);
}
