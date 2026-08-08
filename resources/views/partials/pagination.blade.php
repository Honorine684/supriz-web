{{-- Pagination maison (style dashboard) : Précédent, numéros de page jusqu'à
     la dernière, Suivant. Utilisée via $liste->links('partials.pagination'). --}}
@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-2 flex-wrap mt-4 mb-2">
        @if ($paginator->onFirstPage())
            <span class="p-2 px-4 rounded-20 bg-gray-100 text-gray-400">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="p-2 px-4 rounded-20 bg-white border border-gray-100 text-gray-400 font-medium">&laquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="p-2 px-4 text-gray-400">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="p-2 px-4 rounded-20 text-white bg-green-900 font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="p-2 px-4 rounded-20 bg-white border border-gray-100 text-gray-400 font-medium">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="p-2 px-4 rounded-20 bg-white border border-gray-100 text-gray-400 font-medium">&raquo;</a>
        @else
            <span class="p-2 px-4 rounded-20 bg-gray-100 text-gray-400">&raquo;</span>
        @endif
    </nav>
@endif
