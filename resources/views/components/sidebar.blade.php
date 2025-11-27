<div class="fixed top-0 left-0 z-50 w-64 h-full p-4 transition-transform bg-dark-design sidebar-menu">
    <a href="#" class="flex items-center pb-4">
        <img src={{ asset('images/logo.png') }} alt="" class="logo-size">

    </a>
    <ul class="margin-nav-coustum index-30">
        <li class="mb-1 group @if ($active_link == 'maison') active @endif">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-dashboard-line"></i>
                <span class="text-sm">Dashboard</span>
            </a>
        </li>

        <li class="mb-1 group @if ($active_link == 'c') active @endif">
            <a href="{{ route('admin.clients') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-user-3-line"></i>
                <span class="text-sm">Clients</span>
            </a>
        </li>

        <li class="mb-1 group @if ($active_link == 's') active @endif">
            <a href="{{ route('admin.stocks') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-archive-line"></i>
                <span class="text-sm">Stock</span>
            </a>
        </li>

        <li class="mb-1 group @if ($active_link == 'ca') active @endif">
            <a href="{{ route('admin.categorie') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-list-check"></i>
                <span class="text-sm">Catégorie</span>
            </a>
        </li>

        <li class="mb-1 group @if ($active_link == 'musique') active @endif">
            <a href="{{ route('admin.musique') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-music-2-line"></i>
                <span class="text-sm">Musiques</span>
            </a>
        </li>

        <li class="mb-1 group @if ($active_link == 'r') active @endif">
            <a href="{{ route('admin.rapportJournalier') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-bar-chart-box-line"></i>
                <span class="text-sm">Rapport</span>
            </a>
        </li>

        <li class="mb-1 group @if ($active_link == 'dep') active @endif">
            <a href="{{ route('admin.depenses') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-money-dollar-circle-line"></i>
                <span class="text-sm">Dépenses</span>
            </a>
        </li>

        <li class="mb-1 group @if ($active_link == 'm') active @endif">
            <a href="{{ route('admin.notifications') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-message-2-line"></i>
                <span class="text-sm">Message</span>
            </a>
        </li>

        <li class="mb-1 group @if ($active_link == 'n') active @endif">
            <a href="{{ route('admin.notificationVente') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-notification-line"></i>
                <span class="text-sm">Notifications</span>
            </a>
        </li>

        <li class="group @if ($active_link == 'faq') active @endif">
            <a href="{{ route('admin.faq') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-question-line"></i>
                <span class="text-sm">FAQ</span>
            </a>
        </li>

        <li class="mb-6 group @if ($active_link == 'p') active @endif">
            <a href="{{ route('admin.parametre') }}"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="mr-3 text-lg ri-settings-3-line"></i>
                <span class="text-sm">Paramètres</span>
            </a>
        </li>
    </ul>

    <ul class="mrg-t-8">
        <li class="flex">
            @if (auth()->user() && auth()->user()->profil_image)
            <img class="profil-photo" src="{{ asset('storage/' . auth()->user()->profil_image) }}" alt="">
        @else
            <img class="profil-photo" src="{{ asset('images/profil/no-profile.png') }}" alt="">
        @endif
        

            <div class="grid ml-2"><span class="text-white">Admin</span><span>Admin</span></div>
        </li>
    </ul>


    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" name="submit">
            <span class="p-2 text-white mrg-t-8 top-6 width-custom-btn-deconn rounded-20 btn-design display-flex">
                <img src="{{ asset('images/svg/logout_svgrepo.com.svg') }}" alt="">
                <span>Deconnexion</span>
            </span>
        </button>
    </form>


</div>
<div class="fixed top-0 left-0 z-40 hidden w-full h-full bg-black/50 md:hidden sidebar-overlay"></div>
