@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden gap-4">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-gray-400 bg-gray-200 cursor-not-allowed">@lang('pagination.previous')</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">@lang('pagination.previous')</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">@lang('pagination.next')</a>
            @else
                <span class="px-4 py-2 text-gray-400 bg-gray-200 cursor-not-allowed">@lang('pagination.next')</span>
            @endif
        </div>

        <div class="hidden sm:flex sm:items-center sm:justify-between gap-2">
            <span class="text-gray-700">
                Showing {{ $paginator->firstItem() == $paginator->lastItem() ? $paginator->firstItem() : $paginator->firstItem().' to '.$paginator->lastItem() }} of {{ $paginator->total() }} results
            </span>

            <div class="flex space-x-2">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-4 py-2 text-gray-400 bg-gray-200 rounded">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-4 py-2 bg-blue-600 text-white rounded">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </nav>
@endif
