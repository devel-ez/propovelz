<x-layouts.app>
    {{-- SortableJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    {{-- Flash messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium animate-fade-in">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col h-full bg-slate-50/50" x-data="kanbanBoard()">
        {{-- Header Area --}}
        <div class="p-6 md:px-8 md:pt-8 md:pb-6 border-b border-slate-200 bg-white sticky top-0 z-20">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 max-w-[1600px] mx-auto">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-1">
                        <a href="{{ route('projetos.index') }}" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors" title="Voltar a Projetos">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </a>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-md
                                @if($projeto->status == 'Concluido') bg-emerald-100 text-emerald-700
                                @elseif($projeto->status == 'Cancelado') bg-red-100 text-red-700
                                @elseif($projeto->status == 'Pausado') bg-amber-100 text-amber-700
                                @else bg-blue-100 text-blue-700 @endif
                            ">
                                {{ $projeto->status }}
                            </span>
                            <span class="text-sm font-medium text-slate-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ $projeto->cliente?->nome ?? 'Sem Cliente' }}
                            </span>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 truncate" title="{{ $projeto->nome }}">{{ $projeto->nome }}</h1>
                    @if($projeto->descricao)
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2 max-w-3xl">{{ $projeto->descricao }}</p>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <button type="button" @click="editProjectModal = true"
                            class="inline-flex items-center gap-1.5 text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editar Projeto
                    </button>
                    <button type="button" @click="openTaskModal()"
                            class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm shadow-blue-200 text-sm focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Nova Tarefa
                    </button>
                </div>
            </div>
        </div>

        {{-- Kanban Board Area --}}
        <div class="flex-1 overflow-auto p-6 md:p-8">
            <div class="max-w-[1600px] mx-auto h-full min-h-[500px]">
                <div class="flex items-start gap-6 overflow-x-auto pb-4 h-full snap-x">
                    
                    @php
                        $columns = [
                            ['id' => 'Backlog', 'title' => 'Backlog', 'color' => 'bg-slate-200 text-slate-700 border-slate-300'],
                            ['id' => 'Doing', 'title' => 'Doing', 'color' => 'bg-blue-100 text-blue-700 border-blue-200'],
                            ['id' => 'Review', 'title' => 'Review', 'color' => 'bg-amber-100 text-amber-700 border-amber-200'],
                            ['id' => 'Done', 'title' => 'Done', 'color' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
                        ];
                    @endphp

                    @foreach($columns as $col)
                        <div class="flex flex-col flex-shrink-0 w-80 max-w-full bg-slate-200 rounded-2xl border-2 border-slate-300 max-h-full snap-center shadow shadow-slate-300/50">
                            {{-- Column Header --}}
                            <div class="p-3 border-b-2 border-slate-300 flex items-center justify-between sticky top-0 bg-slate-200 backdrop-blur-sm rounded-t-2xl z-10">
                                <h3 class="font-bold text-sm flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ explode(' ', $col['color'])[0] }}"></span>
                                    <span class="text-slate-800">{{ $col['title'] }}</span>
                                </h3>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $col['color'] }} bg-opacity-80 task-count" data-col="{{ $col['id'] }}">
                                    {{ $kanban[$col['id']]->count() }}
                                </span>
                            </div>
                            
                            {{-- Tasks Container --}}
                            <div class="p-3 overflow-visible pb-12 flex-1 min-h-[150px] space-y-3 sortable-list" data-status="{{ $col['id'] }}" id="list-{{ strtolower($col['id']) }}">
                                @if($kanban[$col['id']]->isEmpty())
                                    <div class="text-xs text-center text-slate-400 py-4 italic border-2 border-dashed border-slate-200 rounded-xl empty-state">
                                        Arraste ou crie tarefas aqui
                                    </div>
                                @endif
                                @foreach($kanban[$col['id']] as $tarefa)
                                    <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm cursor-grab hover:shadow-md transition-shadow group relative" data-id="{{ $tarefa->id }}">
                                        <div class="flex justify-between items-start gap-2 mb-2">
                                            <h4 class="font-semibold text-slate-800 text-sm leading-snug pr-6">{{ $tarefa->titulo }}</h4>
                                            
                                            {{-- Dropdown Toggle --}}
                                            <button @click.stop="activeTask = (activeTask === {{ $tarefa->id }} ? null : {{ $tarefa->id }})" class="absolute top-3 right-3 p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16"><path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
                                            </button>
                                            
                                            {{-- Dropdown Menu --}}
                                            <div x-show="activeTask === {{ $tarefa->id }}" @click.away="activeTask = null" style="display: none;"
                                                 class="absolute top-9 right-3 w-32 bg-white rounded-xl shadow-lg border border-slate-100 z-30 py-1 overflow-hidden">
                                                <button @click="openTaskModal('{{ $tarefa->id }}', '{{ addslashes($tarefa->titulo) }}', '{{ addslashes($tarefa->descricao) }}', '{{ $tarefa->status }}')" class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">Editar</button>
                                                <button @click="confirmDelete('{{ $tarefa->id }}')" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 font-medium border-t border-slate-100">Excluir</button>
                                            </div>
                                        </div>
                                        @if($tarefa->descricao)
                                            <p class="text-xs text-slate-500 line-clamp-2">{{ $tarefa->descricao }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="w-4 flex-shrink-0"></div>
                </div>
            </div>
        </div>

        {{-- Form Deletar Tarefa --}}
        <form x-ref="deleteForm" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        {{-- Edit Project Modal --}}
        <div x-show="editProjectModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="editProjectModal = false"
                 x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col"
                 x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between p-5 md:p-6 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Editar Detalhes do Projeto</h3>
                    </div>
                    <button type="button" @click="editProjectModal = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="overflow-y-auto flex-1 p-5 md:p-6">
                    <form id="edit-form" action="{{ route('projetos.update', $projeto) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_redirect_to_show" value="1">
                        @include('projetos._form', ['projeto' => $projeto, 'clientes' => $clientes, 'propostas' => $propostas])
                    </form>
                </div>

                <div class="p-5 md:p-6 border-t border-slate-100 bg-slate-50 rounded-b-2xl flex items-center justify-end gap-3">
                    <button type="button" @click="editProjectModal = false" class="px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors">Cancelar</button>
                    <button type="submit" form="edit-form" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-200 rounded-xl transition-colors">Salvar Alterações</button>
                </div>
            </div>
        </div>
        
        {{-- Task Modal (Create & Edit) --}}
        <div x-show="taskModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="taskModal = false"
                 x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg flex flex-col"
                 x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between p-5 md:p-6 border-b border-slate-100">
                    <h3 class="font-bold text-lg text-slate-900" x-text="taskFormData.id ? 'Editar Tarefa' : 'Nova Tarefa'"></h3>
                    <button type="button" @click="taskModal = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form :action="taskFormData.id ? `{{ url('projetos/'.$projeto->id.'/tarefas') }}/${taskFormData.id}` : `{{ route('projetos.tarefas.store', $projeto) }}`" method="POST" class="flex flex-col flex-1 min-h-0">
                    <div class="overflow-y-auto flex-1 p-5 md:p-6 space-y-5">
                        @csrf
                        <template x-if="taskFormData.id">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Título *</label>
                            <input type="text" name="titulo" x-model="taskFormData.titulo" required
                                   class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status *</label>
                            <select name="status" x-model="taskFormData.status" required
                                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm appearance-none">
                                <option value="Backlog">Backlog</option>
                                <option value="Doing">Doing</option>
                                <option value="Review">Review</option>
                                <option value="Done">Done</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Descrição <span class="text-slate-400 font-normal">(Opcional)</span></label>
                            <textarea name="descricao" x-model="taskFormData.descricao" rows="4"
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm resize-y"></textarea>
                        </div>
                    </div>

                    <div class="p-5 md:p-6 border-t border-slate-100 bg-slate-50 rounded-b-2xl flex items-center justify-end gap-3">
                        <button type="button" @click="taskModal = false" class="px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-200 rounded-xl transition-colors" x-text="taskFormData.id ? 'Salvar Tarefa' : 'Criar Tarefa'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function kanbanBoard() {
            return {
                editProjectModal: false,
                taskModal: false,
                activeTask: null,
                taskFormData: {
                    id: null,
                    titulo: '',
                    descricao: '',
                    status: 'Backlog'
                },
                init() {
                    const self = this;
                    // Initialize SortableJS on all columns
                    const columns = document.querySelectorAll('.sortable-list');
                    columns.forEach(col => {
                        new Sortable(col, {
                            group: 'kanban',
                            animation: 150,
                            easing: "cubic-bezier(1, 0, 0, 1)",
                            ghostClass: 'opacity-50',
                            onEnd: function (evt) {
                                const item = evt.item;
                                const to = evt.to;
                                
                                const newStatus = to.getAttribute('data-status');
                                const taskId = item.getAttribute('data-id');
                                
                                // Make AJAX request to update ordering
                                self.saveOrder(to, newStatus);
                                
                                // Hide/show empty state placeholder & update counts
                                self.toggleEmptyStates();
                                self.updateCounts();
                            }
                        });
                    });
                },
                openTaskModal(id = null, titulo = '', descricao = '', status = 'Backlog') {
                    this.taskFormData = { id, titulo, descricao, status };
                    this.activeTask = null; // close dropdown
                    this.taskModal = true;
                },
                confirmDelete(id) {
                    this.activeTask = null;
                    if(confirm("Tem certeza que deseja excluir esta tarefa?")) {
                        this.$refs.deleteForm.action = `{{ url('projetos/'.$projeto->id.'/tarefas') }}/${id}`;
                        this.$refs.deleteForm.submit();
                    }
                },
                saveOrder(columnEl, status) {
                    const itemIds = Array.from(columnEl.children)
                                         .filter(el => el.hasAttribute('data-id'))
                                         .map(el => el.getAttribute('data-id'));
                                         
                    fetch(`{{ route('projetos.tarefas.reorder', $projeto) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: status,
                            items: itemIds
                        })
                    }).then(res => {
                        if(!res.ok) console.error("Falha ao salvar a nova ordem");
                    });
                },
                toggleEmptyStates() {
                    document.querySelectorAll('.sortable-list').forEach(col => {
                        const items = Array.from(col.children).filter(el => el.hasAttribute('data-id'));
                        let emptyMsg = col.querySelector('.empty-state');
                        if (items.length === 0) {
                            if (!emptyMsg) {
                                emptyMsg = document.createElement('div');
                                emptyMsg.className = 'text-xs text-center text-slate-400 py-4 italic border-2 border-dashed border-slate-200 rounded-xl empty-state';
                                emptyMsg.innerText = 'Arraste ou crie tarefas aqui';
                                col.appendChild(emptyMsg);
                            } else {
                                emptyMsg.style.display = 'block';
                            }
                        } else if (emptyMsg) {
                            emptyMsg.style.display = 'none';
                        }
                    });
                },
                updateCounts() {
                    document.querySelectorAll('.sortable-list').forEach(col => {
                        const status = col.getAttribute('data-status');
                        const count = Array.from(col.children).filter(el => el.hasAttribute('data-id')).length;
                        const badge = document.querySelector(`.task-count[data-col="${status}"]`);
                        if(badge) {
                            badge.innerText = count;
                        }
                    });
                }
            }
        }
    </script>
    
    <style>
        .animate-scale-in { animation: scaleIn 0.2s ease-out forwards; }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .snap-x::-webkit-scrollbar { height: 8px; }
        .snap-x::-webkit-scrollbar-track { background: transparent; }
        .snap-x::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; border: 3px solid transparent; background-clip: content-box; }
        .sortable-drag { opacity: 1 !important; cursor: grabbing !important; }
        .sortable-list { min-height: 150px; }
    </style>
</x-layouts.app>
