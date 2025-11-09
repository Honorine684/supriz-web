@if($users->isNotEmpty())
    @foreach($users as $user)
        @if ($user->deleted_at == NULL)
            <div class="p-3 mt-4 bg-gray-100 rounded">
                <div class="flex items-center justify-between">
                    <span class="text-green-500">Création de compte</span>
                    <span class="text-gray-400">{{ $user->created_at->format('H:i') }}</span>
                </div>
                <p>L’utilisateur {{ $user->nom }} {{ $user->prenom }} vient de créer son compte</p>
            </div>
        @else 
            <div class="p-3 mt-4 bg-gray-100 rounded">
                <div class="flex items-center justify-between">
                    <span class="text-red-500">Suppression de compte</span>
                    <span class="text-gray-400">{{ $user->deleted_at->format('H:i') }}</span>
                </div>
                <p>L’utilisateur {{ $user->nom }} {{ $user->prenom }} vient de supprimer son compte</p>
            </div>
        @endif
    @endforeach
@else
    <p class="text-center text-design">Aucune donnée disponible</p>
@endif
