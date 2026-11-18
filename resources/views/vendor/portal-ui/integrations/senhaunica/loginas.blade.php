@extends(config('portal-ui.integrations.senhaunica.layout', 'portal-ui::layouts.app'))

@section('title', 'LoginAs')

@section('content')
    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
        Senhaunica-socialite <i class="fas fa-angle-right text-sm"></i> Login as
    </h1>

    <form method="POST" action="{{ route('SenhaunicaLoginAs') }}" class="flex flex-wrap items-end gap-3">
        @csrf
        <div class="flex flex-col">
            <label for="codpes" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número Usp</label>
            <input id="codpes" type="text" name="codpes" value="{{ old('codpes') }}" required autofocus
                class="w-48 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-portal focus:border-portal @error('codpes') border-red-500 @enderror">
            @error('codpes')
                <span class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-portal text-white text-sm font-medium rounded-lg hover:bg-portal-dark transition-colors">
            Entrar
        </button>
    </form>
@endsection
