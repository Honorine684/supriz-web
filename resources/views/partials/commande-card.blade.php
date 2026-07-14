@php
    /** @var \App\Models\Commandes $commande */
    /** @var int|null $totalCalcul */
    $isMoi = false;
    if (!empty($commande->selectedOption)) {
        $so = strtolower($commande->selectedOption);
        $isMoi = $so === 'moi même' || $so === 'moi meme';
    }

    // Normalise un numéro pour WhatsApp (international sans +, ni espace).
    // Bénin: 10 chiffres commençant par 0 -> 229 + 9 chiffres restants
    // 8 chiffres -> 229 + ...
    // Sinon on garde tel quel après nettoyage.
    if (!function_exists('supriz_wa_number')) {
        function supriz_wa_number($num) {
            if (!$num) return null;
            $s = preg_replace('/[^\d+]/', '', (string) $num);
            if ($s === '') return null;
            if (str_starts_with($s, '+')) $s = substr($s, 1);
            if (strlen($s) === 10 && str_starts_with($s, '0')) {
                $s = '229' . substr($s, 1);
            } elseif (strlen($s) === 8) {
                $s = '229' . $s;
            }
            return $s;
        }
    }
    $userWa = supriz_wa_number($commande->user->numero ?? null);
    $beneficiaireWa = supriz_wa_number($commande->numero_beneficier ?? null);

    $statusMap = [
        'encours' => ['label' => 'En cours', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dot' => 'bg-yellow-500'],
        'confirmer' => ['label' => 'Confirmée', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dot' => 'bg-blue-500'],
        'livrée' => ['label' => 'Livrée', 'bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-500'],
    ];
    $status = $statusMap[$commande->status] ?? ['label' => ucfirst($commande->status), 'bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'dot' => 'bg-gray-500'];
    $prixTotal = $totalCalcul
        ?? (($commande->prix_total_commande ?? 0) + ($commande->prix_total_livraison ?? 0));
    $canValidate = $commande->status === 'confirmer';

    // Regroupe les produits par marchand pour construire un message WhatsApp
    // complet et formaté (tous les produits du marchand dans cette commande).
    $marchandGroups = [];
    foreach ($commande->achats as $achatItem) {
        $m = $achatItem->product->marchand ?? null;
        if (!$m) continue;
        $marchandGroups[$m->id]['marchand'] = $m;
        $marchandGroups[$m->id]['achats'][] = $achatItem;
    }

    $marchandMessages = [];
    foreach ($marchandGroups as $mid => $group) {
        $m = $group['marchand'];
        $lignes = ["Prise de commande — {$commande->code_commandes}", ""];
        $multi = count($group['achats']) > 1;
        $i = 1;
        foreach ($group['achats'] as $a) {
            $p = $a->product;
            $prix = $p
                ? number_format(($p->prix_promo ?: $p->price), 0, ',', ' ') . ' F'
                : '—';
            $perso = $a->description_de_personnalisation ?: '—';
            $prefix = $multi ? "{$i}) " : "";
            $indent = $multi ? "   " : "";
            $lignes[] = "{$prefix}Produits: " . ($p->title ?? 'Produit supprimé');
            $lignes[] = "{$indent}Quantité: {$a->qte_achat}";
            $lignes[] = "{$indent}Prix: {$prix}";
            $lignes[] = "{$indent}Personnalisation: {$perso}";
            $lignes[] = "";
            $i++;
        }
        $lignes[] = "Partenaires: {$m->nom}";
        $marchandMessages[$mid] = rawurlencode(implode("\n", $lignes));
    }

    // Texte complet de la commande (copier-coller au clic sur une ligne).
    $copyLines = ["Prise de commande — {$commande->code_commandes}", ""];
    $multiAll = $commande->achats->count() > 1;
    $n = 1;
    foreach ($commande->achats as $a) {
        $p = $a->product;
        $prixItem = $p
            ? number_format(($p->prix_promo ?: $p->price), 0, ',', ' ') . ' F'
            : '—';
        $persoItem = $a->description_de_personnalisation ?: '—';
        $partenaire = ($p && $p->marchand) ? $p->marchand->nom : '—';
        $prefix = $multiAll ? "{$n}) " : "";
        $indent = $multiAll ? "   " : "";
        $copyLines[] = "{$prefix}Produits: " . ($p->title ?? 'Produit supprimé');
        $copyLines[] = "{$indent}Quantité: {$a->qte_achat}";
        $copyLines[] = "{$indent}Prix: {$prixItem}";
        $copyLines[] = "{$indent}Personnalisation: {$persoItem}";
        $copyLines[] = "{$indent}Partenaires: {$partenaire}";
        $copyLines[] = "";
        $n++;
    }
    $copyLines[] = "Total: " . number_format($prixTotal, 0, ',', ' ') . " F";
    $commandeCopyText = implode("\n", $copyLines);

    // Suivi des marchands dont le lien WhatsApp a déjà été affiché (une fois par marchand).
    $linkShownMarchands = [];
@endphp

<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-4">
    {{-- Header --}}
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                 style="background: #005792;">
                <i class="ri-shopping-cart-2-line text-xl" style="color: #ffffff;"></i>
            </div>
            <div>
                <div class="font-mono font-bold text-gray-900 text-base tracking-wider">
                    {{ $commande->code_commandes }}
                </div>
                <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                    <i class="ri-time-line"></i>
                    {{ $commande->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                <span class="w-2 h-2 rounded-full {{ $status['dot'] }}"></span>
                {{ $status['label'] }}
            </span>
        </div>
    </div>

    <div class="p-5 space-y-4">

        {{-- Bloc client + delivery type --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            {{-- Client --}}
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <i class="ri-user-line"></i> Client
                </div>
                <div class="font-semibold text-sm text-gray-800">
                    @if ($commande->user)
                        {{ $commande->user->prenom }} {{ $commande->user->nom }}
                    @else
                        <span class="text-gray-400">Inconnu</span>
                    @endif
                </div>
                @if ($commande->user && $commande->user->email)
                    <a href="mailto:{{ $commande->user->email }}"
                       class="text-xs text-blue-600 hover:underline break-all block mt-0.5">
                        {{ $commande->user->email }}
                    </a>
                @endif
            </div>

            {{-- Téléphone client --}}
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <i class="ri-whatsapp-line text-green-600"></i> WhatsApp client
                </div>
                <div class="font-semibold text-sm text-gray-800">
                    @if ($userWa)
                        <a href="https://wa.me/{{ $userWa }}" target="_blank"
                           class="text-green-600 hover:text-green-700 hover:underline inline-flex items-center gap-1">
                            {{ $commande->user->numero }}
                            <i class="ri-external-link-line text-xs"></i>
                        </a>
                    @else
                        <span class="text-gray-400">Non renseigné</span>
                    @endif
                </div>
            </div>

            {{-- Bénéficiaire --}}
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <i class="{{ $isMoi ? 'ri-user-heart-line' : 'ri-gift-line' }}"></i>
                    {{ $isMoi ? 'Pour lui-même' : 'Bénéficiaire' }}
                </div>
                <div class="font-semibold text-sm text-gray-800">
                    @if ($beneficiaireWa)
                        <a href="https://wa.me/{{ $beneficiaireWa }}" target="_blank"
                           class="text-green-600 hover:text-green-700 hover:underline inline-flex items-center gap-1">
                            <i class="ri-whatsapp-line"></i>
                            {{ $commande->numero_beneficier }}
                        </a>
                    @else
                        <span class="text-gray-400">Non renseigné</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Produits --}}
        <div>
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1">
                <i class="ri-shopping-bag-3-line"></i> Produits
            </div>
            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="w-full min-w-[500px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-3 py-2 font-medium">Produit</th>
                            <th class="px-3 py-2 font-medium">Prix</th>
                            <th class="px-3 py-2 font-medium text-center">Qté</th>
                            <th class="px-3 py-2 font-medium">Personnalisation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commande->achats as $achat)
                            <tr class="border-t border-gray-100 hover:bg-gray-50 cursor-copy"
                                data-copy="{{ $commandeCopyText }}"
                                onclick="copyToClipboard(this)">
                                <td class="px-3 py-2.5">
                                    @if ($achat->product)
                                        <div class="font-medium text-gray-800">{{ $achat->product->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $achat->product->entreprise }}</div>
                                        @if ($achat->product->marchand)
                                            @php
                                                $marchand = $achat->product->marchand;
                                                $marchandWa = supriz_wa_number($marchand->telephone);
                                                // Message complet : tous les produits de ce marchand dans la commande.
                                                $waText = $marchandMessages[$marchand->id] ?? '';
                                                // Le lien « Prendre la commande » ne s'affiche qu'une fois par marchand.
                                                $showLink = !in_array($marchand->id, $linkShownMarchands, true);
                                                if ($showLink) { $linkShownMarchands[] = $marchand->id; }
                                            @endphp
                                            <div class="text-xs mt-1 flex items-center gap-1 flex-wrap">
                                                <i class="ri-store-2-line text-gray-400"></i>
                                                <span class="text-gray-600 font-medium">{{ $marchand->nom }}</span>
                                                @if ($showLink)
                                                    @if ($marchandWa)
                                                        <a href="https://wa.me/{{ $marchandWa }}?text={{ $waText }}" target="_blank"
                                                           onclick="event.stopPropagation();"
                                                           class="text-green-600 hover:text-green-700 hover:underline inline-flex items-center gap-0.5">
                                                            <i class="ri-whatsapp-line"></i> Prendre la commande
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">(WhatsApp non renseigné)</span>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">Produit supprimé</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 text-gray-700 whitespace-nowrap">
                                    @if ($achat->product)
                                        {{ number_format(($achat->product->prix_promo ?: $achat->product->price) + ($achat->product->prix_livraison ?? 0), 0, ',', ' ') }} F
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="inline-block bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-semibold text-xs">
                                        {{ $achat->qte_achat }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-gray-600 text-xs">
                                    {{ $achat->description_de_personnalisation ?: '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Prix + Livraison --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            {{-- Résumé prix --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-4">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-1">
                    <i class="ri-money-euro-circle-line"></i> Détail prix
                </div>
                <div class="space-y-1.5 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Produits</span>
                        <span>{{ number_format($commande->prix_total_commande ?? 0, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Livraison</span>
                        <span>{{ number_format($commande->prix_total_livraison ?? 0, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-200 font-bold text-base">
                        <span class="text-gray-900">Total</span>
                        <span class="text-green-600">{{ number_format($prixTotal, 0, ',', ' ') }} F</span>
                    </div>
                </div>
            </div>

            {{-- Livraison --}}
            <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-xl p-4">
                <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-3 flex items-center gap-1">
                    <i class="ri-truck-line"></i> Livraison
                </div>
                @if ($commande->date_livraison || $commande->heure_livraison)
                    <div class="text-sm text-gray-800 mb-2">
                        <i class="ri-calendar-event-line text-blue-500"></i>
                        @if ($commande->date_livraison)
                            <span class="font-medium">{{ \Carbon\Carbon::parse($commande->date_livraison)->format('d/m/Y') }}</span>
                        @endif
                        @if ($commande->heure_livraison)
                            à <span class="font-medium">{{ \Illuminate\Support\Str::substr($commande->heure_livraison, 0, 5) }}</span>
                        @endif
                    </div>
                @endif
                @if ($commande->adresse_livraison)
                    <div class="text-xs text-gray-700 mb-1 flex items-start gap-1">
                        <i class="ri-map-pin-line text-blue-500 mt-0.5"></i>
                        <span>{{ $commande->adresse_livraison }}</span>
                    </div>
                @endif
                @if ($commande->latitude_livraison && $commande->longitude_livraison)
                    <a href="https://maps.google.com/?q={{ $commande->latitude_livraison }},{{ $commande->longitude_livraison }}"
                       target="_blank"
                       class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium mt-1">
                        <i class="ri-external-link-line"></i> Ouvrir dans Google Maps
                    </a>
                @endif
                @if (!$commande->date_livraison && !$commande->heure_livraison && !$commande->adresse_livraison)
                    <p class="text-xs text-gray-400 italic">Aucune info de livraison enregistrée</p>
                @endif
            </div>
        </div>

        {{-- Marquer comme livrée --}}
        @if ($canValidate)
            <form action="{{ route('admin.close.shop') }}" method="POST"
                  onsubmit="return confirm('Confirmer que la commande {{ $commande->code_commandes }} a été livrée ?');">
                @csrf
                <input type="hidden" name="command_id" value="{{ $commande->id }}">
                <input type="hidden" name="code" value="{{ $commande->code_commandes }}">
                <button type="submit"
                        class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition-all shadow-sm hover:shadow">
                    <i class="ri-truck-line"></i> Marquer comme livrée
                </button>
            </form>
        @elseif ($commande->status === 'livrée')
            <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-2">
                <i class="ri-checkbox-circle-fill text-green-600 text-lg"></i>
                <span class="text-sm text-green-800 font-medium">Commande livrée avec succès</span>
            </div>
        @endif
    </div>
</div>
