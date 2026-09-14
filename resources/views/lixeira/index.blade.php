<x-layouts.app>
    @if(session('success'))
        <div id="flash-success"
             class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium animate-fade-in">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="p-6 md:p-8 max-w-4xl">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Lixeira</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Excluir não apaga de vez: o registro fica aqui até você decidir.
                    Recupere a qualquer momento, ou esvazie para apagar de verdade.
                </p>
            </div>

            @if($total > 0)
                <form method="POST" action="{{ route('lixeira.esvaziar') }}"
                      onsubmit="return confirm('Apagar TUDO da lixeira definitivamente?\n\nIsto não tem volta. Clientes levam junto seus projetos, propostas e acessos.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Esvaziar lixeira
                    </button>
                </form>
            @endif
        </div>

        @if($total === 0)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-slate-700">Lixeira vazia</p>
                    <p class="text-sm text-slate-400 mt-1 max-w-md">
                        Nada foi excluído. Quando você excluir um cliente ou uma proposta, ele aparece aqui
                        e pode ser recuperado.
                    </p>
                </div>
            </div>
        @else
            @foreach($excluidos as $chave => $grupo)
                @if($grupo['registros']->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                            <h2 class="text-sm font-semibold text-slate-700">
                                {{ $grupo['plural'] }}
                                <span class="ml-1.5 text-xs font-bold bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full">
                                    {{ $grupo['registros']->count() }}
                                </span>
                            </h2>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach($grupo['registros'] as $item)
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 px-6 py-4">
                                    <div class="flex-1 min-w-[160px]">
                                        <p class="text-sm font-medium text-slate-700">{{ $item['titulo'] }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            excluído em {{ $item['excluido_em']?->format('d/m/Y \à\s H:i') }}
                                            @if($item['detalhe'])
                                                · <span class="text-amber-600">{{ $item['detalhe'] }}</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('lixeira.restaurar', [$chave, $item['id']]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-2 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Recuperar
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('lixeira.destruir', [$chave, $item['id']]) }}"
                                              onsubmit="return confirm('Apagar &quot;{{ addslashes($item['titulo']) }}&quot; definitivamente?{{ $item['detalhe'] ? '\n\n' . $item['detalhe'] . '.' : '' }}\n\nIsto não tem volta.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Apagar de vez
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 5a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z"/>
                </svg>
                <div class="text-xs text-amber-800 leading-relaxed">
                    <p class="font-semibold mb-1">Antes de apagar de vez</p>
                    <p>
                        <strong>Cliente:</strong> leva junto os projetos, as propostas e os acessos dele. As faturas ficam,
                        mas perdem o vínculo com o cliente.
                    </p>
                    <p class="mt-1">
                        <strong>Proposta:</strong> leva junto os itens dela.
                    </p>
                    <p class="mt-2 text-amber-700">
                        Projetos, tarefas e faturas <strong>não passam pela lixeira</strong> — esses são apagados direto.
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
