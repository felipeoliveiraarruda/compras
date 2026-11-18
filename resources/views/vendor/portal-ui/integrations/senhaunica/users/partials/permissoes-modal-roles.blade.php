<div class="text-sm font-black text-gray-800 mb-3 border-b border-blue-200 pb-2 flex items-center gap-2">
    <i class="fas fa-user-tag text-blue-500"></i>
    Funções (Roles)
</div>
<div class="role-app space-y-2 px-1">
  @forelse ($rolesAplicacao as $p)
    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white cursor-pointer transition-colors border border-transparent hover:border-gray-100 group">
        <div class="relative flex items-center">
            <input type="checkbox" name="role_app[]" value="{{ $p->name }}"
                   class="form-checkbox w-5 h-5 text-blue-500 border-2 border-gray-300 rounded focus:ring-blue-500 transition-all cursor-pointer">
        </div>
        <span class="text-sm font-medium text-gray-600 group-hover:text-gray-900 transition-colors">
            {{ $p->name }}
        </span>
    </label>
  @empty
    <p class="text-xs text-gray-400 italic p-2">Sem funções disponíveis</p>
  @endforelse
</div>
