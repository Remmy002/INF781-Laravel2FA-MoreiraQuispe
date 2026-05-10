<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('¿Perdiste tu dispositivo? Ingresa uno de tus códigos de recuperación de 10 caracteres para acceder a tu cuenta.') }}
    </div>

    <form method="POST" action="{{ route('two-factor.recovery.verify') }}">
        @csrf

        <div>
            <x-input-label for="recovery_code" :value="__('Código de Recuperación')" />
            <x-text-input id="recovery_code" class="block mt-1 w-full font-mono" type="text" name="recovery_code" required autofocus />
            <x-input-error :messages="$errors->get('recovery_code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verificar y Acceder') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>