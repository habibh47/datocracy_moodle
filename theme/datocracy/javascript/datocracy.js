// datocracy : pour les visiteurs non connectés, les liens vers la fiche native d'un cours (réservée aux inscrits)
// mènent à la fiche publique du cours.
(function () {
    var publicsheets = {'2': '/mod/page/view.php?id=17'};
    function isLoggedIn() {
        return !!document.querySelector('.usermenu, [data-region="usermenu"], .dato-shell, .dato-player');
    }
    function rewrite() {
        if (isLoggedIn()) { return; }
        document.querySelectorAll('a[href*="/course/view.php?id="]').forEach(function (a) {
            var m = a.getAttribute('href').match(/\/course\/view\.php\?id=(\d+)/);
            if (m && publicsheets[m[1]]) { a.setAttribute('href', publicsheets[m[1]]); }
        });
    }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', rewrite); } else { rewrite(); }
    setTimeout(rewrite, 1500);
})();