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

    <div class="p-6 md:p-8 max-w-3xl" x-data="{ novo: {{ $errors->any() ? 'true' : 'false' }} }">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700 mb-6">
                <ul class="space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Modelos de proposta</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Textos que se repetem de cliente para cliente — cláusulas de contrato, escopos,
                    condições. Escreva aqui uma vez e insira em quantas propostas quiser.
                </p>
            </div>

            <button type="button" @click="novo = ! novo"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-blue-200 transition-all text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Novo modelo
            </button>
        </div>

        {{-- Formulário de novo modelo --}}
        <div x-show="novo" x-cloak class="bg-white rounded-2xl border border-blue-200 shadow-sm p-6 mb-8">
            <h2 class="text-sm font-semibold text-slate-800 mb-4">Novo modelo</h2>

            <form method="POST" action="{{ route('modelos.store') }}" class="space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label for="novo-titulo" class="text-sm font-semibold text-slate-700">Nome do modelo</label>
                    <input type="text" id="novo-titulo" name="titulo" required maxlength="255"
                           value="{{ old('titulo') }}"
                           placeholder="Ex.: Contrato de manutenção — Plano Nós cuidamos"
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
                    <p class="text-xs text-slate-400">É o nome que aparece no seletor "Inserir modelo".</p>
                </div>

                <x-editor id="novo-conteudo"
                          name="conteudo"
                          :value="old('conteudo')"
                          label="Texto do modelo"
                          hint="Escreva as cláusulas, o escopo, as condições. Use os títulos para separar as seções." />

                <div class="flex items-center justify-end gap-3 pt-1">
                    <button type="button" @click="novo = false"
                            class="px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm shadow-blue-200 transition-colors">
                        Criar modelo
                    </button>
                </div>
            </form>
        </div>

        {{-- Como usar --}}
        <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 mb-8">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs text-blue-900 leading-relaxed space-y-1">
                <p><strong>Inserir numa proposta:</strong> abra ou crie uma proposta e use o seletor <strong>Inserir modelo</strong>, logo abaixo do editor de texto.</p>
                <p><strong>Reaproveitar uma proposta inteira:</strong> na lista de propostas, use <strong>Duplicar</strong> — copia o texto e os itens.</p>
                <p><strong>Criar a partir de um texto pronto:</strong> abra a proposta e clique em <strong>Salvar como modelo</strong>.</p>
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
                        Clique em <strong>Novo modelo</strong> para escrever o primeiro.
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
                                <a href="{{ route('modelos.edit', $modelo) }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-blue-600 px-2.5 py-1.5 rounded-lg hover:bg-blue-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar
                                </a>
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

                        <p class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100">
                            {{ mb_strlen(trim(strip_tags($modelo->conteudo))) }} caracteres ·
                            atualizado em {{ $modelo->updated_at->format('d/m/Y \à\s H:i') }}
                        </p>
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
