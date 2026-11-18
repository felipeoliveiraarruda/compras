@props([
    'title' => null,
    'subtitle' => null,
    'breadcrumbs' => null,
    'backRoute' => null,
    'backLabel' => 'Voltar',
])

<div {{ $attributes->merge(['class' => 'mb-6 -mt-1']) }}>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1 min-w-0">
            @if(isset($breadcrumbs) && is_array($breadcrumbs))
                <nav class="flex text-sm text-gray-500 mb-1 space-x-2" aria-label="Breadcrumb">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        @if($index > 0)
                            <span class="text-gray-400">/</span>
                        @endif
                        @if(!empty($breadcrumb['route']))
                            <a href="{{ route($breadcrumb['route']) }}" class="hover:text-gray-700 hover:underline">
                                {{ $breadcrumb['label'] }}
                            </a>
                        @else
                            <span class="font-medium text-gray-700 truncate" aria-current="page">{{ $breadcrumb['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif

            @if($title)
                <h1 class="text-2xl font-bold text-gray-900 truncate">
                    {{ $title }}
                </h1>
            @endif

            @if($subtitle)
                <p class="text-sm text-gray-500 mt-1">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2 sm:gap-3 flex-shrink-0">
            {{ $actions ?? '' }}

            @if($backRoute)
                <a href="{{ route($backRoute) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-gray-700 shadow-sm text-sm sm:text-base font-medium">
                    <i class="fa fa-arrow-left"></i>
                    <span>{{ $backLabel }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
