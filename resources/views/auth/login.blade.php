<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" style="color: var(--color-accent-hover);" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm" style="color: var(--color-accent);">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
    @csrf

    <div>
        <x-label for="email" value="Correo electrónico" style="color: var(--color-text);" />
        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
    </div>

    <div class="mt-4">
        <x-label for="password" value="Contraseña" style="color: var(--color-text);" />
        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
    </div>

    <div class="block mt-4">
        <label for="remember_me" class="flex items-center" style="color: var(--color-text);">
            <x-checkbox id="remember_me" name="remember" />
            <span class="ms-2 text-sm">Recuérdame</span>
        </label>
    </div>

    <div class="flex items-center justify-between mt-4">
        @if (Route::has('password.request'))
            <a class="underline text-sm" href="{{ route('password.request') }}"
               style="color: var(--color-text);"
               onmouseover="this.style.color='var(--color-accent)'"
               onmouseout="this.style.color='var(--color-text)'">
                ¿Olvidaste tu contraseña?
            </a>
        @endif

        <div class="flex gap-4">
            <x-button style="background-color: var(--color-accent); color: white;"
                onmouseover="this.style.backgroundColor='var(--color-accent-hover)'"
                onmouseout="this.style.backgroundColor='var(--color-accent)'">
                Iniciar sesión
            </x-button>

            <a href="{{ route('register') }}"
               class="inline-flex items-center justify-center px-4 py-2 border border-var(--color-accent) rounded text-var(--color-accent) hover:bg-var(--color-accent) hover:text-white transition-colors duration-300"
               style="border-color: var(--color-accent); color: var(--color-accent); text-decoration: none;">
                Registrarse
            </a>
        </div>
    </div>
</form>

    </x-authentication-card>
</x-guest-layout>
