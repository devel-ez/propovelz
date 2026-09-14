<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fatura #{{ str_pad($fatura->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #1e293b;
            background: #ffffff;
            padding: 0;
        }

        /* ── HEADER BAR ── */
        .header-bar {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            padding: 40px 50px 32px;
            color: #ffffff;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
        }
        .company-name {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .company-tagline {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            margin-top: 4px;
        }
        .invoice-label {
            text-align: right;
        }
        .invoice-label .word {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.75);
        }
        .invoice-label .number {
            font-size: 28px;
            font-weight: 800;
            line-height: 1;
            margin-top: 2px;
        }

        .status-pill {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 6px;
        }
        .status-pago   { background: #d1fae5; color: #065f46; }
        .status-pendente { background: #fef3c7; color: #92400e; }

        /* ── BODY ── */
        .body {
            padding: 36px 50px;
        }

        /* ── META ROW ── */
        .meta-row {
            display: flex;
            gap: 20px;
            margin-bottom: 32px;
        }
        .meta-card {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
        }
        .meta-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 6px;
        }
        .meta-value {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }
        .meta-value-large {
            font-size: 20px;
            font-weight: 800;
            color: #2563eb;
        }

        /* ── SERVICE BOX ── */
        .service-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }
        .service-box-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .service-box-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.4;
        }
        .service-box-obs {
            font-size: 12px;
            color: #64748b;
            margin-top: 8px;
            line-height: 1.5;
        }

        /* ── TABLE ── */
        .table-section {
            margin-bottom: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
        }
        thead th {
            background: #1e293b;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 12px 16px;
            text-align: left;
        }
        thead th.right { text-align: right; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr:last-child { border-bottom: none; }
        tbody td {
            padding: 14px 16px;
            font-size: 13px;
            color: #334155;
            vertical-align: top;
        }
        tbody td.right { text-align: right; font-weight: 600; color: #0f172a; }
        .total-row td {
            background: #f0f9ff;
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
            border-top: 2px solid #bfdbfe;
        }
        .total-row td.right { color: #2563eb; font-size: 18px; }

        /* ── PAYMENT INFO ── */
        .payment-info {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .payment-info-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #059669;
            margin-bottom: 6px;
        }
        .payment-info-value {
            font-size: 14px;
            font-weight: 600;
            color: #065f46;
        }

        /* ── FOOTER ── */
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-note {
            font-size: 11px;
            color: #94a3b8;
        }
        .footer-generated {
            font-size: 11px;
            color: #cbd5e1;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 24px 0;
        }
    </style>
</head>
<body>
    {{-- ─── HEADER ─── --}}
    <div class="header-bar">
        <div class="header-top">
            <div>
                <div class="company-name">Crie Sites Pro</div>
                <div class="company-tagline">Criação de sites, landing pages e sistemas</div>
            </div>
            <div class="invoice-label">
                <div class="word">Fatura</div>
                <div class="number">#{{ str_pad($fatura->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div>
                    @if ($fatura->pago)
                        <span class="status-pill status-pago">✓ Pago</span>
                    @else
                        <span class="status-pill status-pendente">Pendente</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ─── BODY ─── --}}
    <div class="body">

        {{-- META CARDS --}}
        <div class="meta-row">
            <div class="meta-card">
                <div class="meta-label">Cliente</div>
                <div class="meta-value">{{ $fatura->cliente?->nome ?? '—' }}</div>
            </div>

            <div class="meta-card">
                <div class="meta-label">Vencimento</div>
                <div class="meta-value">
                    {{ $fatura->vencimento ? \Carbon\Carbon::parse($fatura->vencimento)->format('d/m/Y') : '—' }}
                </div>
            </div>

            @if ($fatura->tipo === 'mensal')
            <div class="meta-card">
                <div class="meta-label">Mês de Referência</div>
                <div class="meta-value">
                    @php
                        $mr = $fatura->mes_referencia ? \Carbon\Carbon::parse($fatura->mes_referencia) : null;
                        echo $mr ? ($meses[(int)$mr->format('n') - 1] . ' ' . $mr->format('Y')) : '—';
                    @endphp
                </div>
            </div>
            @endif

            <div class="meta-card">
                <div class="meta-label">Valor Total</div>
                <div class="meta-value-large">
                    R$ {{ $fatura->valor ? number_format($fatura->valor, 2, ',', '.') : '0,00' }}
                </div>
            </div>
        </div>

        {{-- SERVICE BOX --}}
        <div class="service-box">
            <div class="service-box-label">Descrição do Serviço</div>
            <div class="service-box-title">{{ $fatura->descricao_servico }}</div>
            @if ($fatura->observacoes)
                <div class="service-box-obs">{{ $fatura->observacoes }}</div>
            @endif
        </div>

        {{-- TABLE --}}
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        @if ($fatura->tipo === 'mensal')
                            <th>Competência</th>
                        @endif
                        @if ($fatura->projeto)
                            <th>Projeto</th>
                        @endif
                        <th class="right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $fatura->descricao_servico }}</td>
                        @if ($fatura->tipo === 'mensal')
                            <td>
                                @php
                                    $mr2 = $fatura->mes_referencia ? \Carbon\Carbon::parse($fatura->mes_referencia) : null;
                                    echo $mr2 ? ($meses[(int)$mr2->format('n') - 1] . '/' . $mr2->format('Y')) : '—';
                                @endphp
                            </td>
                        @endif
                        @if ($fatura->projeto)
                            <td>{{ $fatura->projeto->nome }}</td>
                        @endif
                        <td class="right">R$ {{ $fatura->valor ? number_format($fatura->valor, 2, ',', '.') : '0,00' }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="{{ 1 + ($fatura->tipo === 'mensal' ? 1 : 0) + ($fatura->projeto ? 1 : 0) }}">Total</td>
                        <td class="right">R$ {{ $fatura->valor ? number_format($fatura->valor, 2, ',', '.') : '0,00' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- PAYMENT CONFIRMATION --}}
        @if ($fatura->pago && $fatura->data_pagamento)
            <div class="payment-info">
                <div class="payment-info-label">✓ Pagamento Confirmado</div>
                <div class="payment-info-value">
                    Recebido em {{ \Carbon\Carbon::parse($fatura->data_pagamento)->format('d/m/Y') }}
                </div>
            </div>
        @endif

    </div>

    {{-- ─── FOOTER ─── --}}
    <div class="footer">
        <div class="footer-note">Documento gerado automaticamente por Crie Sites Pro</div>
        <div class="footer-generated">{{ now()->format('d/m/Y H:i') }}</div>
    </div>
</body>
</html>
