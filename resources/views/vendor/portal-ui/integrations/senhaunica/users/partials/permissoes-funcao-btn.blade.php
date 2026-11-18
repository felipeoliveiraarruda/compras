@php
    $permissionsRoute = route(config('senhaunica.userRoutes') . '.show', $user->id);
@endphp
@foreach ($user->roles->all() as $r)
  <button type="button" data-portal-permissions-open="{{ $permissionsRoute }}"
          class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200 transition-all hover:brightness-95 cursor-pointer"
          title="Funções">
    {{ $r->name }}
  </button>
@endforeach
