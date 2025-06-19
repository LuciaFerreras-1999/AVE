<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" style="color: var(--color-accent-hover);" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="Nombre" style="color: var(--color-text);" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="Correo electrónico" style="color: var(--color-text);" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="Contraseña" style="color: var(--color-text);" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirmar Contraseña" style="color: var(--color-text);" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4" style="color: var(--color-text);">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2 text-sm">
                                {!! __('Estoy de acuerdo con los :terms_of_service y :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md">'.__('Términos de Servicio').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md">'.__('Política de Privacidad').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('login') }}"
                   class="underline text-sm"
                   style="color: var(--color-text);"
                   onmouseover="this.style.color='var(--color-accent)'"
                   onmouseout="this.style.color='var(--color-text)'">
                    ¿Ya estás registrado?
                </a>

                <x-button style="background-color: var(--color-accent); color: white;"
                    onmouseover="this.style.backgroundColor='var(--color-accent-hover)'"
                    onmouseout="this.style.backgroundColor='var(--color-accent)'">
                    Registrarse
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
