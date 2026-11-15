<?php
define("CLI_SCRIPT", true); require("/var/www/html/config.php");
set_config("scss", file_get_contents("/tmp/brand.scss"), "theme_elan"); purge_all_caches(); echo "scss ok\n";
