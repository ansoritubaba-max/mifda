<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MIFDA') }}</title>
        <meta name="description" content="Platform belajar interaktif untuk siswa Madrasah Ibtidaiyah">

        <!-- PWA -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon" href="/icon-192x192.png">

        <!-- Font: Plus Jakarta Sans — modern, tebal, tech feel -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Slot untuk CSS tambahan dari halaman tertentu (masuk ke <head>) --}}
        @stack('head-styles')

        <style>
            /*
             * PENDEKATAN BENAR: body + inherit, TANPA !important global.
             *
             * Kenapa: "*, *::before, *::after { font-family: X !important }"
             * mematikan font FA & summernote icon karena tidak bisa dioverride.
             * Solusi: set di body saja, biarkan FA/summernote icon font menang
             * via specificity normalnya.
             */
            body {
                font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            }
            /*
             * Input, select, button, textarea TIDAK inherit font dari body
             * secara default di browser — paksa inherit agar pakai Plus Jakarta Sans.
             */
            input, select, textarea, button,
            optgroup, option, datalist {
                font-family: inherit;
            }

            :root {
                --color-primary: #4f46e5;
                --color-accent:  #06b6d4;
            }
        </style>
    </head>
    <body class="antialiased bg-slate-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            {{-- Page Heading: dark tech gradient --}}
            @if (isset($header))
                <header class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border-b border-indigo-900/50 shadow-2xl shadow-indigo-900/20">
                    <div class="max-w-7xl mx-auto py-5 md:py-7 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>
        {{-- Slot untuk script tambahan dari halaman tertentu (masuk sebelum </body>) --}}
        @stack('scripts')

        <!-- PWA Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js', { scope: '/' })
                        .then(reg => {
                            console.log('[MIFDA SW] Registered, scope:', reg.scope);
                            // Cek update SW di background
                            reg.update();
                        })
                        .catch(err => console.warn('[MIFDA SW] Registration failed:', err));
                });
            }
        </script>
    </body>
</html>
