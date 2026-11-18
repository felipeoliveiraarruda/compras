@php
    $brand = config('portal-ui.brand', []);
    $assets = config('portal-ui.assets', []);
    $colors = config('portal-ui.colors', []);
    $documentTitle = $documentTitle ?? ($brand['name'] ?? config('app.name', 'Sistema'));
    $favicon = $brand['favicon'] ?? 'favicon.ico';
    $primary = $colors['primary'] ?? '#0b7c93';
    $primaryDark = $colors['primary_dark'] ?? '#095f71';
    $primarySoft = $colors['primary_soft'] ?? '#0d94a8';
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="color-scheme" content="light dark">
<meta name="supported-color-schemes" content="light dark">
<meta name="theme-color" content="{{ $primary }}" id="portal-ui-color-meta">

<title>{{ $documentTitle }}</title>

<style>
    :root {
        --portal-ui-primary: {{ $primary }};
        --portal-ui-primary-dark: {{ $primaryDark }};
        --portal-ui-primary-soft: {{ $primarySoft }};
    }

    .portal-ui-switching *,
    .portal-ui-switching *::before,
    .portal-ui-switching *::after {
        transition: none !important;
    }
</style>

<!-- Script para evitar FOUC no dark mode -->
<script>
    Promise.resolve().then(() => {
        const isDark = localStorage.getItem('portal-ui-dark') === 'true' ||
            (!('portal-ui-dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        const root = document.documentElement;
        const themeColor = document.querySelector('#portal-ui-color-meta');

        root.classList.toggle('dark', isDark);
        root.style.colorScheme = isDark ? 'dark' : 'light';
        root.dataset.portalUiMode = isDark ? 'dark' : 'light';

        if (themeColor) {
            themeColor.setAttribute('content', isDark ? '{{ $primaryDark }}' : '{{ $primary }}');
        }
    });
</script>

@if(! empty($favicon))
    <link rel="icon" href="{{ asset($favicon) }}" type="image/x-icon">
@endif

@if(! empty($assets['fontawesome_cdn']))
    <link rel="stylesheet" href="{{ $assets['fontawesome_cdn_url'] ?? 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.0.0/css/all.min.css' }}">
@endif

@if(($assets['mode'] ?? 'published') === 'published' && ! empty($assets['load_css']))
    <link rel="stylesheet" href="{{ asset($assets['css_path'] ?? 'vendor/portal-ui/portal-ui.css') }}">
@endif

@stack('portal-ui-head')
@stack('styles')
