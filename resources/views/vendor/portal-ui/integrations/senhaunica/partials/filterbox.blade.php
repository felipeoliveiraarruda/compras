<form method="GET" action="{{ route(config('senhaunica.userRoutes') . '.index') }}" class="flex items-stretch">
    <a href="{{ route(config('senhaunica.userRoutes') . '.index') }}?filter=__none__&page=1"
       class="inline-flex items-center px-2 py-1.5 border border-r-0 border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800 rounded-l-lg text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
        <i class="fas fa-times text-xs"></i>
    </a>
    <input type="text" name="filter" placeholder="Filtrar..." id="dt-search" value="{{ $params['filter'] ?? '' }}"
        class="flex-1 min-w-0 px-3 py-1.5 text-sm border border-gray-300 bg-white text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-portal focus:border-portal">
    <input type="hidden" name="sort" value="{{ $params['sort'] }}">
    <input type="hidden" name="direction" value="{{ $params['direction'] }}">
    <input type="hidden" name="page" value="1">
    <button type="submit"
        class="inline-flex items-center px-2 py-1.5 border border-l-0 border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800 rounded-r-lg text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
        <i class="fas fa-search text-xs"></i>
    </button>
</form>
