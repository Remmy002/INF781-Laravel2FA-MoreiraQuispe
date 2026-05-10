<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> 
            {{ __('Dashboard') }} 
        </h2> 
    </x-slot> 
  
    <div class="py-12"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"> 
                <div class="p-6 text-gray-900"> 
                    {{ __("You're logged in!") }} 
                </div> 
                <div class="p-6 border-t border-gray-200"> 
                    @if(auth()->user()->two_factor_enabled) 
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold mr-3"
                              style="background-color: #d1fae5; color: #065f46; border: 1px solid #10b981;"> 
                            2FA Activado 
                        </span> 
                    @else 
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold mr-3"
                              style="background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b;"> 
                            2FA Desactivado 
                        </span> 
                    @endif 

                    <a href="{{ route('two-factor.setup') }}" 
                       class="text-blue-600 hover:underline text-sm font-semibold"> 
                        Configurar Autenticación en Dos Factores 
                    </a> 
                </div> 
            </div> 
        </div> 
    </div> 
</x-app-layout>