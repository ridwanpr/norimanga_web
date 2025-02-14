@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        {{-- Mobile view --}}
        <div class="d-flex d-sm-none w-100">
            <ul class="pagination w-100 justify-content-center">
                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();

                    // Always show first page
                    $pages[] = 1;

                    if ($currentPage > 3) {
                        $pages[] = '...';
                    }

                    // Current page and one before/after
                    if ($currentPage > 1 && $currentPage < $lastPage) {
                        if ($currentPage > 2) {
                            $pages[] = $currentPage - 1;
                        }
                        $pages[] = $currentPage;
                        if ($currentPage < $lastPage - 1) {
                            $pages[] = $currentPage + 1;
                        }
                    }

                    if ($currentPage < $lastPage - 2) {
                        $pages[] = '...';
                    }

                    // Always show last page
                    if ($lastPage > 1) {
                        $pages[] = $lastPage;
                    }
                @endphp

                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">&lsaquo; Prev</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo; Prev</a>
                    </li>
                @endif

                {{-- Page Numbers --}}
                @foreach ($pages as $page)
                    @if ($page === '...')
                        <li class="page-item disabled d-none d-sm-inline" aria-disabled="true">
                            <span class="page-link">...</span>
                        </li>
                    @else
                        <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                            <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &rsaquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Next &rsaquo;</span>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Desktop view --}}
        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div>
                <p class="small text-muted">
                    {!! __('Showing') !!}
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <ul class="pagination">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link" aria-hidden="true">&lsaquo; Prev</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                                aria-label="@lang('pagination.previous')">&lsaquo; Prev</a>
                        </li>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($pages as $page)
                        @if ($page === '...')
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link">...</span>
                            </li>
                        @else
                            <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                                aria-label="@lang('pagination.next')">Next &rsaquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link" aria-hidden="true">Next &rsaquo;</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
