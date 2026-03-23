<x-layouts.app title="Dashboard">

    {{-- ── Chart.js CDN ──────────────────────────────────────────────────── --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush

    <div class="flex-1 overflow-y-auto" id="main-content">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

            {{-- ── Header + Filtros ──────────────────────────────────────────── --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Visão geral do negócio</p>
                </div>

                {{-- Filtro em linha --}}
                <form method="GET" action="{{ route('dashboard') }}"
                      class="flex flex-wrap items-center gap-2">

                    {{-- Período --}}
                    <div class="inline-flex rounded-xl border border-slate-200 bg-white overflow-hidden shadow-sm text-sm font-medium">
                        @foreach ([1 => '1 mês', 3 => '3 meses', 6 => '6 meses', 12 => '12 meses'] as $val => $label)
                            <button type="submit" name="periodo" value="{{ $val }}"
                                    @if($clienteId) onclick="this.form.querySelector('[name=cliente_id]').disabled=false" @endif
                                    class="px-4 py-2 transition-colors
                                        {{ $periodo == $val
                                            ? 'bg-brand-600 text-white'
                                            : 'text-slate-600 hover:bg-slate-50' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Cliente --}}
                    <div class="flex items-center gap-2">
                        <select name="cliente_id"
                                onchange="this.form.submit()"
                                class="text-sm border border-slate-200 rounded-xl bg-white px-3 py-2 text-slate-700 focus:ring-2 focus:ring-brand-100 focus:border-brand-500 outline-none shadow-sm">
                            <option value="">Todos os clientes</option>
                            @foreach ($clientes as $c)
                                <option value="{{ $c->id }}" {{ $clienteId == $c->id ? 'selected' : '' }}>
                                    {{ $c->nome }}
                                </option>
                            @endforeach
                        </select>
                        {{-- Preserve período no submit via select --}}
                        <input type="hidden" name="periodo" value="{{ $periodo }}">
                    </div>
                </form>
            </div>

            {{-- ── KPI Cards ─────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                {{-- Recebido --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Recebido</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">
                                R$ {{ number_format($totalRecebido, 2, ',', '.') }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">Faturas pagas no período</p>
                        </div>
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                {{-- A Receber --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">A Receber</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">
                                R$ {{ number_format($totalAReceber, 2, ',', '.') }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">Em aberto (dentro do prazo)</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Vencidas --}}
                <div class="bg-white rounded-2xl border border-red-100 p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-red-400 uppercase tracking-wide">Vencidas</p>
                            <p class="mt-1 text-2xl font-bold text-red-600">
                                R$ {{ number_format($totalVencidas, 2, ',', '.') }}
                            </p>
                            <p class="text-xs text-red-300 mt-0.5">{{ $qtdVencidas }} fatura(s) em atraso</p>
                        </div>
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Propostas Aprovadas --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Propostas Aprovadas</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $propostasAprovadas }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">No período selecionado</p>
                        </div>
                        <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Gráficos ───────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

                {{-- Faturamento Mensal (linha) — ocupa 3/5 --}}
                <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-700 mb-4">Faturamento Recebido — Mês a Mês</h2>
                    <div class="relative h-56">
                        <canvas id="chartFaturamento"></canvas>
                    </div>
                </div>

                {{-- Propostas por status (rosca) — ocupa 2/5 --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-700 mb-4">Propostas por Status</h2>
                    <div class="relative h-56 flex items-center justify-center">
                        <canvas id="chartPropostas"></canvas>
                    </div>
                </div>

            </div>

            {{-- ── Top Clientes ───────────────────────────────────────────────── --}}
            @if($topClientes->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-700">Top Clientes — Por Valor Recebido</h2>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">#</th>
                            <th class="px-6 py-3 text-left font-semibold">Cliente</th>
                            <th class="px-6 py-3 text-right font-semibold">Recebido</th>
                            <th class="px-6 py-3 text-right font-semibold">Em Aberto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($topClientes as $i => $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3.5 text-slate-400 font-mono text-xs">{{ $i + 1 }}</td>
                            <td class="px-6 py-3.5 font-medium text-slate-800">
                                {{ $row->cliente?->nome ?? '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-right font-semibold text-emerald-600">
                                R$ {{ number_format($row->total_faturado, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-3.5 text-right
                                {{ $row->pendentes > 0 ? 'text-amber-600 font-semibold' : 'text-slate-400' }}">
                                {{ $row->pendentes > 0 ? $row->pendentes . ' fatura(s)' : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>

    {{-- ── Chart.js Init ─────────────────────────────────────────────────── --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Dados PHP → JS
        const mesesLabels = @json(collect($meses)->pluck('label'));
        const mesesValores = @json(collect($meses)->pluck('valor')->map(fn($v) => (float)$v));

        const propostasLabels = @json(collect($propostasPorStatus)->pluck('label'));
        const propostasCounts  = @json(collect($propostasPorStatus)->pluck('count')->map(fn($c) => (int)$c));

        // ── Gráfico de Linha: Faturamento Mensal ──────────────────────────
        new Chart(document.getElementById('chartFaturamento'), {
            type: 'line',
            data: {
                labels: mesesLabels,
                datasets: [{
                    label: 'Recebido (R$)',
                    data: mesesValores,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    pointBackgroundColor: '#2563eb',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' R$ ' + ctx.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2})
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { size: 11 },
                            callback: v => 'R$ ' + v.toLocaleString('pt-BR')
                        }
                    }
                }
            }
        });

        // ── Gráfico de Rosca: Propostas por Status ────────────────────────
        const statusColors = ['#94a3b8','#3b82f6','#a78bfa','#10b981','#ef4444','#f97316'];
        new Chart(document.getElementById('chartPropostas'), {
            type: 'doughnut',
            data: {
                labels: propostasLabels,
                datasets: [{
                    data: propostasCounts,
                    backgroundColor: statusColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 11 }, padding: 12, boxWidth: 12 }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.parsed}`
                        }
                    }
                }
            }
        });
    </script>

</x-layouts.app>
