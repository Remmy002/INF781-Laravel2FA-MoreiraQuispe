<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> 
            Autenticación en Dos Factores (2FA) 
        </h2> 
    </x-slot> 
  
    <div class="py-12"> 
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8"> 
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"> 
  
                @if (session('status')) 
                    <div class="mb-4 p-4 rounded shadow-md flex items-center" 
                        style="background-color: #d1fae5; border: 1px solid #10b981; color: #065f46;"> 
                        
                        <svg class="w-6 h-6 mr-3" style="fill: #10b981;" viewBox="0 0 20 20">
                            <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm-1 15l-5-5 1.41-1.41L9 12.17l7.59-7.59L18 6l-9 9z"/>
                        </svg>
                        
                        <p class="font-semibold text-lg">{{ session('status') }}</p>
                    </div> 
                @endif
  
                @if ($enabled) 
                    <div class="mb-6 p-4 bg-green-50 border border-green-300 rounded"> 
                        <p class="text-green-700 font-semibold"> 
                            2FA está ACTIVADO en tu cuenta. 
                        </p> 
                    </div> 
                    @if (session('recovery_codes'))
                        <div class="mb-6 p-4 rounded shadow-md" style="background-color: #fef2f2; border: 1px solid #f87171;">
                            <p class="font-bold text-red-700 mb-2">⚠️ ¡GUARDA ESTOS CÓDIGOS DE RESPALDO!</p>
                            <p class="text-sm text-red-600 mb-4">Si pierdes tu dispositivo, estos son la única forma de acceder. No volverán a mostrarse.</p>
                            
                            <div class="grid grid-cols-2 gap-2 font-mono text-sm bg-white p-3 rounded border border-red-200">
                                @foreach (session('recovery_codes') as $code)
                                    <span class="text-gray-800">{{ $code }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('two-factor.disable') }}"> 
                        @csrf 
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                            onclick="return confirm('¿Deseas desactivar el 2FA?')"> 
                            Desactivar 2FA 
                        </button> 
                    </form> 
                @else 
                    <p class="mb-4 text-gray-700"> 
                        Escanea este código QR con tu app autenticadora 
                        (Google Authenticator, Authy, etc.) y luego ingresa 
                        el código de 6 dígitos para activar 2FA. 
                    </p> 
  
                    <div class="flex justify-center mb-4"> 
                        {!! $qrCodeSvg !!} 
                    </div> 
  
                    <div class="mb-6"> 
                        <p class="text-sm text-gray-500"> 
                            O ingresa manualmente esta clave secreta: 
                        </p> 
                        <code class="block bg-gray-100 p-2 rounded text-sm mt-1 tracking-widest"> 
                            {{ $secret }} 
                        </code> 
                    </div> 
  
                    <form method="POST" action="{{ route('two-factor.enable') }}"> 
                        @csrf 
                        <div class="mb-4"> 
                            <label class="block text-sm font-medium text-gray-700 mb-1"> 
                                Código OTP de verificación 
                            </label> 
                            <input type="text" name="code" maxlength="6" 
                                autocomplete="one-time-code" 
                                class="border border-gray-300 rounded px-3 py-2 w-40 text-center tracking-widest text-lg 
                                 @error('code') border-red-500 @enderror" 
                                placeholder="000000"> 
                            @error('code') 
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                            @enderror 
                        </div> 
                        <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"> 
                            Activar 2FA 
                        </button> 
                    </form> 
                @endif 
            </div> 
        </div> 
    </div> 
</x-app-layout>