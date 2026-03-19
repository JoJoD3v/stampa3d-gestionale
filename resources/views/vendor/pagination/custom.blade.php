@if ($paginator->hasPages())
<nav class="pagination" aria-label="Pagination">

    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="page-item disabled"><span class="page-link">&lsaquo;</span></span>
    @else
        <span class="page-item"><a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev">&lsaquo;</a></span>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-item disabled"><span class="page-link">{{ $element }}</span></span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-item active"><span class="page-link">{{ $page }}</span></span>
                @else
                    <span class="page-item"><a href="{{ $url }}" class="page-link">{{ $page }}</a></span>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <span class="page-item"><a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="next">&rsaquo;</a></span>
    @else
        <span class="page-item disabled"><span class="page-link">&rsaquo;</span></span>
    @endif

</nav>
@endif
