<x-layouts.app>
    <div class="p-6 md:p-8 max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Faturas</h1>
                <p class="text-sm text-slate-500 mt-0.5">Gerencie suas faturas e contratos de manutenção</p>
            </div>
            <a href="{{ route('faturas.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-brand-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nova Fatura
            </a>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Filters --}}
        <form method="GET" action="{{ route('faturas.index') }}" class="flex flex-wrap gap-3 mb-6">
            <select name="status" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                <option value="">Todos os status</option>
                <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="pago" {{ request('status') === 'pago' ? 'selected' : '' }}>Pago</option>
            </select>
            <select name="cliente_id" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                <option value="">Todos os clientes</option>
                @foreach ($clientes as $c)
                    <option value="{{ $c->id }}" {{ request('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                @endforeach
            </select>
            @if(request('status') || request('cliente_id'))
                <a href="{{ route('faturas.index') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 bg-white border border-slate-200 rounded-xl transition-colors">
                    Limpar filtros
                </a>
            @endif
        </form>

        {{-- Content --}}
        @if ($faturas->isEmpty())
            <div class="bg-white border border-slate-200 rounded-2xl p-16 text-center">
                <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-700 mb-1">Nenhuma fatura encontrada</h3>
                <p class="text-sm text-slate-400 mb-5">Crie sua primeira fatura ou contrato mensal.</p>
                <a href="{{ route('faturas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nova Fatura
                </a>
            </div>
        @else
            @php
                $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
                $grupos = $faturas->groupBy(fn($f) => $f->grupo_clone ?? 'single_' . $f->id);
            @endphp

            <div class="space-y-4">
                @foreach ($grupos as $grupoKey => $grupo)
                    @php $isGrupo = $grupo->count() > 1; $primeira = $grupo->first(); @endphp

                    {{-- ── CONTRATO MENSAL (collapsible) ── --}}
                    @if ($isGrupo)
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm"
                             x-data="{ open: false }">

                            {{-- Group header — always visible --}}
                            <div class="px-5 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                {{-- Left: badge + name (clickable to toggle) --}}
                                <div class="flex items-center gap-3 min-w-0 flex-1 cursor-pointer select-none" @click="open = !open">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700 flex-shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Contrato Mensal
                                    </span>
                                    <div class="min-w-0">
                                        <span class="text-sm font-semibold text-slate-700 truncate block">{{ $primeira->descricao_servico }}</span>
                                        @if ($primeira->cliente)
                                            <span class="text-xs text-slate-400">{{ $primeira->cliente->nome }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Right: stats + action buttons + chevron --}}
                                <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                                    <span class="text-xs text-slate-400">{{ $grupo->count() }} parcelas</span>
                                    @php $pagas = $grupo->where('pago', true)->count(); @endphp
                                    @if ($pagas > 0)
                                        <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">{{ $pagas }}/{{ $grupo->count() }} pagas</span>
                                    @endif

                                    {{-- Edit contract button --}}
                                    <a href="{{ route('faturas.grupo.edit', $grupoKey) }}"
                                       class="p-1.5 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors"
                                       title="Editar contrato"
                                       @click.stop>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    {{-- Delete contract button --}}
                                    <form method="POST" action="{{ route('faturas.grupo.destroy', $grupoKey) }}"
                                          onsubmit="return confirm('Excluir o contrato inteiro com todas as {{ $grupo->count() }} parcelas? Esta ação não pode ser desfeita.')"
                                          @click.stop>
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Excluir contrato inteiro">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>

                                    {{-- Chevron --}}
                                    <button type="button" @click="open = !open"
                                            class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Expandable rows --}}
                            <div x-show="open" style="display:none"
                                 x-transition:enter="transition-all duration-200 ease-out"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition-all duration-150 ease-in"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-left">
                                            <th class="px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide w-12">Pago</th>
                                            <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Mês</th>
                                            <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Vencimento</th>
                                            <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Data Pagto.</th>
                                            <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide text-right">Valor</th>
                                            <th class="px-4 py-3 w-20"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @foreach ($grupo as $fatura)
                                            @php
                                                $dataRaw = $fatura->mes_referencia;
                                                $dataCarbon = $dataRaw ? \Carbon\Carbon::parse($dataRaw) : null;
                                                $nomeMes = $dataCarbon ? ($meses[(int)$dataCarbon->format('n') - 1] . ' ' . $dataCarbon->format('Y')) : '—';
                                            @endphp
                                            <tr class="hover:bg-slate-50/50 transition-colors group" x-data="{
                                                pago: {{ $fatura->pago ? 'true' : 'false' }},
                                                dataPagamento: '{{ $fatura->data_pagamento ? \Carbon\Carbon::parse($fatura->data_pagamento)->format('Y-m-d') : now()->format('Y-m-d') }}',
                                                loading: false,
                                                toggle() {
                                                    this.loading = true;
                                                    fetch('{{ route('faturas.toggle-pago', $fatura) }}', {
                                                        method: 'PATCH',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Accept': 'application/json',
                                                        },
                                                        body: JSON.stringify({ data_pagamento: this.dataPagamento })
                                                    })
                                                    .then(r => r.json())
                                                    .then(d => { this.pago = d.pago; this.loading = false; })
                                                    .catch(() => this.loading = false);
                                                }
                                            }">
                                                <td class="px-5 py-3">
                                                    <button @click="toggle" :disabled="loading"
                                                            class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all focus:outline-none disabled:opacity-50"
                                                            :class="pago ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300 hover:border-emerald-400'">
                                                        <svg x-show="pago" style="display:none" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </button>
                                                </td>
                                                <td class="px-4 py-3 font-medium text-slate-700">{{ $nomeMes }}</td>
                                                <td class="px-4 py-3 text-slate-500">
                                                    {{ $fatura->vencimento ? \Carbon\Carbon::parse($fatura->vencimento)->format('d/m/Y') : '—' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div x-show="pago" style="display:none">
                                                        <input type="date" x-model="dataPagamento" @change="toggle"
                                                               class="text-xs border border-slate-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-1 focus:ring-brand-400 bg-white">
                                                    </div>
                                                    <span x-show="!pago" class="text-slate-400 text-xs">—</span>
                                                </td>
                                                <td class="px-4 py-3 text-right font-semibold text-slate-800">
                                                    {{ $fatura->valor ? 'R$ ' . number_format($fatura->valor, 2, ',', '.') : '—' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <a href="{{ route('faturas.pdf', $fatura) }}" target="_blank"
                                                           class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                           title="Baixar PDF">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                        </a>
                                                        <a href="{{ route('faturas.edit', $fatura) }}"
                                                           class="p-1.5 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors"
                                                           title="Editar parcela">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        </a>
                                                        <form method="POST" action="{{ route('faturas.destroy', $fatura) }}" onsubmit="return confirm('Excluir esta parcela?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                    class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                                    title="Excluir parcela">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    {{-- ── FATURA ÚNICA ── --}}
                    @else
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 text-left">
                                        <th class="px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide w-12">Pago</th>
                                        <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Serviço</th>
                                        <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Cliente</th>
                                        <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Vencimento</th>
                                        <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Data Pagto.</th>
                                        <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide text-right">Valor</th>
                                        <th class="px-4 py-3 w-28"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($grupo as $fatura)
                                        <tr class="hover:bg-slate-50/50 transition-colors group" x-data="{
                                            pago: {{ $fatura->pago ? 'true' : 'false' }},
                                            dataPagamento: '{{ $fatura->data_pagamento ? \Carbon\Carbon::parse($fatura->data_pagamento)->format('Y-m-d') : now()->format('Y-m-d') }}',
                                            loading: false,
                                            toggle() {
                                                this.loading = true;
                                                fetch('{{ route('faturas.toggle-pago', $fatura) }}', {
                                                    method: 'PATCH',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Accept': 'application/json',
                                                    },
                                                    body: JSON.stringify({ data_pagamento: this.dataPagamento })
                                                })
                                                .then(r => r.json())
                                                .then(d => { this.pago = d.pago; this.loading = false; })
                                                .catch(() => this.loading = false);
                                            }
                                        }">
                                            <td class="px-5 py-3">
                                                <button @click="toggle" :disabled="loading"
                                                        class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all focus:outline-none disabled:opacity-50"
                                                        :class="pago ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300 hover:border-emerald-400'">
                                                    <svg x-show="pago" style="display:none" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-medium text-slate-800">{{ Str::limit($fatura->descricao_servico, 50) }}</span>
                                                @if ($fatura->observacoes)
                                                    <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs">{{ Str::limit($fatura->observacoes, 60) }}</p>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-slate-500">{{ $fatura->cliente?->nome ?? '—' }}</td>
                                            <td class="px-4 py-3 text-slate-500">
                                                {{ $fatura->vencimento ? \Carbon\Carbon::parse($fatura->vencimento)->format('d/m/Y') : '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div x-show="pago" style="display:none">
                                                    <input type="date" x-model="dataPagamento" @change="toggle"
                                                           class="text-xs border border-slate-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-1 focus:ring-brand-400 bg-white">
                                                </div>
                                                <span x-show="!pago" class="text-slate-400 text-xs">—</span>
                                            </td>
                                            <td class="px-4 py-3 text-right font-semibold text-slate-800">
                                                {{ $fatura->valor ? 'R$ ' . number_format($fatura->valor, 2, ',', '.') : '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <a href="{{ route('faturas.pdf', $fatura) }}" target="_blank"
                                                       class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                       title="Baixar PDF">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    </a>
                                                    <a href="{{ route('faturas.edit', $fatura) }}"
                                                       class="p-1.5 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors"
                                                       title="Editar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </a>
                                                    <form method="POST" action="{{ route('faturas.destroy', $fatura) }}" onsubmit="return confirm('Excluir esta fatura?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                                title="Excluir">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
