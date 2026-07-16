@vite(["resources/css/app.css"])
<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex items-center justify-center gap-2 mb-2">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <span class="text-xl font-bold text-slate-800 tracking-tight">Sistema contable</span>
        </div>
        <h2 class="text-center text-2xl font-bold tracking-tight text-slate-900">
            Finalizar Registro de Usuario
        </h2>
        <p class="mt-2 text-center text-sm text-slate-500">
            Establezca las credenciales de acceso para su cuenta de sistema.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 border border-slate-200 sm:rounded-lg sm:px-10 shadow-sm">

            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-semibold text-blue-800 uppercase tracking-wider">Identificación de Cuenta</p>
                        <p class="text-sm font-semibold text-slate-700 mt-0.5 break-all">{{ $email }}</p>
                    </div>
                </div>
            </div>

            <form class="space-y-6" action="{{ request()->fullUrl() }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="p-3 bg-red-50 border border-red-200 rounded text-xs text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-1">
                                <span>⚠️</span> {{ $error }}
                            </p>
                        @endforeach
                    </div>
                @endif

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                        Nueva Contraseña
                    </label>
                    <input id="password" name="password" type="password" required
                        class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none sm:text-sm"
                        placeholder="Mínimo 8 caracteres">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                        Confirmar Contraseña
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none sm:text-sm"
                        placeholder="Repita la contraseña">
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition duration-150 ease-in-out">
                        Activar Cuenta y Acceder
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 flex items-center justify-center gap-1.5 text-xs text-slate-400">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <span>Sesión protegida mediante encriptación SSL</span>
        </div>
    </div>
</div>
