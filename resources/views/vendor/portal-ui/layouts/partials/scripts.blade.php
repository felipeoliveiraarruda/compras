@php
    $assets = config('portal-ui.assets', []);
@endphp
@if(($assets['mode'] ?? 'published') === 'published' && ! empty($assets['load_js']))
    <script src="{{ asset($assets['js_path'] ?? 'vendor/portal-ui/portal-ui.js') }}" defer></script>
@endif
@yield('javascripts_bottom')
