<?php
// Thème enfant datocracy (parent : Elan, puis Boost).
defined('MOODLE_INTERNAL') || die();

$THEME->name = 'datocracy';
$THEME->parents = ['elan', 'boost'];
$THEME->sheets = [];
$THEME->editor_sheets = [];

// SCSS : contenu du parent + feuille datocracy (voir lib.php).
$THEME->scss = function($theme) {
    return theme_datocracy_get_main_scss_content($theme);
};
$THEME->csspostprocess = 'theme_datocracy_process_css';

// Seule la mise en page du tableau de bord est remplacée ; les autres viennent d'Elan.
$THEME->layouts = [
    // Espace apprenant : coque applicative (menu marine) ; « standard » ne l'applique qu'aux pages de l'espace connecté.
    'mycourses' => ['file' => 'datocracy_app.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre', 'options' => ['nonavbar' => true]],
    'mypublic' => ['file' => 'datocracy_app.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre'],
    'standard' => ['file' => 'datocracy_standard.php', 'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'], 'defaultregion' => 'side-pre'],
    'incourse' => ['file' => 'datocracy_player.php', 'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'], 'defaultregion' => 'side-pre'],
    'mydashboard' => [
        'file' => 'datocracy_my.php',
        'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'],
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true, 'langmenu' => true, 'nocontextheader' => true],
    ],
];

$THEME->javascripts_footer = ['datocracy'];
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->iconsystem = '\\theme_elan\\output\\icon_system_fontawesome';
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
