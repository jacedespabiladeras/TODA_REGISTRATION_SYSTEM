@php
    $userTheme = auth()->check() ? (auth()->user()->theme ?? 'light') : 'light';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ $userTheme }}" class="{{ $userTheme === 'dark' ? 'dark' : '' }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        Sorsogon City Government Portal
    </title>

    <script>
        (function() {
            var theme = "{{ $userTheme }}";
            document.documentElement.setAttribute('data-theme', theme);
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        window.setAppTheme = function(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            if (document.body) {
                document.body.setAttribute('data-theme', theme);
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    document.body.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.body.classList.remove('dark');
                }
            }
            
            // Persist to user account in backend
            var csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';
            
            if (csrfToken) {
                fetch("{{ route('settings.preferences.update') }}", {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ theme: theme })
                }).catch(function(err) {
                    console.error('Error saving theme preference:', err);
                });
            }
        };

        window.toggleAppTheme = function() {
            var current = document.documentElement.getAttribute('data-theme') || 'light';
            var target = (current === 'dark') ? 'light' : 'dark';
            window.setAppTheme(target);
        };
    </script>


    <!-- =========================================
         FONTS
    ========================================== -->

    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    />


    <!-- =========================================
         BOOTSTRAP 5
    ========================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =========================================
         BOOTSTRAP ICONS
    ========================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- =========================================
         LARAVEL VITE
    ========================================== -->

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="font-sans antialiased {{ $userTheme === 'dark' ? 'dark' : '' }}" data-theme="{{ $userTheme }}">


    <div class="min-h-screen app-canvas">


        <!-- =====================================
             PAGE HEADING
        ====================================== -->

        @isset($header)

            <header class="bg-white shadow">

                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

                    {{ $header }}

                </div>

            </header>

        @endisset


        <!-- =====================================
             PAGE CONTENT
        ====================================== -->

        <main>

            {{ $slot }}

        </main>


    </div>


    <!-- =========================================
         BOOTSTRAP JAVASCRIPT
    ========================================== -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


</body>

</html>