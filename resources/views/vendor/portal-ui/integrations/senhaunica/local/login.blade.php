@extends(config('portal-ui.integrations.senhaunica.layout', 'portal-ui::layouts.app'))

@section('title', 'Login Local')

@section('content')
@php
    $brandName = config('portal-ui.brand.name', config('app.name', 'Sistema'));
@endphp

<div class="flex items-center justify-center py-12">
    <div class="w-full max-w-md">
        <!-- Brand/Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-portal tracking-tighter flex items-center justify-center gap-2">
                <i class="fas fa-user-lock"></i>
                Gestão de Usuários
            </h1>
            <p class="text-gray-500 font-medium mt-1">Acesso para Usuários Locais</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl shadow-portal/10 border border-gray-100 overflow-hidden">
            <div class="bg-portal-gradient h-2 w-full"></div>
            
            <div class="p-8">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                        <div class="flex items-center gap-2 text-red-700 font-bold mb-1">
                            <i class="fas fa-exclamation-circle"></i>
                            Ops! Algo deu errado
                        </div>
                        <ul class="text-xs text-red-600 space-y-1 mt-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('SenhaunicaLocalLoginAs') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">E-mail</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-portal text-gray-400 transition-colors">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" name="email" id="email" required
                                   class="form-input block w-full pl-11 pr-4 py-3 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:bg-white focus:border-portal focus:ring-4 focus:ring-portal/10 transition-all outline-none text-gray-700 placeholder-gray-400"
                                   placeholder="seu@email.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Senha</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-portal text-gray-400 transition-colors">
                                <i class="fas fa-key"></i>
                            </div>
                            <input type="password" name="password" id="password" required
                                   class="form-input block w-full pl-11 pr-4 py-3 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:bg-white focus:border-portal focus:ring-4 focus:ring-portal/10 transition-all outline-none text-gray-700 placeholder-gray-400"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 bg-portal-gradient text-white font-black rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-portal/20 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                        Entrar no Sistema
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-center">
                    <a href="{{ url('/') }}" class="text-sm font-bold text-portal hover:text-portal-dark transition-colors flex items-center gap-2">
                        <i class="fas fa-home"></i>
                        Voltar para {{ $brandName }}
                    </a>
                </div>
            </div>
        </div>
        
        <p class="text-center text-gray-400 text-xs mt-8 font-medium">
            &copy; {{ date('Y') }} SenhaUnica Socialite • {{ $brandName }}
        </p>
    </div>
</div>
@endsection
