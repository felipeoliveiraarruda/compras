<form method="POST" action="{{ route('SenhaunicaLoginAs') }}">
  @csrf
  <input type="hidden" name="codpes" value="{{ $user->codpes }}">
  <button type="submit" class="text-gray-400 hover:text-portal transition-all p-1.5 rounded-lg hover:bg-portal/10" title="Assumir identidade">
    <i class="fas fa-user-ninja text-lg"></i>
  </button>
</form>
