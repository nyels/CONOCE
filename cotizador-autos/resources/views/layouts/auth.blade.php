<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Cotizador de Autos' }} - Sistema de Cotización</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary: #3B82F6;
            --primary-dark: #2563EB;
            --secondary: #6366F1;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --dark: #1F2937;
            --light: #F9FAFB;
        }

        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 50%, #1e1b4b 100%);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
        }

        .glass-card-light {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .input-modern {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 14px 16px;
            color: white;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .input-modern:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            background: rgba(255, 255, 255, 0.1);
        }

        .input-modern::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-light {
            background: #F9FAFB;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            padding: 14px 16px;
            color: #1F2937;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .input-light:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            background: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }

        .floating-shapes .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(99, 102, 241, 0.3));
            animation: float 20s infinite;
        }

        .floating-shapes .shape:nth-child(1) {
            width: 400px;
            height: 400px;
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .floating-shapes .shape:nth-child(2) {
            width: 300px;
            height: 300px;
            bottom: -50px;
            left: -50px;
            animation-delay: -5s;
        }

        .floating-shapes .shape:nth-child(3) {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 30%;
            animation-delay: -10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-30px) rotate(180deg);
            }
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            color: #FCA5A5;
            font-size: 0.875rem;
            margin-bottom: 16px;
        }

        .success-message {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            color: #6EE7B7;
            font-size: 0.875rem;
            margin-bottom: 16px;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="antialiased">
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <main class="relative z-10">
        {{ $slot }}
    </main>
</body>

</html>
