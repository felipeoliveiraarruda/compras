@props([
    'prepend' => '',
    'append' => '',
    'label' => '',
    'groupClass' => '',
    'class' => '',
    'id' => 'select-'.mt_rand(1000000, 9999999),
])

<div
    data-portal-person-select
    data-search-url="{{ route('SenhaunicaFindUsers') }}"
    class="senhaunica-select-pessoa mb-4 {{ $groupClass }} relative"
>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-gray-700 mb-1 border-none">{{ $label }}</label>
    @endif

    <div class="relative flex items-stretch">
        @if($prepend)
            <div class="flex items-center px-3 bg-gray-50 border-2 border-r-0 border-gray-100 rounded-l-xl text-gray-500 text-sm">
                {!! $prepend !!}
            </div>
        @endif

        <div class="relative flex-1">
            <input type="hidden" name="codpes" data-portal-person-value>

            <button
                id="{{ $id }}"
                type="button"
                data-portal-person-toggle
                aria-expanded="false"
                class="group block w-full text-left px-4 py-3 bg-white border-2 border-gray-100 {{ $prepend ? 'rounded-r-none' : 'rounded-l-xl' }} {{ $append ? 'rounded-l-none' : 'rounded-r-xl' }} focus:border-portal focus:ring-4 focus:ring-portal/10 transition-all outline-none text-sm shadow-sm hover:border-portal/40 hover:shadow-md text-gray-500 {{ $class }}"
                {{ $attributes }}
            >
                <span class="flex items-center gap-3 pr-8">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-portal/10 text-portal">
                        <x-portal::icon name="fa-user" class="text-sm" />
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-[11px] font-black uppercase tracking-[0.16em] text-gray-400">Busca de pessoa</span>
                        <span class="block truncate font-medium" data-portal-person-label>Digite o nome ou número USP...</span>
                    </span>
                </span>
                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400 transition-transform duration-200" data-portal-person-chevron>
                    <x-portal::icon name="fa-chevron-down" class="text-xs" />
                </span>
            </button>

            <div
                data-portal-person-menu
                class="hidden absolute z-[1100] mt-2 w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl ring-1 ring-black/5"
            >
                <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white p-3">
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <span class="text-[11px] font-black uppercase tracking-[0.16em] text-gray-400">Digite para buscar</span>
                        <button type="button" data-portal-person-clear class="hidden text-[11px] font-bold text-portal hover:text-portal-dark transition-colors">
                            Limpar
                        </button>
                    </div>
                    <div class="relative">
                        <x-portal::icon name="fa-search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs" />
                        <input
                            type="search"
                            data-portal-person-search
                            autocomplete="off"
                            placeholder="Mínimo 4 caracteres..."
                            class="form-input w-full rounded-xl border-0 bg-gray-100 pl-9 pr-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-portal/20 outline-none"
                        >
                    </div>
                </div>

                <div class="max-h-72 overflow-y-auto border-t border-gray-100">
                    <div data-portal-person-status class="p-5 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        Digite pelo menos 4 caracteres
                    </div>
                    <div data-portal-person-results class="hidden p-2"></div>
                </div>

                <template data-portal-person-result-template>
                    <button type="button" class="mb-1 flex w-full items-start gap-3 rounded-xl px-3 py-3 text-left text-sm text-gray-700 transition-colors hover:bg-portal/10 focus:bg-portal/10 last:mb-0">
                        <span data-portal-person-result-id class="mt-0.5 inline-flex shrink-0 rounded-lg bg-portal/10 px-2 py-1 text-[11px] font-black tracking-wide text-portal"></span>
                        <span class="min-w-0 flex-1">
                            <span data-portal-person-result-name class="block font-semibold text-gray-900"></span>
                            <span class="mt-0.5 block text-[11px] font-medium uppercase tracking-wide text-gray-400">Selecionar pessoa</span>
                        </span>
                    </button>
                </template>
            </div>
        </div>

        @if(isset($slot) && (string) $slot !== '')
            {{ $slot }}
        @endif

        @if($append)
            <div class="flex items-center px-3 bg-gray-50 border-2 border-l-0 border-gray-100 rounded-r-xl text-gray-500 text-sm">
                {!! $append !!}
            </div>
        @endif
    </div>

    @error('codpes')
        <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span>
    @enderror
</div>
