@php
    $levelColorMap = [
        'danger' => 'bg-red-100 text-red-700 border-red-200',
        'warning' => 'bg-amber-100 text-amber-700 border-amber-200',
        'success' => 'bg-green-100 text-green-700 border-green-200',
        'primary' => 'bg-blue-100 text-blue-700 border-blue-200',
        'secondary' => 'bg-gray-100 text-gray-700 border-gray-200',
    ];
    $colorClass = $levelColorMap[$user->labelLevel()] ?? 'bg-blue-100 text-blue-700 border-blue-200';
    $permissionsRoute = route(config('senhaunica.userRoutes') . '.show', $user->id);
@endphp

    <button type="button" data-portal-permissions-open="{{ $permissionsRoute }}"
        class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $colorClass }} transition-all hover:brightness-95"
        title="Permissões hierárquicas">
    {{ $user->level }}
    {{ $user->env ? '(env)' : '' }}
</button>
