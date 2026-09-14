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
                <h1 class="text-2xl font-bold text-slate-900">Editar Proposta</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $proposta->titulo }}</p>
            </div>
        </div>

        <form action="{{ route('propostas.update', $proposta) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            @include('propostas._form', [
                'clientes' => $clientes,
                'statuses' => $statuses,
                'proposta' => $proposta,
            ])

            <div class="flex gap-3 pt-2">
                <a href="{{ route('propostas.index') }}"
                   class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm shadow-blue-200">
                    Salvar Alterações
                </button>
            </div>
        </form>

        {{-- Salvar como modelo: fica FORA do form da proposta, porque é outro
             envio. Um form não pode estar dentro de outro. --}}
        <div class="border-t border-slate-100 mt-8 pt-6" x-data="{ aberto: false }">
            <button type="button" @click="aberto = ! aberto"
                    class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Salvar o texto desta proposta como modelo
            </button>

            <div x-show="aberto" x-cloak class="mt-3 bg-slate-50 border border-slate-200 rounded-xl p-4">
                <p class="text-xs text-slate-500 mb-3">
                    O modelo guarda <strong>só o texto</strong> — cliente, valores e itens não entram.
                    Depois ele fica disponível no seletor <strong>Inserir modelo</strong> de qualquer proposta.
                </p>
                <form method="POST" action="{{ route('modelos.store') }}" class="flex flex-wrap items-center gap-2">
                    @csrf
                    <input type="hidden" name="proposta_id" value="{{ $proposta->id }}">
                    <input type="text" name="titulo" required maxlength="255"
                           placeholder="Nome do modelo (ex.: Contrato de manutenção)"
                           class="flex-1 min-w-[220px] px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
                    <button type="submit"
                            class="text-xs font-semibold text-white bg-slate-800 hover:bg-slate-900 px-4 py-2 rounded-lg transition-colors whitespace-nowrap">
                        Salvar como modelo
                    </button>
                </form>
                <p class="text-xs text-slate-400 mt-2">
                    Salve a proposta antes, para o modelo pegar o texto já gravado.
                </p>
            </div>
        </div>
    </div>
</x-layouts.app>
