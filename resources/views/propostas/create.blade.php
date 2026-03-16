<x-layouts.app>
    <div class="p-6 md:p-8 max-w-2xl">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('propostas.index') }}"
               class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Nova Proposta</h1>
                <p class="text-sm text-slate-500 mt-0.5">Preencha os dados para criar a proposta.</p>
            </div>
        </div>

        @if($clientes->isEmpty())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                </svg>
                <div>
                    <p class="font-semibold text-amber-800">Nenhum cliente cadastrado</p>
                    <p class="text-sm text-amber-700 mt-0.5">Cadastre um cliente primeiro antes de criar uma proposta.</p>
                </div>
            </div>
        @else
            <form action="{{ route('propostas.store') }}" method="POST" class="space-y-5">
                @csrf

                @include('propostas._form', ['clientes' => $clientes, 'statuses' => $statuses])

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('propostas.index') }}"
                       class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm shadow-blue-200">
                        Criar Proposta
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-layouts.app>
