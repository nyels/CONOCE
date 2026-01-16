<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'CONOCE') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts & Styles -->
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead

    <style>
        /* Reset base para consistencia */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; width: 100%; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #475569; -webkit-font-smoothing: antialiased; }

        /* Loading screen */
        #app-loading {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }
        #app-loading.hidden { opacity: 0; pointer-events: none; }
        #app-loading .spinner { display: flex; flex-direction: column; align-items: center; gap: 16px; }
        #app-loading img { height: 48px; width: auto; animation: pulse 2s infinite; }
        #app-loading p { color: #64748b; font-size: 14px; font-weight: 500; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    </style>
</head>

<body>
    <!-- Loading Screen (se oculta cuando Vue monta) -->
    <div id="app-loading">
        <div class="spinner">
            <img src="/logo_conoce.png" alt="CONOCE">
            <p>Cargando...</p>
        </div>
    </div>

    @inertia
</body>

</html>
