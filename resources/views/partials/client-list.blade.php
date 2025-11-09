@if($users->isNotEmpty())
    @foreach($users as $user)
        <tr>
            <td class="px-4 py-2 w-40 border-b border-b-gray-50">
                <span class="text-[13px] font-medium text-gray-400">
                    @if ($user->profil_image)
                        <img class="profil-photo" src="{{ asset('storage/' . $user->profil_image) }}" alt="">
                    @else
                        <img class="profil-photo" src="{{ asset('images/profil/no-profile.png') }}" alt="Image par défaut">
                    @endif
                </span>
            </td>
            <td class="px-4 py-2 w-40 border-b border-b-gray-50">
                <span class="text-[13px] font-medium text-gray-400">{{$user->nom}}</span>
            </td>
            <td class="px-4 py-2 w-40 border-b border-b-gray-50">
                <span class="text-[13px] font-medium text-gray-400">{{$user->prenom}}</span>
            </td>
            <td class="px-4 py-2 w-40 border-b border-b-gray-50">
                <span class="text-[13px] font-medium text-gray-400">{{$user->numero}}</span>
            </td>
            <td class="px-4 py-2 w-40 border-b border-b-gray-50">
                <span class="text-[13px] font-medium text-gray-400">{{$user->email}}</span>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center text-gray-500 py-4">Aucun client trouvé</td>
    </tr>
@endif
