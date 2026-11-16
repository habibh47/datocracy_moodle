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
    // Éléments d'interface ajoutés côté navigateur : 2e bouton du hero, bouton unique du blog, bascule FR/EN, « Read More ».
    function addExtras() {
        var hero = document.querySelector('.block_elan_banner_1 .main-banner-content .default-btn');
        if (hero && !document.querySelector('.dato-btn-outline')) {
            var a = document.createElement('a'); a.className = 'dato-btn-outline'; a.href = '/course/index.php'; a.textContent = 'Découvrir les cours';
            hero.parentNode.insertBefore(a, hero.nextSibling);
        }
        var blog = document.querySelector('.block_elan_blog_area .container');
        if (blog && !blog.querySelector('.dato-blog-all')) {
            var d = document.createElement('div'); d.className = 'dato-blog-all'; d.innerHTML = '<a class="default-btn" href="/blog/index.php">Tous les articles</a>'; blog.appendChild(d);
        }
        var box = document.querySelector('.others-option');
        if (box && !box.querySelector('.dato-lang-switch')) {
            var fr = (document.documentElement.lang || '').indexOf('fr') === 0;
            var s = document.createElement('div'); s.className = 'option-item dato-lang-switch';
            var url = function (l) { var u = new URL(location.href); u.searchParams.set('lang', l); return u.toString(); };
            s.innerHTML = '<a class="' + (fr ? 'on' : '') + '" href="' + url('fr') + '">FR</a><a class="' + (fr ? '' : 'on') + '" href="' + url('en') + '">EN</a>';
            box.insertBefore(s, box.firstChild);
        }
        var w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null);
        var node;
        while ((node = w.nextNode())) {
            if (node.nodeValue.indexOf('Read More') !== -1) { node.nodeValue = node.nodeValue.replace('Read More', 'Lire la suite'); }
        }
        document.querySelectorAll('.navbar-nav > li.dropdown').forEach(function (li) {
            if (/Fran\u00e7ais|English/.test(li.textContent)) { li.style.display = 'none'; }
        });
    }
    function init() { addContactCard(); addExtras(); setTimeout(addExtras, 1200); setTimeout(openFirstFaq, 900); }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); } else { init(); }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', rewrite); } else { rewrite(); }
    setTimeout(rewrite, 1500);
})();