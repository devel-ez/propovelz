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

    <div class="p-6 md:p-8 max-w-3xl">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700 mb-6">
                <ul class="space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Modelos de proposta</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Textos que se repetem de cliente para cliente — cláusulas de contrato, escopos,
                condições. Em vez de reescrever a cada proposta, você insere o modelo no editor.
            </p>
        </div>

        {{-- Como usar --}}
        <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 mb-8">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs text-blue-900 leading-relaxed space-y-1">
                <p><strong>Inserir numa proposta:</strong> abra ou crie uma proposta e use o seletor <strong>Inserir modelo</strong>, logo abaixo do editor de texto.</p>
                <p><strong>Criar um modelo novo:</strong> monte o texto numa proposta (o editor tem formatação), depois clique em <strong>Salvar como modelo</strong>, na página de edição dela.</p>
                <p><strong>Reaproveitar uma proposta inteira:</strong> na lista de propostas, use <strong>Duplicar</strong> — copia o texto e os itens.</p>
                <p class="pt-1 text-blue-800">Os trechos entre colchetes, como <strong>[NOME OU RAZÃO SOCIAL]</strong>, são para você localizar e substituir ao usar o modelo.</p>
            </div>
        </div>

        {{-- Lista --}}
        @if($modelos->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-slate-700">Nenhum modelo ainda</p>
                    <p class="text-sm text-slate-400 mt-1 max-w-md">
                        Escreva um texto numa proposta e clique em <strong>Salvar como modelo</strong> para reaproveitá-lo.
                    </p>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($modelos as $modelo)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                            <h2 class="text-sm font-semibold text-slate-800">{{ $modelo->titulo }}</h2>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('modelos.destroy', $modelo) }}"
                                      onsubmit="return confirm('Remover o modelo &quot;{{ addslashes($modelo->titulo) }}&quot;?\n\nAs propostas que já usaram o texto não são afetadas.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-slate-400 hover:text-red-600 hover:bg-red-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </div>

                        <details class="group">
                            <summary class="text-xs font-semibold text-blue-600 hover:underline cursor-pointer list-none">
                                Ver o texto
                            </summary>
                            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-600 leading-relaxed space-y-1.5 max-h-72 overflow-y-auto">
                                {!! $modelo->conteudo !!}
                            </div>
                        </details>

                        <form method="POST" action="{{ route('modelos.update', $modelo) }}" class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="titulo" value="{{ $modelo->titulo }}" required maxlength="255"
                                   class="flex-1 px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
                            <button type="submit"
                                    class="text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 px-3 py-2 rounded-lg transition-colors whitespace-nowrap">
                                Renomear
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <p class="text-xs text-slate-400 mt-6">
                {{ $modelos->count() }} {{ $modelos->count() === 1 ? 'modelo' : 'modelos' }} ·
                remover um modelo não altera nenhuma proposta já criada
            </p>
        @endif
    </div>
</x-layouts.app>
