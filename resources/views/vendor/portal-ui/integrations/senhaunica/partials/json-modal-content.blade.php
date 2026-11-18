<div class="bg-portal-gradient text-white py-4 px-6 flex items-center justify-between">
    <h5 class="flex items-center gap-2 font-bold text-lg">
        <i class="fas fa-code"></i>
        JSON: {{ $user->codpes }} - {{ $user->name }}
    </h5>
    <div class="text-[10px] bg-white/20 px-2 py-1 rounded font-mono uppercase tracking-widest">
        Gerado em: {{ $date }}
    </div>
</div>
<div class="p-4 overflow-hidden">
    <pre class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-green-300 p-4 rounded-xl overflow-x-auto text-xs font-mono shadow-inner border border-gray-300 dark:border-gray-700 leading-relaxed max-h-[40vh] scrollbar-thin scrollbar-thumb-portal/20">{{ $json }}</pre>
</div>
