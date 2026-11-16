// datocracy : pour les visiteurs non connectés, les liens vers la fiche native d'un cours (réservée aux inscrits)
// mènent à la fiche publique du cours.
(function () {
    var publicsheets = {'2': '/mod/page/view.php?id=17'};
    function isLoggedIn() {
        return !!document.querySelector('.usermenu, [data-region="usermenu"], .dato-shell, .dato-player');
    }
    function addLogin() {
        var box = document.querySelector('.others-option');
        if (!box || box.querySelector('.dato-login')) { return; }
        var item = document.createElement('div');
        item.className = 'option-item';
        item.innerHTML = '<a class="dato-login" href="/login/index.php">Connexion</a>';
        box.insertBefore(item, box.firstChild);
    }
    function rewrite() {
        if (isLoggedIn()) { return; }
        addLogin();
        document.querySelectorAll('a[href*="/course/view.php?id="]').forEach(function (a) {
            var m = a.getAttribute('href').match(/\/course\/view\.php\?id=(\d+)/);
            if (m && publicsheets[m[1]]) { a.setAttribute('href', publicsheets[m[1]]); }
        });
    }
    // Contact : carte « Envoyer un message » dans la colonne vide du bloc Elan (renvoie au formulaire natif de Moodle).
    function addContactCard() {
        document.querySelectorAll('.block_elan_contact_area .contact-image').forEach(function (box) {
            if (box.querySelector('.dato-contact-card')) { return; }
            box.innerHTML = '<div class="dato-contact-card"><h3>Envoyez-nous un message</h3><p>Une question sur nos cours ou un projet de formation ? Nous répondons sous 24 h.</p><a class="default-btn" href="/user/contactsitesupport.php">Envoyer le message</a></div>';
        });
    }
    // FAQ : première question ouverte, comme dans la maquette.
    function openFirstFaq() {
        var t = document.querySelector('.block_elan_faq .accordion-title');
        if (t && !document.querySelector('.block_elan_faq .accordion-item.active, .block_elan_faq .accordion-title.active')) { t.click(); }
    }
    function init() { addContactCard(); setTimeout(openFirstFaq, 900); }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); } else { init(); }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', rewrite); } else { rewrite(); }
    setTimeout(rewrite, 1500);
})();