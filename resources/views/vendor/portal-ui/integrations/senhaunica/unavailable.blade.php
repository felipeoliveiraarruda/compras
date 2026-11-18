@extends(config('portal-ui.integrations.senhaunica.layout', 'portal-ui::layouts.app'))

@section('title', 'Acesso indisponível')

@section('content')
    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Senhaunica-socialite</h1>

    @if ($reason == 'noLocalUser')
        <div class="bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300 rounded-lg p-4 mt-4">
            <div class="font-semibold text-base mb-1">Usuário sem acesso!</div>
            <p class="text-sm">Seu usuário não está autorizado nesse sistema!</p>
            <a href="{{ url('/') }}" class="text-sm underline hover:no-underline">Retorne ao início</a>
        </div>
    @else
        <div class="bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300 rounded-lg p-4 mt-4">
            <div class="font-semibold text-base mb-1">Serviço indisponível!</div>
            <p class="text-sm">Inclua no seu arquivo App\Models\User o trait da senhaunica:</p>
            <div class="ml-3 mt-1"><code class="text-xs bg-red-100 dark:bg-red-900/50 px-1 py-0.5 rounded">use \Uspdev\SenhaunicaSocialite\Traits\HasSenhaunica;</code></div>
        </div>
    @endif
@endsection
