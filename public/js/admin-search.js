/**
 * Recherche live sur toutes les pages admin.
 * S'attache à tout input avec l'attribut `data-admin-search`.
 *
 * Cibles automatiquement:
 * - Toutes les <tr> à l'intérieur de <tbody> dans <main>
 * - Les cards de commande (.commande-section > div)
 * - Tout enfant direct de conteneur marqué [data-search-list]
 */
(function () {
    'use strict';

    function normalize(s) {
        return (s || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function collectRows(main) {
        const rows = [];
        // Table rows
        main.querySelectorAll('tbody tr').forEach((tr) => {
            if (tr.querySelector('td')) rows.push(tr);
        });
        // Cards de commande (partial commande-card)
        main.querySelectorAll('.commande-section > .bg-white').forEach((c) => rows.push(c));
        // Conteneurs marqués manuellement
        main.querySelectorAll('[data-search-list] > *').forEach((c) => rows.push(c));
        return rows;
    }

    function ensureEmptyState(container) {
        let empty = container.querySelector('[data-search-empty]');
        if (!empty) {
            empty = document.createElement('div');
            empty.setAttribute('data-search-empty', '');
            empty.className = 'p-6 text-center text-gray-400 text-sm hidden';
            empty.textContent = 'Aucun résultat pour cette recherche.';
            container.appendChild(empty);
        }
        return empty;
    }

    function apply(input) {
        const query = normalize(input.value.trim());
        const main = document.querySelector('main') || document.body;
        const rows = collectRows(main);
        if (rows.length === 0) return;

        let visibleCount = 0;
        rows.forEach((row) => {
            const text = normalize(row.textContent);
            const match = query === '' || text.includes(query);
            row.style.display = match ? '' : 'none';
            if (match) visibleCount += 1;
        });

        // Empty state sur le premier tbody vide
        main.querySelectorAll('tbody').forEach((tb) => {
            const visibleTr = Array.from(tb.querySelectorAll('tr')).some(
                (tr) => tr.style.display !== 'none'
            );
            const emptyRow = tb.querySelector('[data-search-empty-row]');
            if (query && !visibleTr) {
                if (!emptyRow) {
                    const cols = tb.querySelector('tr')?.querySelectorAll('td').length || 1;
                    const tr = document.createElement('tr');
                    tr.setAttribute('data-search-empty-row', '');
                    tr.innerHTML = `<td colspan="${cols}" class="p-4 text-center text-gray-400 text-sm">Aucun résultat</td>`;
                    tb.appendChild(tr);
                }
            } else if (emptyRow) {
                emptyRow.remove();
            }
        });

        // Empty state global pour cards (commande, etc.)
        main.querySelectorAll('.commande-section.active').forEach((section) => {
            const allCards = section.querySelectorAll(':scope > .bg-white');
            const anyVisible = Array.from(allCards).some((c) => c.style.display !== 'none');
            const empty = ensureEmptyState(section);
            empty.classList.toggle('hidden', !(query && !anyVisible));
        });
    }

    function init() {
        const inputs = document.querySelectorAll('input[data-admin-search], input[placeholder="Recherche"]');
        inputs.forEach((input) => {
            input.setAttribute('data-admin-search', '');
            let timer = null;
            input.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => apply(input), 120);
            });
            // Also enter key
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    input.value = '';
                    apply(input);
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
