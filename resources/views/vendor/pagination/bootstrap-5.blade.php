@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            {{-- Results summary --}}
            <div>
                <p class="text-muted mb-0">
                    <span class="fw-medium">{{ $paginator->total() }}</span> results -
                    Page <span class="fw-medium">{{ $paginator->currentPage() }}</span> of
                    <span class="fw-medium">{{ $paginator->lastPage() }}</span>
                </p>
            </div>

            {{-- Page links - Same for both mobile and desktop --}}
            <ul class="pagination mb-0">
                {{-- Previous Button --}}
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->onFirstPage() ? '#' : $paginator->previousPageUrl() }}"
                        aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                {{-- First Page --}}
                <li class="page-item {{ $paginator->currentPage() == 1 ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>

                {{-- Ellipsis if needed before current --}}
                @if ($paginator->currentPage() > 3)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif

                {{-- Pages around current --}}
                @for ($i = max(2, $paginator->currentPage() - 1); $i <= min($paginator->lastPage() - 1, $paginator->currentPage() + 1); $i++)
                    <li class="page-item {{ $paginator->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Ellipsis if needed after current --}}
                @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif

                {{-- Last Page --}}
                @if ($paginator->lastPage() > 1)
                    <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                    </li>
                @endif

                {{-- Next Button --}}
                <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ !$paginator->hasMorePages() ? '#' : $paginator->nextPageUrl() }}"
                        aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
@endif
