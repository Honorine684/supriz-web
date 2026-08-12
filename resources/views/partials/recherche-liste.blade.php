{{-- Recherche instantanée côté navigateur pour les pages du dashboard.
     Filtre au fil de la frappe (champ placeholder="Recherche") :
       - les lignes de tableau (tbody tr, hors lignes d'en-tête) ;
       - les cartes marquées avec la classe js-recherche-item.
     Insensible à la casse et aux accents.
     N.B. : sur les listes PAGINÉES, le champ porte data-recherche-serveur et
     est ignoré ici — la recherche se fait côté serveur (?q=) sur tout le
     catalogue, sinon on ne filtrerait que la page affichée. --}}
<script>
    (function () {
        var champs = document.querySelectorAll('input[placeholder="Recherche"]:not([data-recherche-serveur])');
        if (!champs.length) return;

        function normaliser(texte) {
            return texte.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }

        function cibles() {
            var liste = Array.prototype.slice.call(document.querySelectorAll('.js-recherche-item'));
            document.querySelectorAll('table tbody tr').forEach(function (tr) {
                if (!tr.querySelector('th')) liste.push(tr);
            });
            return liste;
        }

        champs.forEach(function (champ) {
            champ.addEventListener('input', function () {
                var terme = normaliser(champ.value.trim());
                cibles().forEach(function (el) {
                    var visible = terme === '' || normaliser(el.textContent).indexOf(terme) !== -1;
                    el.style.display = visible ? '' : 'none';
                });
            });
        });

        // Recherche SERVEUR (listes paginées) : on soumet le formulaire tout
        // seul après une courte pause de frappe, pour chercher dans TOUT le
        // catalogue sans que l'utilisateur ait à appuyer sur Entrée.
        document.querySelectorAll('input[placeholder="Recherche"][data-recherche-serveur]').forEach(function (champ) {
            // Après le rechargement, on redonne le focus et on place le curseur
            // en fin de texte pour pouvoir continuer à taper sans cliquer.
            if (champ.value) {
                champ.focus();
                var v = champ.value;
                champ.value = '';
                champ.value = v;
            }
            var minuteur = null;
            champ.addEventListener('input', function () {
                if (minuteur) clearTimeout(minuteur);
                minuteur = setTimeout(function () {
                    if (champ.form) champ.form.submit();
                }, 450);
            });
        });
    })();
</script>
