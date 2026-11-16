<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Les réglages (couleurs, logos, pied de page...) restent ceux du thème parent Elan : on les prête au thème enfant.
 */
function theme_datocracy_with_elan_settings($theme) {
    $elan = theme_config::load('elan');
    $settings = clone $elan->settings;
    foreach ((array) $theme->settings as $k => $v) {
        $settings->$k = $v;
    }
    $theme->settings = $settings;
    return $theme;
}

function theme_datocracy_get_main_scss_content($theme) {
    global $CFG;
    require_once($CFG->dirroot . '/theme/elan/lib.php');
    $theme = theme_datocracy_with_elan_settings($theme);
    $scss = theme_elan_get_main_scss_content($theme);
    // Feuille datocracy (le parent Elan fournit ses propres feuilles).
    $scss .= "\n" . file_get_contents(__DIR__ . '/scss/datocracy.scss');
    return $scss;
}

/**
 * Les fichiers du thème enfant (logos, favicon) sont servis comme ceux d'Elan.
 */
function theme_datocracy_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM && in_array($filearea, ['main_logo', 'mobile_logo', 'main_footer_logo', 'favicon'])) {
        $theme = theme_config::load('datocracy');
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }
    send_file_not_found();
}

function theme_datocracy_process_css($css, $theme) {
    global $CFG;
    require_once($CFG->dirroot . '/theme/elan/lib.php');
    $theme = theme_datocracy_with_elan_settings($theme);
    return theme_elan_process_css($css, $theme);
}
