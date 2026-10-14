<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const urlParams = new URLSearchParams(window.location.search);
                const queryAppearance = urlParams.get('appearance');
                const serverAppearance = '{{ $appearance ?? "system" }}';
                let localAppearance = null;
                try {
                    localAppearance = localStorage.getItem('appearance');
                } catch (e) {}

                const effective = queryAppearance || (localAppearance && localAppearance !== 'system' ? localAppearance : serverAppearance);
                const isDark = effective === 'dark' || (effective === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

                if (isDark) {
                    document.documentElement.classList.add('dark');
                    document.documentElement.style.colorScheme = 'dark';
                } else {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.style.colorScheme = 'light';
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @if ($googleVerification = config('services.google.site_verification'))
            <meta name="google-site-verification" content="{{ $googleVerification }}">
        @endif

        @if (app()->isProduction() && ($gaId = config('analytics.ga4_id')))
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $gaId }}');
            </script>
        @endif

        @fonts

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body class="bg-background text-foreground font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
