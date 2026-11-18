<div class="text-sm font-black text-gray-800 mb-3 border-b border-amber-200 pb-2 flex items-center gap-2 font-bold uppercase tracking-wider">
    <i class="fas fa-crown text-amber-500"></i>
    Hierárquico
</div>
<div class="permissao-hierarquica space-y-2 px-1">
  @foreach (App\Models\User::$permissoesHierarquia as $p)
    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white cursor-pointer transition-colors border border-transparent hover:border-gray-100 group">
        <div class="relative flex items-center">
            <input type="radio" name="level" value="{{ $p }}"
                   class="form-radio w-5 h-5 text-amber-500 border-2 border-gray-300 focus:ring-amber-500 transition-all cursor-pointer">
        </div>
        @php
            $levelColorMap = [
                'danger' => 'text-red-600 font-bold',
                'warning' => 'text-amber-600 font-bold',
                'success' => 'text-green-600 font-bold',
                'primary' => 'text-portal font-bold',
                'secondary' => 'text-gray-500',
            ];
            $colorClass = $levelColorMap[isset($user) ? $user->labelLevel($p) : "secondary"] ?? 'text-gray-500';
        @endphp
        <span class="text-sm {{ $colorClass }} uppercase tracking-wide">
            {{ $p }}
        </span>
    </label>
  @endforeach
</div>
