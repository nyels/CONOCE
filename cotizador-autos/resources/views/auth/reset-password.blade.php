<x-layouts.auth title="Restablecer Contraseña">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md animate-fade-in">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 mb-4 shadow-lg shadow-blue-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Nueva Contraseña</h1>
                <p class="text-gray-400">Ingresa tu nueva contraseña</p>
            </div>

            <!-- Card -->
            <div class="glass-card p-8">
                @if ($errors->any())
                    <div class="error-message">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Correo Electrónico
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                            required autocomplete="email" class="input-modern">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Nueva Contraseña
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="input-modern" placeholder="••••••••">
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                            Confirmar Contraseña
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            autocomplete="new-password" class="input-modern" placeholder="••••••••">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Restablecer Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.auth>
