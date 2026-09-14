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

    {{-- Link gerado --}}
    @if(session('link_gerado'))
        <div id="banner-link" class="mx-6 mt-4 bg-blue-50 border border-blue-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-blue-700 mb-1">🔗 Link público gerado com sucesso!</p>
                <input id="link-url" type="text" readonly
                       value="{{ session('link_gerado') }}"
                       class="w-full text-xs text-blue-900 bg-blue-100 border border-blue-200 rounded-lg px-3 py-1.5 truncate focus:outline-none cursor-pointer"
                       onclick="this.select()">
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <button onclick="navigator.clipboard.writeText(document.getElementById('link-url').value).then(()=>alert('Link copiado!'))"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 border border-blue-300 hover:border-blue-500 bg-white rounded-lg px-3 py-2 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Copiar
                </button>
                <a href="https://wa.me/?text={{ urlencode('Olá! Sua proposta está pronta. Acesse e assine online: ' . session('link_gerado')) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-[#25D366] hover:bg-[#1ebe5d] rounded-lg px-3 py-2 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Enviar pelo WhatsApp
                </a>
                <button onclick="document.getElementById('banner-link').remove()"
                        class="text-blue-400 hover:text-blue-600 p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif

    <div class="p-6 md:p-8 max-w-full">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Propostas</h1>
                <p class="text-sm text-slate-500 mt-0.5">Gerencie todas as suas propostas comerciais.</p>
            </div>
            <a href="{{ route('propostas.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-blue-200 transition-all text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nova Proposta
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('propostas.index') }}" class="flex flex-wrap items-center gap-3 mb-6">
            {{-- Search --}}
            <div class="relative flex-1 min-w-[180px]">
                <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Pesquisar por título ou cliente..."
                       class="w-full pl-9 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
            </div>

            {{-- Status filter --}}
            <select name="status"
                    class="text-sm bg-white border border-slate-200 px-3 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-slate-700 min-w-[150px]">
                <option value="">Todos os status</option>
                @foreach($statuses as $key => $s)
                    <option value="{{ $key }}" @selected($currentStatus === $key)>{{ $s['label'] }}</option>
                @endforeach
            </select>

            <button type="submit"
                    class="inline-flex items-center gap-1.5 text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-4 py-2.5 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-6.414 6.414A1 1 0 0014 13.828V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.172a1 1 0 00-.293-.707L1.293 6.707A1 1 0 011 6V4z"/>
                </svg>
                Filtrar
            </button>
            @if($currentStatus || $search)
                <a href="{{ route('propostas.index') }}" class="text-sm text-blue-600 hover:underline">Limpar filtros</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            @if($propostas->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-slate-700">Nenhuma proposta encontrada</p>
                    <p class="text-sm text-slate-400 mt-1">Crie sua primeira proposta clicando em "Nova Proposta".</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/60">
                                <th class="text-left font-semibold text-slate-500 px-6 py-3.5">Título</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Cliente</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Status</th>
                                <th class="text-right font-semibold text-slate-500 px-4 py-3.5">Valor</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Validade</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Criada em</th>
                                <th class="px-4 py-3.5 w-64"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($propostas as $proposta)
                                @php
                                    $statusInfo = $statuses[$proposta->status] ?? ['label' => $proposta->status, 'color' => 'gray'];
                                    $colorMap = [
                                        'gray'   => 'bg-slate-100 text-slate-600',
                                        'blue'   => 'bg-blue-100 text-blue-700',
                                        'purple' => 'bg-purple-100 text-purple-700',
                                        'green'  => 'bg-green-100 text-green-700',
                                        'red'    => 'bg-red-100 text-red-700',
                                        'orange' => 'bg-orange-100 text-orange-700',
                                    ];
                                    $badgeClass = $colorMap[$statusInfo['color']] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <tr class="hover:bg-slate-50/40 transition-colors group">
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        {{ $proposta->titulo }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">
                                        {{ $proposta->cliente?->nome ?? '—' }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $badgeClass }}">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right font-medium text-slate-700">
                                        R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-500">
                                        @if($proposta->data_validade)
                                            {{ \Carbon\Carbon::parse($proposta->data_validade)->format('d/m/Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-slate-400 text-xs">
                                        {{ $proposta->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            {{-- Ver proposta --}}
                                            <a href="{{ route('propostas.show', $proposta) }}"
                                               class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-indigo-600 px-2.5 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors"
                                               title="Visualizar">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Ver
                                            </a>
                                            {{-- Gerar PDF --}}
                                            <a href="{{ route('propostas.pdf', $proposta) }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-rose-600 px-2.5 py-1.5 rounded-lg hover:bg-rose-50 transition-colors"
                                               title="Gerar PDF">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                PDF
                                            </a>
                                            {{-- Editar --}}
                                            <a href="{{ route('propostas.edit', $proposta) }}"
                                               class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-blue-600 px-2.5 py-1.5 rounded-lg hover:bg-blue-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </a>
                                            {{-- Gerar / copiar link --}}
                                            <form method="POST" action="{{ route('propostas.gerar-link', $proposta) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1.5 rounded-lg transition-colors
                                                               {{ $proposta->token_publico ? 'text-green-700 hover:text-green-800 hover:bg-green-50' : 'text-slate-600 hover:text-green-700 hover:bg-green-50' }}"
                                                        title="{{ $proposta->token_publico ? 'Link já gerado — clique para renovar / ver' : 'Gerar link de assinatura' }}">
                                                    @if($proposta->token_publico)
                                                        {{-- WhatsApp icon when link exists --}}
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                        Link
                                                    @else
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                        </svg>
                                                        Link
                                                    @endif
                                                </button>
                                            </form>
                                            {{-- Excluir --}}
                                            <button type="button"
                                                    onclick="openDeleteModal({{ $proposta->id }}, '{{ addslashes($proposta->titulo) }}')"
                                                    class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-red-600 px-2.5 py-1.5 rounded-lg hover:bg-red-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Excluir
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($propostas->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $propostas->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- Count below table --}}
        <p class="text-xs text-slate-400 mt-3">
            Total: {{ $propostas->total() }} proposta{{ $propostas->total() !== 1 ? 's' : '' }}
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
                    <h3 class="font-bold text-slate-900">Excluir proposta</h3>
                    <p class="text-sm text-slate-500">Esta ação não pode ser desfeita.</p>
                </div>
            </div>
            <p class="text-sm text-slate-600 mb-5">
                Tem certeza que deseja excluir a proposta <strong id="delete-modal-title" class="text-slate-900"></strong>?
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
            document.getElementById('delete-form').action = '{{ url('propostas') }}/' + id;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-modal').classList.add('flex');
        }
        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.getElementById('delete-modal').classList.remove('flex');
        }

        // Auto-dismiss flash on old browsers (no Alpine)
        const flash = document.getElementById('flash-success');
        if (flash) setTimeout(() => flash.remove(), 4000);
    </script>

    <style>
        @keyframes scale-in {
            from { transform: scale(0.95); opacity: 0; }
            to   { transform: scale(1);    opacity: 1; }
        }
        .animate-scale-in { animation: scale-in 0.15s ease-out; }
    </style>
</x-layouts.app>
