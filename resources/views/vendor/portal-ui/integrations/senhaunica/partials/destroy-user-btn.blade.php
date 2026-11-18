@if (!$user->env)
  <form method="POST" action="{{ url(config('senhaunica.userRoutes')) }}/{{ $user->id }}">
    @method('DELETE')
    @csrf
    <button class="text-gray-400 hover:text-red-500 transition-all p-1.5 rounded-lg hover:bg-red-50" 
            onclick="return confirm('Tem certeza que deseja remover este usuário?')"
            title="Remover usuário">
      <i class="fas fa-trash-alt"></i>
    </button>
  </form>
@else
  <div title="Usuário gerenciado pelo env">
    <button class="text-gray-300 p-1.5 rounded-lg cursor-not-allowed" style="pointer-events: none;" disabled>
      <i class="fas fa-lock"></i>
    </button>
  </div>
@endif
