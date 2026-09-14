<x-layouts.app>
    <div class="p-6 md:p-8 max-w-3xl">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700 mb-6">
                <ul class="space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-start gap-3 mb-8">
            <a href="{{ route('modelos.index') }}"
               class="mt-1 inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Editar modelo</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $modelo->titulo }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('modelos.update', $modelo) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="space-y-1.5">
                <label for="titulo" class="text-sm font-semibold text-slate-700">Nome do modelo</label>
                <input type="text" id="titulo" name="titulo" required maxlength="255"
                       value="{{ old('titulo', $modelo->titulo) }}"
                       class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
                <p class="text-xs text-slate-400">É o nome que aparece no seletor "Inserir modelo".</p>
            </div>

            <x-editor id="modelo-conteudo"
                      name="conteudo"
                      :value="$modelo->conteudo"
                      label="Texto do modelo"
                      hint="Use os títulos para separar as seções e as listas para as cláusulas." />

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('modelos.index') }}"
                   class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm shadow-blue-200">
                    Salvar Alterações
                </button>
            </div>
        </form>

        <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 mt-8">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 5a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z"/>
            </svg>
            <p class="text-xs text-amber-900 leading-relaxed">
                Alterar o modelo <strong>não muda</strong> as propostas que já usaram este texto.
                O conteúdo copiado para uma proposta vira conteúdo dela e fica independente daqui.
            </p>
        </div>
    </div>
</x-layouts.app>
