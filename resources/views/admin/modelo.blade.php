<div class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Importar Arquivo CSV</h2>

    <!-- Mensagens de Alerta -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="import" class="space-y-4">
        <!-- Input do Arquivo CSV -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo CSV</label>
            <input 
                type="file" 
                wire:model="csv_file" 
                accept=".csv,.txt"
                class="block w-full text-sm text-gray-500 border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
            >
            @error('csv_file') 
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Input Opcional do Nome da Tabela -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nome da Tabela <span class="text-xs text-gray-400">(Opcional)</span>
            </label>
            <input 
                type="text" 
                wire:model="table_name" 
                placeholder="Ex: clientes_2026"
                class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
            >
            @error('table_name') 
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Botão de Submissão com Indicador de Loading -->
        <div class="pt-2">
            <button 
                type="submit" 
            >
                <!-- Spinner enquanto processa -->
                <svg wire:loading wire:target="import" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <span wire:loading.remove wire:target="import">Processar e Criar Tabela</span>
                <span wire:loading wire:target="import">Importando dados...</span>
            </button>
        </div>
    </form>
</div>