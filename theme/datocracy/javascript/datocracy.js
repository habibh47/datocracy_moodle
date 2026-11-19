// datocracy : pour les visiteurs non connectés, les liens vers la fiche native d'un cours (réservée aux inscrits)
// mènent à la fiche publique du cours.
(function () {
    var publicsheets = {'2': '/mod/page/view.php?id=17'};
    var EN = (document.documentElement.lang || '').toLowerCase().indexOf('en') === 0;
    function T(fr, en) { return EN ? en : fr; }
    function isLoggedIn() {
        return !!document.querySelector('.usermenu, [data-region="usermenu"], .dato-shell, .dato-player');
    }
    function addLogin() {
        var box = document.querySelector('.others-option');
        if (!box || box.querySelector('.dato-login')) { return; }
        var item = document.createElement('div');
        item.className = 'option-item';
        item.innerHTML = '<a class="dato-login" href="/login/index.php">' + T('Connexion', 'Sign in') + '</a>';
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
            box.innerHTML = '<div class="dato-contact-card"><h3>' + T('Envoyez-nous un message', 'Send us a message') + '</h3><p>' + T('Une question sur nos cours ou un projet de formation ? Nous répondons sous 24 h.', 'A question about our courses or a training project? We reply within 24 hours.') + '</p><a class="default-btn" href="/user/contactsitesupport.php">' + T('Envoyer le message', 'Send message') + '</a></div>';
        });
    }
    // FAQ : première question ouverte, comme dans la maquette.
    function openFirstFaq() {
        var t = document.querySelector('.block_elan_faq .accordion-title');
        if (t && !document.querySelector('.block_elan_faq .accordion-item.active, .block_elan_faq .accordion-title.active')) { t.click(); }
    }
    // Éléments d'interface ajoutés côté navigateur : 2e bouton du hero, bouton unique du blog, bascule FR/EN, « Read More ».
    function addExtras() {
        document.querySelectorAll('.single-courses-box').forEach(function (c) {
            var title = (c.querySelector('h3') || {}).textContent || '';
            var author = c.querySelector('.author span');
            var list = c.querySelector('.courses-box-list');
            if (/MPME/.test(title)) {
                var price = c.querySelector('.price'); if (price) { price.textContent = T('Bientôt', 'Coming soon'); }
                if (author) { author.textContent = T('Outils et données mobiles', 'Mobile tools and data'); c.querySelector('.author').classList.add('dato-sub'); }
                if (list) { list.classList.add('dato-hide'); }
                c.querySelectorAll('a').forEach(function (a) { a.setAttribute('href', '#'); a.style.pointerEvents = 'none'; });
            } else if (list && list.children.length >= 2 && !list.dataset.dato) {
                list.dataset.dato = '1';
                list.children[0].innerHTML = '<i class="ri-book-open-line"></i> ' + T('7 chapitres', '7 chapters');
                list.children[1].innerHTML = '<i class="ri-time-line"></i> 18 h';
            }
        });        if (EN) {
            document.querySelectorAll('.others-option .default-btn').forEach(function (b) { if (b.textContent.trim() === 'Inscription') { b.textContent = 'Sign up'; } });
            document.querySelectorAll('.category-list a').forEach(function (a) { if (a.textContent.trim() === 'Cours en ligne') { a.textContent = 'Online course'; } });
        }        var first = document.querySelector('.block_elan_features_area_two');
        if (first) { first.classList.add('dato-center'); }
        document.querySelectorAll('.single-courses-box .price').forEach(function (p) { if (!p.textContent.trim()) { p.textContent = T('Gratuit', 'Free'); } });
        var hero = document.querySelector('.block_elan_banner_1 .main-banner-content .default-btn');
        if (hero && !document.querySelector('.dato-btn-outline')) {
            var a = document.createElement('a'); a.className = 'dato-btn-outline'; a.href = '/course/index.php'; a.textContent = T('Découvrir les cours', 'Discover courses');
            hero.parentNode.insertBefore(a, hero.nextSibling);
        }
        var blog = document.querySelector('.block_elan_blog_area .container');
        if (blog && !blog.querySelector('.dato-blog-all')) {
            var d = document.createElement('div'); d.className = 'dato-blog-all'; d.innerHTML = '<a class="default-btn" href="/blog/index.php">' + T('Tous les articles', 'All articles') + '</a>'; blog.appendChild(d);
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
            if (!EN && node.nodeValue.indexOf('Read More') !== -1) { node.nodeValue = node.nodeValue.replace('Read More', 'Lire la suite'); }
        }
        document.querySelectorAll('.navbar-nav > li.dropdown').forEach(function (li) {
            if (/Fran\u00e7ais|English/.test(li.textContent)) { li.style.display = 'none'; }
        });
    }
    // Écran scindé (connexion, inscription, mot de passe oublié) : le menu étant masqué, on ajoute un lien sur le logo
    // et une barre « Retour à l'accueil » / « Connexion » pour ne pas enfermer le visiteur.
    function addAuthNav() {
        var id = document.body.id;
        if (['page-login-index', 'page-login-signup', 'page-login-forgot_password'].indexOf(id) === -1 || document.querySelector('.dato-auth-nav')) { return; }
        var logo = document.createElement('a');
        logo.className = 'dato-auth-logo'; logo.href = '/'; logo.setAttribute('aria-label', T('Retour à l\'accueil', 'Back to home'));
        document.body.appendChild(logo);
        var nav = document.createElement('div');
        nav.className = 'dato-auth-nav';
        var html = '<a href="/">&larr; ' + T('Retour à l\'accueil', 'Back to home') + '</a>';
        if (id !== 'page-login-index') { html += '<a href="/login/index.php">' + T('Déjà un compte ? Connexion', 'Already registered? Sign in') + '</a>'; }
        nav.innerHTML = html;
        document.body.appendChild(nav);
    }    function init() { addAuthNav(); addContactCard(); addExtras(); setTimeout(addExtras, 1200); setTimeout(openFirstFaq, 900); }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); } else { init(); }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', rewrite); } else { rewrite(); }
    setTimeout(rewrite, 1500);
})();