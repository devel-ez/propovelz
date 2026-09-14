<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $proposta->titulo }}</title>
    <style>
        @page {
            margin-top: 80px;
            margin-bottom: 60px;
            margin-left: 0;
            margin-right: 0;
        }
        @page :first {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
        
        /* ------------------------------------------------ */
        /* Cover Page Styles                                */
        /* ------------------------------------------------ */
        html, body {
            height: 100%;
        }
        .cover-page {
            position: relative;
            width: 100%;
            height: 100%; /* Force exact page height without overflow */
            background-color: #f8fafc;
            overflow: hidden;
            page-break-after: always;
            box-sizing: border-box;
        }
        .shape-top-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 300px;
            background-color: #0a0f1c; /* Azul quase preto da marca */
        }
        .shape-top-right-triangle {
            position: absolute;
            top: 300px;
            right: 0;
            width: 0;
            height: 0;
            border-top: 150px solid #0a0f1c;
            border-left: 400px solid transparent;
        }
        .shape-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 180px;
            background-color: #2563eb; /* Azul da marca */
        }
        .shape-bottom-triangle {
            position: absolute;
            bottom: 180px;
            left: 0;
            width: 0;
            height: 0;
            border-bottom: 120px solid #2563eb;
            border-right: 800px solid transparent;
        }
        .cover-content-wrapper {
            position: absolute;
            top: 15%;
            left: 80px;
            right: 80px;
            z-index: 10;
        }
        .cover-logo {
            font-size: 32px;
            font-weight: 900;
            color: #0a0f1c;
            margin-bottom: 80px;
            letter-spacing: -0.5px;
        }
        .cover-title {
            font-size: 48px;
            font-weight: 900;
            line-height: 1.1;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: -1px;
        }
        .cover-title .dark { color: #0a0f1c; }
        .cover-title .accent { color: #2563eb; }
        
        .cover-subtitle {
            font-size: 20px;
            font-weight: bold;
            color: #334155;
            margin-top: 25px;
            margin-bottom: 40px;
            text-transform: uppercase;
            border-left: 5px solid #2563eb;
            padding-left: 15px;
            line-height: 1.4;
        }
        .cover-bullets {
            margin-top: 50px;
        }
        .cover-bullets table {
            width: 100%;
            border-collapse: collapse;
        }
        .cover-bullets td {
            padding: 10px 0;
            font-size: 16px;
            color: #475569;
            font-weight: 500;
        }
        .cover-bullets td.bullet {
            color: #2563eb;
            padding-right: 15px;
            font-size: 20px;
            width: 25px;
            vertical-align: top;
        }
        .cover-bottom-area {
            position: absolute;
            bottom: 40px;
            left: 80px;
            z-index: 10;
        }
        /* ------------------------------------------------ */
        
        /* Content Pages */
        .page-content {
            padding: 0 50px;
        }
        .header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #111;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 40px;
        }
        .meta-info td {
            width: 50%;
            vertical-align: top;
        }
        .meta-label {
            font-size: 10px;
            font-weight: bold;
            color: #888;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }
        .meta-value {
            font-size: 15px;
            font-weight: bold;
            color: #111;
            margin: 0 0 20px 0;
        }
        .content {
            margin-bottom: 50px;
            line-height: 1.7;
            font-size: 15px;
            color: #334155;
            text-align: justify;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        table.items th {
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #ddd;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        table.items td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            width: 100%;
        }
        .totals td {
            padding: 5px 10px;
        }
        .total-final {
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }
        .signature-section {
            margin-top: 60px;
            width: 100%;
        }
        .signature-box {
            width: 250px;
            float: right;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 40px;
            font-size: 20px;
            color: #2563eb;
            font-style: italic;
        }
        .signature-name {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .signature-date {
            font-size: 10px;
            color: #666;
            margin: 2px 0 0 0;
        }
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="cover-page">
        <div class="shape-top-right"></div>
        <div class="shape-top-right-triangle"></div>
        
        <div class="shape-bottom"></div>
        <div class="shape-bottom-triangle"></div>

        <div class="cover-content-wrapper">
            <div class="cover-logo">
                <span style="display: inline-block; width: 14px; height: 14px; background: #2563eb; border-radius: 4px; margin-right: 10px;"></span>Crie Sites <span style="color: #2563eb;">Pro</span>
            </div>

            <div class="cover-title">
                <span class="dark">PROPOSTA</span><br>
                <span class="dark">COMERCIAL</span><br>
                <span class="accent">DESENVOLVIMENTO E</span><br>
                <span class="accent">MANUTENÇÃO</span><br>
                <span class="dark">DE SOFTWARE</span>
            </div>

            <div class="cover-subtitle">
                {{ $proposta->titulo }}
            </div>

            <div class="cover-bullets">
                <table>
                    <tr>
                        <td class="bullet">&bull;</td>
                        <td>Soluções web modernas e escaláveis</td>
                    </tr>
                    <tr>
                        <td class="bullet">&bull;</td>
                        <td>Manutenção contínua e suporte técnico</td>
                    </tr>
                    <tr>
                        <td class="bullet">&bull;</td>
                        <td>Desenvolvimento orientado ao seu negócio</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="cover-bottom-area">
            <table>
                <tr>
                    <td style="background: white; color: #2563eb; padding: 12px 18px; border-radius: 8px; font-weight: bold; font-size: 24px; text-align: center;">
                        <span style="font-family: Helvetica, Arial, sans-serif;">CS</span>
                    </td>
                    <td style="padding-left: 20px; color: white; font-weight: bold; font-size: 14px; letter-spacing: 1px; line-height: 1.4;">
                        PRESTAÇÃO DE SERVIÇOS EM<br>TECNOLOGIA DA INFORMAÇÃO
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="page-content">
        <div class="header">
            <h1>{{ $proposta->titulo }}</h1>
            <p>Crie Sites Pro</p>
        </div>

    <table class="meta-info">
        <tr>
            <td>
                <p class="meta-label">Cliente</p>
                <p class="meta-value">{{ $proposta->cliente?->nome }}</p>
                
                <p class="meta-label">Criada em</p>
                <p class="meta-value">{{ $proposta->created_at->format('d/m/Y') }}</p>
            </td>
            <td style="text-align: right;">
                <p class="meta-label">Ref</p>
                <p class="meta-value">#PROP-{{ str_pad($proposta->id, 4, '0', STR_PAD_LEFT) }}</p>
                
                <p class="meta-label">Validade</p>
                <p class="meta-value">{{ $proposta->data_validade ? \Carbon\Carbon::parse($proposta->data_validade)->format('d/m/Y') : 'Sem validade restrita' }}</p>
            </td>
        </tr>
    </table>

    <div class="content">
        {!! $proposta->conteudo !!}
    </div>

    @if($proposta->itens->isNotEmpty())
        <h3 style="font-size: 14px; text-transform: uppercase; color: #333; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 15px;">Investimento e Escopo</h3>
        
        <table class="items">
            <thead>
                <tr>
                    <th>Descrição dos Serviços/Produtos</th>
                    <th class="text-center">Qtd.</th>
                    <th class="text-right">V. Unit.</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposta->itens as $item)
                    <tr>
                        <td>{{ $item->descricao }}</td>
                        <td class="text-center">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                        <td class="text-right">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: bold;">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td style="width: 60%;"></td>
                <td class="text-right" style="color: #666; font-size: 12px; text-transform: uppercase;">Subtotal</td>
                <td class="text-right" style="font-weight: bold;">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="width: 60%;"></td>
                <td class="text-right" style="font-weight: bold; text-transform: uppercase; padding-top: 15px; border-top: 1px solid #ddd;">Total Final</td>
                <td class="text-right total-final" style="padding-top: 15px; border-top: 1px solid #ddd;">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</td>
            </tr>
        </table>
    @endif

    <div class="signature-section">
        <div style="float: left; width: 55%; font-size: 12px; line-height: 1.5; color: #64748b; padding-top: 20px;">
            <p style="margin: 0 0 10px 0;"><strong>Confidencialidade:</strong> Este documento tem validade legal e os valores comerciais aqui descritos são de caráter estritamente confidencial.</p>
            <p style="margin: 0;"><strong>Suporte:</strong> Dúvidas? Entre em contato conosco através do canal de atendimento exclusivo da Crie Sites Pro.</p>
        </div>
        
        <div class="signature-box">
            <div class="signature-line">
                @if($proposta->assinado_em)
                    {{ \Illuminate\Support\Str::title($proposta->assinado_por_nome) }}
                @endif
            </div>
            <p class="signature-name">Assinatura do Cliente</p>
            <p class="signature-date">
                @if($proposta->assinado_em)
                    Assinado em {{ \Carbon\Carbon::parse($proposta->assinado_em)->format('d/m/Y H:i') }}
                @else
                    {{ $proposta->cliente?->nome }}
                @endif
            </p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
