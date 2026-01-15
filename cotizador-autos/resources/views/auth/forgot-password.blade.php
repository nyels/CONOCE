<x-layouts.auth title="Recuperar Contraseña">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md animate-fade-in">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 mb-4 shadow-lg shadow-blue-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Recuperar Contraseña</h1>
                <p class="text-gray-400">Te enviaremos un enlace para restablecer tu contraseña</p>
            </div>

            <!-- Card -->
            <div class="glass-card p-8">
                <!-- Mensajes -->
                @if ($errors->any())
                    <div class="error-message">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('status'))
                    <div class="success-message">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus autocomplete="email" class="input-modern pl-12" placeholder="tu@correo.com">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        Enviar Enlace de Recuperación
                    </button>
                </form>

                <!-- Back to login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">
                        ← Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
