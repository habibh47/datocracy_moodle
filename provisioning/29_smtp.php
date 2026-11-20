<?php
// Envoi des e-mails de Moodle (confirmation d'inscription, mot de passe oublié) par SMTP Google Workspace.
// Le mot de passe d'application est lu dans /tmp/smtp_pw.txt (copié temporairement dans le conteneur, jamais versionné).
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
$pw = trim(file_get_contents('/tmp/smtp_pw.txt'));
set_config('smtphosts', 'smtp.gmail.com:587');
set_config('smtpsecure', 'tls');
set_config('smtpauthtype', 'LOGIN');
set_config('smtpuser', 'learn@datocracy.ai');
set_config('smtppass', $pw);
set_config('noreplyaddress', 'learn@datocracy.ai');
purge_all_caches();
echo "SMTP configuré\n";
