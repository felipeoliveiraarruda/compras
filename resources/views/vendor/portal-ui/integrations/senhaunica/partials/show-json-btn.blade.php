@if ($json = $user->hasSenhaunicaJson())
  <button type="button"
          class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-all p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30"
          data-portal-json-open
          data-url="{{ route('SenhaunicaGetJsonModalContent', ['id' => $user->id]) }}"
          title="Ver JSON de autenticação">
    <x-portal::icon name="fa-file-code" style="regular" class="text-lg" />
  </button>
@else
  <span class="text-gray-300 dark:text-gray-600 text-xs">-</span>
@endif
