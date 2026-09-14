<x-layouts.app>
    {{-- Flash messages --}}
    @if(session('success'))
        <div id="flash-success"
             class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium animate-fade-in">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="p-6 md:p-8 max-w-full">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Projetos</h1>
                <p class="text-sm text-slate-500 mt-0.5">Gerencie os projetos e acompanhe as tarefas.</p>
            </div>
            <a href="{{ route('projetos.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-blue-200 transition-all text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Projeto
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('projetos.index') }}" class="flex flex-wrap items-center gap-3 mb-6">
            {{-- Search --}}
            <div class="relative flex-1 min-w-[180px]">
                <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Pesquisar por nome ou cliente..."
                       class="w-full pl-9 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-1.5 text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-4 py-2.5 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-6.414 6.414A1 1 0 0014 13.828V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.172a1 1 0 00-.293-.707L1.293 6.707A1 1 0 011 6V4z"/>
                </svg>
                Filtrar
            </button>
            @if(request('search'))
                <a href="{{ route('projetos.index') }}" class="text-sm text-blue-600 hover:underline">Limpar filtros</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            @if($projetos->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-slate-700">Nenhum projeto encontrado</p>
                    <p class="text-sm text-slate-400 mt-1">Crie o seu primeiro projeto clicando em "Novo Projeto".</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/60">
                                <th class="text-left font-semibold text-slate-500 px-6 py-3.5">Projeto</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Cliente</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Status</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Dominio</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Criado em</th>
                                <th class="px-4 py-3.5 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($projetos as $projeto)
                                <tr class="hover:bg-slate-50/40 transition-colors group {{ $projeto->ativo ? '' : 'bg-slate-50/70' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-semibold {{ $projeto->ativo ? 'text-slate-800' : 'text-slate-400' }}">
                                                {{ $projeto->nome }}
                                            </span>
                                            @unless($projeto->ativo)
                                                <span class="text-[10px] font-bold bg-slate-200 text-slate-500 px-2 py-0.5 rounded-full uppercase tracking-wide"
                                                      title="Não conta vencimento de hospedagem nem domínio">
                                                    Inativo
                                                </span>
                                            @endunless
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">
                                        {{ $projeto->cliente?->nome ?? '—' }}
                                        @if($projeto->cliente && ! $projeto->cliente->ativo)
                                            <span class="ml-1.5 text-[10px] font-bold bg-slate-200 text-slate-500 px-1.5 py-0.5 rounded-full uppercase tracking-wide">
                                                Cliente inativo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">
                                        {{ $projeto->status }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-500">
                                        {{ $projeto->dominio ?? '—' }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-400 text-xs">
                                        {{ $projeto->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            {{-- Gerenciar Projeto --}}
                                            <a href="{{ route('projetos.show', $projeto) }}"
                                               class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-indigo-600 px-2.5 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors"
                                               title="Gerenciar Projeto e Tarefas">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Gerenciar
                                            </a>
                                            {{-- Editar --}}
                                            <a href="{{ route('projetos.edit', $projeto) }}"
                                               class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-blue-600 px-2.5 py-1.5 rounded-lg hover:bg-blue-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </a>
                                            {{-- Excluir --}}
                                            <button type="button"
                                                    onclick="openDeleteModal({{ $projeto->id }}, '{{ addslashes($projeto->nome) }}')"
                                                    class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-red-600 px-2.5 py-1.5 rounded-lg hover:bg-red-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($projetos->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $projetos->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- Count below table --}}
        <p class="text-xs text-slate-400 mt-3">
            Total: {{ $projetos->total() }} projeto{{ $projetos->total() !== 1 ? 's' : '' }}
        </p>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl p-6 mx-4 w-full max-w-sm animate-scale-in">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Excluir projeto</h3>
                    <p class="text-sm text-slate-500">Esta ação não pode ser desfeita.</p>
                </div>
            </div>
            <p class="text-sm text-slate-600 mb-5">
                Tem certeza que deseja excluir o projeto <strong id="delete-modal-title" class="text-slate-900"></strong>?
            </p>
            <div class="flex gap-2">
                <button onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                <form id="delete-form" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">
                        Sim, excluir
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id, title) {
            document.getElementById('delete-modal-title').textContent = title;
            document.getElementById('delete-form').action = '{{ url('projetos') }}/' + id;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-modal').classList.add('flex');
        }
        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.getElementById('delete-modal').classList.remove('flex');
        }

        const flash = document.getElementById('flash-success');
        if (flash) setTimeout(() => flash.remove(), 4000);
    </script>
</x-layouts.app>
