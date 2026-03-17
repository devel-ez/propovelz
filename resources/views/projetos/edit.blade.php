<x-layouts.app>
    <div class="p-6 md:p-8 max-w-2xl">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('projetos.index') }}"
               class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Editar Projeto</h1>
                <p class="text-sm text-slate-500 mt-0.5">Altere as informações do projeto.</p>
            </div>
        </div>

        <form action="{{ route('projetos.update', $projeto) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            @include('projetos._form', ['projeto' => $projeto, 'clientes' => $clientes, 'propostas' => $propostas])

            <div class="flex gap-3 pt-2">
                <a href="{{ route('projetos.index') }}"
                   class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm shadow-blue-200">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
