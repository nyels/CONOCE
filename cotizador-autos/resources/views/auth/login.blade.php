<x-layouts.auth title="Iniciar Sesión">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md animate-fade-in">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 mb-4 shadow-lg shadow-blue-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Cotizador de Autos</h1>
                <p class="text-gray-400">Sistema de Cotización de Seguros</p>
            </div>

            <!-- Card de Login -->
            <div class="glass-card p-8">
                <h2 class="text-xl font-semibold text-white mb-6 text-center">Iniciar Sesión</h2>

                <!-- Mensajes de error -->
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

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
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

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" class="input-modern pl-12" placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-400">Recordarme</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-blue-400 hover:text-blue-300 transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Iniciar Sesión
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} Cotizador de Autos. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</x-layouts.auth>
