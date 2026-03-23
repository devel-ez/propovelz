<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Fatura;
use App\Models\Proposta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodo    = (int) $request->input('periodo', 3); // meses
        $clienteId  = $request->input('cliente_id');

        $inicio = Carbon::now()->startOfMonth()->subMonths($periodo - 1);
        $fim    = Carbon::now()->endOfMonth();

        // ── Base queries ──────────────────────────────────────────────
        $faturaQuery = Fatura::query()
            ->when($clienteId, fn($q) => $q->where('cliente_id', $clienteId));

        $propostaQuery = Proposta::query()
            ->when($clienteId, fn($q) => $q->where('cliente_id', $clienteId));

        // ── KPI 1: Total recebido (pagas no período) ──────────────────
        $totalRecebido = (clone $faturaQuery)
            ->where('pago', true)
            ->whereBetween('data_pagamento', [$inicio, $fim])
            ->sum('valor');

        // ── KPI 2: A receber (em aberto, vencimento no período ou futuro)
        $totalAReceber = (clone $faturaQuery)
            ->where('pago', false)
            ->where('vencimento', '>=', Carbon::today())
            ->sum('valor');

        // ── KPI 3: Vencidas (em aberto, vencimento passado) ──────────
        $totalVencidas = (clone $faturaQuery)
            ->where('pago', false)
            ->where('vencimento', '<', Carbon::today())
            ->sum('valor');

        $qtdVencidas = (clone $faturaQuery)
            ->where('pago', false)
            ->where('vencimento', '<', Carbon::today())
            ->count();

        // ── KPI 4: Propostas aprovadas no período ─────────────────────
        $propostasAprovadas = (clone $propostaQuery)
            ->where('status', 'approved')
            ->whereBetween('created_at', [$inicio, $fim])
            ->count();

        // ── Gráfico 1: Faturamento mês a mês (recebido) ──────────────
        $meses = [];
        for ($i = $periodo - 1; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $meses[] = [
                'label' => $mes->translatedFormat('M/y'),
                'valor' => (clone $faturaQuery)
                    ->where('pago', true)
                    ->whereYear('data_pagamento', $mes->year)
                    ->whereMonth('data_pagamento', $mes->month)
                    ->sum('valor'),
            ];
        }

        // ── Gráfico 2: Propostas por status ───────────────────────────
        $statusLabels = ['draft' => 'Rascunho', 'sent' => 'Enviada',
                         'viewed' => 'Visualizada', 'approved' => 'Aprovada',
                         'rejected' => 'Recusada', 'canceled' => 'Cancelada'];
        $propostasPorStatus = [];
        foreach ($statusLabels as $status => $label) {
            $propostasPorStatus[] = [
                'label' => $label,
                'count' => (clone $propostaQuery)
                    ->where('status', $status)
                    ->whereBetween('created_at', [$inicio, $fim])
                    ->count(),
            ];
        }

        // ── Top Clientes ──────────────────────────────────────────────
        $topClientes = Fatura::query()
            ->selectRaw('cliente_id, SUM(valor) as total_faturado, COUNT(CASE WHEN pago = 0 THEN 1 END) as pendentes')
            ->where('pago', true)
            ->whereBetween('data_pagamento', [$inicio, $fim])
            ->when($clienteId, fn($q) => $q->where('cliente_id', $clienteId))
            ->groupBy('cliente_id')
            ->orderByDesc('total_faturado')
            ->limit(5)
            ->with('cliente:id,nome')
            ->get();

        // ── Clientes para o filtro ────────────────────────────────────
        $clientes = Cliente::orderBy('nome')->get(['id', 'nome']);

        return view('dashboard.index', compact(
            'totalRecebido', 'totalAReceber', 'totalVencidas', 'qtdVencidas',
            'propostasAprovadas', 'meses', 'propostasPorStatus',
            'topClientes', 'clientes', 'periodo', 'clienteId'
        ));
    }
}
