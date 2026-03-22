@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="opacity:.35;"><i class="bi bi-chevron-left" style="font-size:.75rem;"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <i class="bi bi-chevron-left" style="font-size:.75rem;"></i>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="border:none;background:none;color:var(--muted);">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active"><span>{{ $page }}</span></span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">
                <i class="bi bi-chevron-right" style="font-size:.75rem;"></i>
            </a>
        @else
            <span style="opacity:.35;"><i class="bi bi-chevron-right" style="font-size:.75rem;"></i></span>
        @endif
    </div>
@endif