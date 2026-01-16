<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - CONOCE Seguros</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* Reset base - consistente con app.blade.php */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f8fafc;
            -webkit-font-smoothing: antialiased;
        }

        /* Panel Izquierdo - Branding */
        .brand-panel {
            flex: 1;
            background: linear-gradient(135deg, #7B2D3B 0%, #5A1F2C 40%, #3D1520 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(199, 161, 114, 0.15) 0%, transparent 60%);
            pointer-events: none;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(199, 161, 114, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .brand-logo {
            position: relative;
            z-index: 1;
        }

        .brand-logo img {
            height: 60px;
        }

        .brand-content {
            position: relative;
            z-index: 1;
        }

        .brand-tagline {
            font-size: 42px;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 24px;
        }

        .brand-tagline span {
            background: linear-gradient(90deg, #C7A172, #E8D5B7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-description {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
            max-width: 400px;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 48px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 15px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(199, 161, 114, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon svg {
            width: 20px;
            height: 20px;
            color: #C7A172;
        }

        .brand-footer {
            position: relative;
            z-index: 1;
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
        }

        /* Panel Derecho - Login */
        .login-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
            background: white;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 15px;
        }

        .error-alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 24px;
            color: #dc2626;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .error-alert svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #9ca3af;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 50px;
            font-size: 15px;
            font-family: inherit;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #f9fafb;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-input:focus {
            outline: none;
            border-color: #7B2D3B;
            background: white;
            box-shadow: 0 0 0 4px rgba(123, 45, 59, 0.1);
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .checkbox-wrapper input {
            width: 18px;
            height: 18px;
            accent-color: #7B2D3B;
            cursor: pointer;
        }

        .checkbox-wrapper span {
            font-size: 14px;
            color: #64748b;
        }

        .forgot-link {
            font-size: 14px;
            color: #7B2D3B;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #5A1F2C;
        }

        .btn-login {
            width: 100%;
            padding: 16px 24px;
            font-size: 16px;
            font-weight: 600;
            font-family: inherit;
            color: white;
            background: linear-gradient(135deg, #7B2D3B 0%, #5A1F2C 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(123, 45, 59, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(123, 45, 59, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login svg {
            width: 20px;
            height: 20px;
        }

        .login-footer {
            margin-top: 32px;
            text-align: center;
        }

        .login-footer p {
            color: #94a3b8;
            font-size: 13px;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 32px 0;
            color: #94a3b8;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* Mobile */
        @media (max-width: 1024px) {
            body {
                flex-direction: column;
            }

            .brand-panel {
                padding: 32px;
                min-height: auto;
            }

            .brand-tagline {
                font-size: 28px;
            }

            .brand-features {
                display: none;
            }

            .brand-content {
                margin: 24px 0;
            }

            .login-panel {
                padding: 32px 24px;
            }
        }

        @media (max-width: 480px) {
            .brand-panel {
                padding: 24px;
            }

            .brand-tagline {
                font-size: 22px;
            }

            .brand-description {
                font-size: 14px;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <!-- Panel de Branding -->
    <div class="brand-panel">
        <div class="brand-logo">
            <img src="/logo_conoce.png" alt="CONOCE Seguros y Fianzas">
        </div>

        <div class="brand-content">
            <h2 class="brand-tagline">
                Sistema de<br>
                <span>Cotización Inteligente</span>
            </h2>
            <p class="brand-description">
                La plataforma empresarial para gestionar cotizaciones de seguros automotrices de manera eficiente y
                profesional.
            </p>

            <div class="brand-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span>Cotizaciones en segundos</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span>Múltiples aseguradoras integradas</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span>Reportes y analíticas en tiempo real</span>
                </div>
            </div>
        </div>

        <div class="brand-footer">
            © {{ date('Y') }} CONOCE Seguros y Fianzas. Todos los derechos reservados.
        </div>
    </div>

    <!-- Panel de Login -->
    <div class="login-panel">
        <div class="login-container">
            <div class="login-header">
                <h1>Bienvenido de nuevo</h1>
                <p>Ingresa tus credenciales para acceder al sistema</p>
            </div>

            @if ($errors->any())
                <div class="error-alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Correo electrónico</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <input type="email" id="email" name="email" class="form-input"
                            placeholder="correo@ejemplo.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="remember">
                        <span>Mantener sesión</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    Iniciar Sesión
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <div class="login-footer">
                <p>¿Necesitas ayuda? Contacta a soporte técnico</p>
            </div>
        </div>
    </div>
</body>

</html>
