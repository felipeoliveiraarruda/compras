@extends('portal-ui::layouts.app')

@section('title', 'Showcase Minimal')

@section('breadcrumbs')
    <a href="{{ Route::has('dashboard') ? route('dashboard') : (Route::has('home') ? route('home') : '#') }}" class="hover:text-gray-700 hover:underline">Dashboard</a>
    <span>/</span>
    <span>Showcase Minimal</span>
@endsection

@section('content')
    @php
        $demoLinks = [
            ['label' => 'Minimal', 'route' => 'portal-ui.demo.minimal'],
            ['label' => 'Simple', 'route' => 'portal-ui.demo'],
            ['label' => 'Admin', 'route' => 'portal-ui.demo.crud'],
            ['label' => 'Guest', 'route' => 'portal-ui.demo.guest'],
        ];
    @endphp

    <div class="space-y-6">
        <x-portal::page-header
            title="Showcase Minimal"
            subtitle="Exemplo mais enxuto para páginas internas com cabeçalho, um card principal e uma ação."
            :breadcrumbs="[
                ['label' => 'Dashboard', 'route' => Route::has('dashboard') ? 'dashboard' : (Route::has('home') ? 'home' : '')],
                ['label' => 'Showcase Minimal', 'route' => ''],
            ]"
        >
            <x-slot name="actions">
                <x-portal::badge variant="secondary">Minimal</x-portal::badge>
                <x-portal::button size="sm" icon="fa-plus">Nova ação</x-portal::button>
            </x-slot>
        </x-portal::page-header>

        @if(collect($demoLinks)->contains(fn ($link) => \Illuminate\Support\Facades\Route::has($link['route'])))
            <x-portal::card>
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Navegação dos exemplos</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Compare os níveis minimal, simple, admin e guest do pacote.</p>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                        @foreach($demoLinks as $link)
                            @if(\Illuminate\Support\Facades\Route::has($link['route']))
                                <x-portal::button
                                    :href="route($link['route'])"
                                    :variant="$link['route'] === 'portal-ui.demo.minimal' ? 'primary' : 'outline'"
                                    size="sm"
                                >
                                    {{ $link['label'] }}
                                </x-portal::button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </x-portal::card>
        @endif

        <x-portal::alert variant="info" title="Layout base">
            Use este exemplo como ponto de partida para páginas internas pequenas.
        </x-portal::alert>

        <x-portal::card>
            <x-slot name="header">
                <div class="flex items-center justify-between gap-3">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Resumo</span>
                    <x-portal::badge variant="success">Ativo</x-portal::badge>
                </div>
            </x-slot>

            <p class="text-sm text-gray-600 dark:text-gray-300">
                O showcase minimal reúne só o essencial: breadcrumbs, título, ação principal, alerta e card.
            </p>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <x-portal::button variant="secondary" full="true">Cancelar</x-portal::button>
                <x-portal::button full="true" icon="fa-save">Salvar</x-portal::button>
            </div>
        </x-portal::card>
    </div>
@endsection
