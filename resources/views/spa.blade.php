@php
    $config = [
        'appName' => config('app.name'),
        'locale' => ($locale = app()->getLocale()),
        'locales' => config('app.locales'),
        'csrfToken' => csrf_token(),
    ];
    $appJs = mix('dist/js/app.js');
    $appCss = mix('dist/css/app.css');
@endphp
<!DOCTYPE html>
<html lang="{{ $config['locale'] }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('app/images/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('app/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('app/images/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('app/images/favicons/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('app/images/favicons/safari-pinned-tab.svg') }}" color="#313131">
    <link rel="shortcut icon" href="{{ asset('app/images/favicons/favicon.ico') }}">
    <meta name="apple-mobile-web-app-title" content="Skaalab Test">
    <meta name="application-name" content="Skaalab Test">
    <meta name="msapplication-TileColor" content="#fcfcfc">
    <meta name="msapplication-config" content="{{ asset('app/images/favicons/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">

    <title>{{ $config['appName'] }}</title>

    {{-- Laravel config --}}
    <script type="text/javascript">
        window.Laravel = {
            appName: "{{ $config['appName'] }}",
            locale: "{{ $config['locale'] }}",
            csrfToken: "{{ $config['csrfToken'] }}",
            baseUrl: "{{ env('APP_URL') }}",
            echo_port: '{{ env('LARAVEL_WEBSOCKETS_PORT') }}',
            app_host: '{{ env('APP_HOST') }}',
            app_url: '{{ env('APP_url') }}',
            app_port: '{{ env('APP_port') }}',
            app_version: '{{ config('version.code') }}',
            app_version_name: '{{ config('version.name') }}',
        }
    </script>
    <link rel="stylesheet" href="{{ $appCss }}">
</head>

<body>
    <div id="app"></div>

    @if (env('APP_ENV') == 'production')
        <script type="text/javascript">
            console.clear()
            console.log('%c Stop!', 'color: #f00; font-size: 60px; font-weight: bold;');
            console.log(
                '%c Il s’agit d’une fonctionnalité de navigateur conçue pour les développeurs. Si quelqu’un vous a invité(e) à copier-coller quelque chose ici pour activer une fonctionnalité ou soit-disant pirater le compte d’un tiers, sachez que c’est une escroquerie permettant à cette personne d’accéder à votre compte. \nToute action ayant pour but de porter préjudice à l\'application Skaalab Test ou aux serveurs de la NLDEV sera automatiquement considérée comme une cybercriminalité et sera punie par la loi.',
                'font-size: 25px;');
        </script>
    @endif
    {{-- <script src="{{ url('/js/echo-realtime.js') }}" type="text/javascript"></script> --}}
    <script src="http://{{ env('APP_HOST') }}:{{ env('LARAVEL_WEBSOCKETS_PORT') }}/socket.io/socket.io.js"></script>
    <script src="{{ $appJs }}"></script>
</body>

</html>
