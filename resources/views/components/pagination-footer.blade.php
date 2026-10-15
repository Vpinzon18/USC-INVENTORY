@props(['paginator', 'label' => 'registros'])

@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $firstItem = $paginator->firstItem() ?? 0;
    $lastItem = $paginator->lastItem() ?? 0;
    $total = $paginator->total();
    $pageSize = $paginator->perPage();
    $sizeOptions = \App\Support\PaginationOptions::SIZES;
    $pageNumbers = $lastPage <= 7
        ? range(1, max(1, $lastPage))
        : array_values(array_unique(array_merge(
            [1, 2],
            range(max(2, $currentPage - 1), min($lastPage - 1, $currentPage + 1)),
            [$lastPage - 1, $lastPage]
        )));
    sort($pageNumbers);

    $pageElements = [];
    $previousPage = 0;
    foreach ($pageNumbers as $pageNumber) {
        if ($previousPage > 0 && $pageNumber - $previousPage > 1) {
            $pageElements[] = '...';
        }

        $pageElements[] = [$pageNumber => $paginator->appends(request()->query())->url($pageNumber)];
        $previousPage = $pageNumber;
    }
@endphp

<div class="w-full border-t border-slate-200 bg-slate-50 px-5 py-4">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <p class="text-xs font-medium text-slate-600" aria-live="polite">
            Mostrando <span class="font-bold text-slate-800">{{ $firstItem }}</span>
            a <span class="font-bold text-slate-800">{{ $lastItem }}</span>
            de <span class="font-bold text-slate-800">{{ $total }}</span> {{ $label }}
        </p>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between lg:justify-end">
            @if ($paginator->hasPages())
                <nav aria-label="Paginación de registros">
                    <ul class="flex items-center gap-1 text-xs">
                        <li>
                            @if ($paginator->onFirstPage())
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-400" aria-disabled="true" aria-label="Página anterior">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L9.06 10l3.71 3.71a.75.75 0 1 1-1.06 1.06l-4.24-4.24a.75.75 0 0 1 0-1.06l4.24-4.24a.75.75 0 0 1 1.08 0Z" clip-rule="evenodd"/></svg>
                                </span>
                            @else
                                <a href="{{ $paginator->appends(request()->query())->url($currentPage - 1) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-600 transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700" rel="prev" aria-label="Página anterior">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L9.06 10l3.71 3.71a.75.75 0 1 1-1.06 1.06l-4.24-4.24a.75.75 0 0 1 0-1.06l4.24-4.24a.75.75 0 0 1 1.08 0Z" clip-rule="evenodd"/></svg>
                                </a>
                            @endif
                        </li>

                        @foreach ($pageElements as $element)
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <li>
                                        @if ((int) $page === $currentPage)
                                            <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-blue-600 bg-blue-600 px-2 font-bold text-white" aria-current="page">{{ $page }}</span>
                                        @else
                                            <a href="{{ $paginator->appends(request()->query())->url($page) }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-slate-300 bg-white px-2 font-semibold text-slate-600 transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700" aria-label="Ir a la página {{ $page }}">{{ $page }}</a>
                                        @endif
                                    </li>
                                @endforeach
                            @else
                                <li><span class="inline-flex h-9 min-w-9 items-center justify-center px-1 text-slate-400" aria-hidden="true">{{ $element }}</span></li>
                            @endif
                        @endforeach

                        <li>
                            @if ($paginator->hasMorePages())
                                <a href="{{ $paginator->appends(request()->query())->url($currentPage + 1) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-600 transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700" rel="next" aria-label="Página siguiente">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L10.94 10 7.23 6.29a.75.75 0 1 1 1.06-1.06l4.24 4.24a.75.75 0 0 1 0 1.06l-4.24 4.24a.75.75 0 0 1-1.08 0Z" clip-rule="evenodd"/></svg>
                                </a>
                            @else
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-400" aria-disabled="true" aria-label="Página siguiente">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L10.94 10 7.23 6.29a.75.75 0 1 1 1.06-1.06l4.24 4.24a.75.75 0 0 1 0 1.06l-4.24 4.24a.75.75 0 0 1-1.08 0Z" clip-rule="evenodd"/></svg>
                                </span>
                            @endif
                        </li>
                    </ul>
                </nav>
            @endif

            <div class="flex items-center gap-2 text-xs text-slate-500">
                <label for="pagination-size-{{ spl_object_id($paginator) }}" class="whitespace-nowrap font-semibold">Filas por página</label>
                <select id="pagination-size-{{ spl_object_id($paginator) }}" data-pagination-size class="rounded-lg border border-slate-300 bg-white px-3 py-2 font-semibold text-slate-700 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" aria-label="Filas por página">
                    @foreach ($sizeOptions as $size)
                        <option value="{{ $size }}" @selected((int) $pageSize === $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

@once
    <script>
        document.addEventListener('change', function (event) {
            if (!event.target.matches('[data-pagination-size]')) return;

            const url = new URL(window.location.href);
            url.searchParams.set('per_page', event.target.value);
            url.searchParams.delete('page');
            window.location.assign(url.toString());
        });
    </script>
@endonce
