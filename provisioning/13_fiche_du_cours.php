<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/modlib.php');
global $DB, $USER;
$USER = get_admin(); \core\session\manager::set_user($USER);
$w = 'https://staging.datocracy.ai'; $b = $w.'/theme/elan/pix/brand/';
$chk = function($t){ return '<li><i class="ri-check-line"></i> '.$t.'</li>'; };
$html = '<div class="dato-course">'
.'<div class="dc-main">'
 .'<p class="dc-meta"><span><i class="ri-group-line"></i> 95+ apprenants</span><span><i class="ri-book-open-line"></i> 7 chapitres</span><span><i class="ri-time-line"></i> 18 h</span></p>'
 .'<img class="dc-cover" src="'.$b.'course1.png" alt="Cours en ligne sur la fracture numérique de genre">'
 .'<p class="dc-tabs"><a class="on" href="#apercu">Aperçu</a><a href="#programme">Programme (7 chapitres)</a><a href="#formateur">Formateur</a><a href="#avis">Avis</a></p>'
 .'<h3 id="apercu">À propos du cours</h3><p class="dc-lead">Cours en ligne gratuit de datocracy. Apprenez à votre rythme avec des vidéos, des ressources PDF, des quiz et une certification.</p>'
 .'<h3>Ce qui est inclus</h3><ul class="dc-incl"><li><i class="ri-check-line"></i> 7 chapitres</li><li><i class="ri-check-line"></i> 25 vidéos (4 h)</li><li><i class="ri-check-line"></i> 32 ressources PDF</li><li><i class="ri-check-line"></i> 340 questions de quiz</li></ul>'
 .'<h3>Pour qui est ce cours ?</h3><ul class="dc-list">'
  .$chk("Personnel d'ONG et acteurs du changement communautaire").$chk('Agents publics et gouvernementaux').$chk('Propriétaires de micro, petites et moyennes entreprises').$chk('Toute personne qui veut utiliser la donnée avec confiance')
 .'</ul>'
 .'<h3>Certification</h3><p class="dc-cert"><i class="ri-star-line"></i> Terminez le cours et réussissez le quiz pour recevoir 1 certification datocracy.</p>'
 .'<h3 id="programme">Programme (7 chapitres)</h3><ul class="dc-chap"><li>Chapitre 1</li><li>Chapitre 2</li><li>Chapitre 3</li></ul>'
.'</div>'
.'<aside class="dc-side"><p class="dc-price">Gratuit</p>'
 .'<a class="dc-btn" href="'.$w.'/enrol/index.php?id=2">S\'inscrire gratuitement !</a>'
 .'<a class="dc-btn alt" href="'.$w.'/login/index.php">Ajouter aux favoris</a>'
 .'<h4>Ce cours comprend</h4><ul>'
  .'<li><i class="ri-play-line"></i> 25 vidéos · 4 h</li><li><i class="ri-file-text-line"></i> 340 questions de quiz</li><li><i class="ri-download-2-line"></i> 32 ressources PDF</li><li><i class="ri-award-line"></i> 1 certification</li><li><i class="ri-time-line"></i> 18 h d\'apprentissage</li>'
 .'</ul></aside></div>';
if ($cm = $DB->get_record('course_modules', ['idnumber'=>'page-fiche-cours','course'=>1])) course_delete_module($cm->id);
$mi = new stdClass(); $mi->modulename='page'; $mi->module=$DB->get_field('modules','id',['name'=>'page']);
$mi->course=1; $mi->section=0; $mi->visible=1; $mi->visibleoncoursepage=1; $mi->name='Cours en ligne sur la fracture numérique de genre'; $mi->cmidnumber='page-fiche-cours';
$mi->introeditor=['text'=>'','format'=>FORMAT_HTML,'itemid'=>0]; $mi->page=['text'=>'<p></p>','format'=>FORMAT_HTML,'itemid'=>0];
$mi->display=5; $mi->printintro=0; $mi->printheading=0; $mi->printlastmodified=0; $mi->showdescription=0; $mi->groupmode=0; $mi->groupingid=0; $mi->completion=0;
$mi = add_moduleinfo($mi, get_course(1));
$cmid = $mi->coursemodule; $inst = $DB->get_field('course_modules','instance',['id'=>$cmid]);
$DB->update_record('page', (object)['id'=>$inst,'content'=>$html,'contentformat'=>FORMAT_HTML,'timemodified'=>time()]);
purge_all_caches(); echo "fiche cmid=$cmid\n";
