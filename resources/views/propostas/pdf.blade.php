@php
    $emp = config('empresa');
    $ref = '#PROP-' . str_pad($proposta->id, 4, '0', STR_PAD_LEFT);

    // Fontes em TTF porque o dompdf não usa woff2. Ficam no repositório para
    // o deploy ser reprodutível. Com o subsetting ligado (config/dompdf.php),
    // só os caracteres usados entram no arquivo, então o PDF segue leve.
    $fonte = fn (string $arq) => 'file://' . resource_path('fonts/' . $arq);
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>{{ $proposta->titulo }}</title>
<style>
    @font-face { font-family: 'PJS'; font-style: normal; font-weight: 400; src: url('{{ $fonte('PlusJakartaSans-Regular.ttf') }}') format('truetype'); }
    @font-face { font-family: 'PJS'; font-style: normal; font-weight: 600; src: url('{{ $fonte('PlusJakartaSans-SemiBold.ttf') }}') format('truetype'); }
    @font-face { font-family: 'PJS'; font-style: normal; font-weight: 700; src: url('{{ $fonte('PlusJakartaSans-Bold.ttf') }}') format('truetype'); }
    @font-face { font-family: 'PJS'; font-style: normal; font-weight: 800; src: url('{{ $fonte('PlusJakartaSans-ExtraBold.ttf') }}') format('truetype'); }

    /* A capa ocupa a página inteira, sem margem. As páginas de conteúdo
       usam margem normal. */
    @page { margin: 92px 56px 76px; }
    @page :first { margin: 0; }

    /* O dompdf NÃO suporta flexbox, gap, box-shadow nem gradiente. Todo o
       layout aqui usa tabela, float, borda e border-radius. */
    body {
        font-family: 'PJS', sans-serif;
        font-size: 10.5px;
        line-height: 1.65;
        color: #0F172A;
        margin: 0;
        padding: 0;
    }

    /* ---------- CAPA ---------- */
    /* Centro, como o hero da landing. Antes era alinhado à esquerda e
       ficava com cara de outro documento. */
    .capa {
        position: relative;
        width: 100%;
        height: 100%;
        background-color: #0A0F1C;
        page-break-after: always;
        overflow: hidden;
        text-align: center;
    }
    .capa-logo    { position: absolute; top: 96px;  left: 0; right: 0; }
    .capa-centro  { position: absolute; top: 372px; left: 76px; right: 76px; }
    .capa-fatos   { position: absolute; bottom: 215px; left: 76px; right: 76px; }
    .capa-rodape  { position: absolute; bottom: 84px;  left: 0; right: 0; }

    .capa-eyebrow {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 2.4px;
        text-transform: uppercase;
        color: #60A5FA;
        margin-bottom: 18px;
    }
    .capa-titulo {
        font-size: 40px;
        font-weight: 800;
        line-height: 1.12;
        letter-spacing: -1px;
        color: #FFFFFF;
    }
    .capa-sub {
        margin-top: 20px;
        font-size: 13px;
        color: #8C9AB4;
        line-height: 1.6;
    }
    .capa-fatos table { width: 100%; border-collapse: collapse; border-top: 1px solid #26304A; }
    .capa-fatos td { padding: 22px 10px 0; vertical-align: top; width: 33.33%; text-align: center; }
    .capa-fatos .rot {
        font-size: 9px; font-weight: 700; letter-spacing: 1.6px;
        text-transform: uppercase; color: #5D6B85; margin-bottom: 5px;
    }
    .capa-fatos .val { font-size: 14px; font-weight: 700; color: #FFFFFF; }

    .capa-rodape { font-size: 10px; color: #5D6B85; }
    .capa-rodape .marca { font-weight: 700; color: #8C9AB4; }

    /* ---------- CABEÇALHO DAS PÁGINAS DE CONTEÚDO ---------- */
    .cabecalho { border-bottom: 2px solid #2563EB; padding-bottom: 12px; margin-bottom: 26px; }
    .cabecalho table { width: 100%; border-collapse: collapse; }
    .cabecalho td { vertical-align: middle; }
    .cabecalho .dir { text-align: right; }
    .cabecalho .ref {
        font-size: 10px; font-weight: 700; letter-spacing: 1.4px;
        text-transform: uppercase; color: #2563EB;
    }
    .cabecalho .data { font-size: 10px; color: #64748B; margin-top: 3px; }

    /* ---------- TÍTULO E FICHA ---------- */
    .titulo-doc {
        font-size: 22px; font-weight: 800; letter-spacing: -0.4px;
        color: #0F172A; margin: 0 0 18px 0; line-height: 1.25;
    }
    .ficha { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    .ficha td {
        width: 25%; vertical-align: top;
        background-color: #F5F8FD;
        border: 1px solid #E5E9F0;
        padding: 12px 14px;
    }
    .ficha .rot {
        font-size: 8.5px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; color: #94A3B8; margin-bottom: 4px;
    }
    .ficha .val { font-size: 11px; font-weight: 600; color: #0F172A; }

    /* ---------- CONTEÚDO (texto do cliente) ---------- */
    .conteudo { margin-bottom: 34px; }
    .conteudo h2 {
        font-size: 13px; font-weight: 800; letter-spacing: -0.2px;
        color: #0F172A;
        border-bottom: 1px solid #E5E9F0;
        padding-bottom: 7px;
        margin: 24px 0 12px 0;
    }
    .conteudo h2:first-child { margin-top: 0; }
    .conteudo h3 { font-size: 11.5px; font-weight: 700; color: #0F172A; margin: 18px 0 8px 0; }
    .conteudo p { margin: 0 0 10px 0; }
    .conteudo ul, .conteudo ol { margin: 0 0 12px 0; padding-left: 18px; }
    .conteudo li { margin-bottom: 5px; }
    .conteudo strong { font-weight: 700; color: #0F172A; }

    /* ---------- ITENS ---------- */
    .bloco-titulo {
        font-size: 12px; font-weight: 800; letter-spacing: 0.4px;
        text-transform: uppercase; color: #0F172A;
        border-bottom: 2px solid #2563EB;
        padding-bottom: 8px; margin-bottom: 0;
    }
    .itens { width: 100%; border-collapse: collapse; margin-bottom: 0; }
    .itens thead th {
        background-color: #F5F8FD;
        border-bottom: 1px solid #E5E9F0;
        font-size: 8.5px; font-weight: 700; letter-spacing: 1.1px;
        text-transform: uppercase; color: #94A3B8;
        padding: 10px 14px; text-align: left;
    }
    .itens tbody td {
        border-bottom: 1px solid #EDF1F7;
        padding: 11px 14px; vertical-align: top;
        font-size: 10.5px; color: #334155;
    }
    .itens tbody tr:last-child td { border-bottom: 0; }
    .itens .c { text-align: center; }
    .itens .r { text-align: right; }
    .itens .desc { font-weight: 600; color: #0F172A; }

    .totais { width: 100%; border-collapse: collapse; margin-top: 0; }
    .totais td { padding: 11px 14px; }
    .totais .rot {
        text-align: right; font-size: 9px; font-weight: 700;
        letter-spacing: 1.1px; text-transform: uppercase; color: #94A3B8;
    }
    .totais .val { text-align: right; font-size: 11px; font-weight: 700; color: #334155; width: 130px; }
    .totais .final {
        background-color: #F5F8FD;
        border-top: 2px solid #2563EB;
    }
    .totais .final .rot { color: #0F172A; }
    .totais .final .val { font-size: 15px; font-weight: 800; color: #2563EB; }

    /* ---------- ASSINATURAS ---------- */
    .assinaturas { margin-top: 54px; page-break-inside: avoid; }
    .assinaturas .nota {
        font-size: 9px; color: #94A3B8; line-height: 1.6;
        border-top: 1px solid #E5E9F0; padding-top: 16px; margin-bottom: 44px;
    }
    .assinaturas table { width: 100%; border-collapse: collapse; }
    .assinaturas td { width: 50%; vertical-align: bottom; padding: 0 14px; text-align: center; }
    .ass-linha {
        border-bottom: 1px solid #94A3B8;
        height: 42px;
        font-size: 15px;
        font-weight: 600;
        color: #2563EB;
        padding-bottom: 4px;
    }
    .ass-nome {
        font-size: 9px; font-weight: 700; letter-spacing: 1.1px;
        text-transform: uppercase; color: #0F172A; margin-top: 7px;
    }
    .ass-info { font-size: 8.5px; color: #94A3B8; margin-top: 3px; }

    /* ---------- RODAPÉ FIXO ---------- */
    .rodape {
        position: fixed;
        bottom: -46px; left: 0; right: 0;
        font-size: 8.5px; color: #B6C0D0;
        border-top: 1px solid #EDF1F7;
        padding-top: 8px;
    }
    .rodape table { width: 100%; border-collapse: collapse; }
    .rodape td { vertical-align: top; }
    .rodape .dir { text-align: right; }
</style>
</head>
<body>

    {{-- ===================== CAPA ===================== --}}
    <div class="capa">
        <div class="capa-logo">
            <img src="{{ public_path('assets/pdf/logo-claro.png') }}" width="252" alt="Crie Sites Pro">
        </div>

        <div class="capa-centro">
            <div class="capa-eyebrow">Proposta Comercial</div>
            <div class="capa-titulo">{{ $proposta->titulo }}</div>
            <div class="capa-sub">
                {{ $emp['responsavel']['nome'] }} - {{ $emp['responsavel']['cargo'] }}<br>
                {{ $emp['site'] }} - {{ $emp['whatsapp'] }}
            </div>
        </div>

        <div class="capa-fatos">
            <table>
                <tr>
                    <td>
                        <div class="rot">Cliente</div>
                        <div class="val">{{ $proposta->cliente?->nome ?? 'A definir' }}</div>
                    </td>
                    <td>
                        <div class="rot">Validade</div>
                        <div class="val">
                            {{ $proposta->data_validade ? \Carbon\Carbon::parse($proposta->data_validade)->format('d/m/Y') : 'Sem validade restrita' }}
                        </div>
                    </td>
                    <td>
                        <div class="rot">Investimento</div>
                        <div class="val">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="capa-rodape">
            <span class="marca">{{ $ref }}</span> &middot; emitida em {{ $proposta->created_at->format('d/m/Y') }}
        </div>
    </div>

    {{-- ===================== CONTEÚDO ===================== --}}
    <div class="pagina">

        <div class="cabecalho">
            <table>
                <tr>
                    <td>
                        <img src="{{ public_path('assets/pdf/logo-escuro.png') }}" width="185" alt="Crie Sites Pro">
                    </td>
                    <td class="dir">
                        <div class="ref">{{ $ref }}</div>
                        <div class="data">{{ $proposta->created_at->format('d/m/Y') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="titulo-doc">{{ $proposta->titulo }}</div>

        <table class="ficha">
            <tr>
                <td>
                    <div class="rot">Cliente</div>
                    <div class="val">{{ $proposta->cliente?->nome ?? 'A definir' }}</div>
                </td>
                <td>
                    <div class="rot">Emitida em</div>
                    <div class="val">{{ $proposta->created_at->format('d/m/Y') }}</div>
                </td>
                <td>
                    <div class="rot">Validade</div>
                    <div class="val">
                        {{ $proposta->data_validade ? \Carbon\Carbon::parse($proposta->data_validade)->format('d/m/Y') : 'Sem validade restrita' }}
                    </div>
                </td>
                <td>
                    <div class="rot">Valor total</div>
                    <div class="val">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</div>
                </td>
            </tr>
        </table>

        @if(trim((string) $proposta->conteudo) !== '')
            <div class="conteudo">{!! $proposta->conteudo !!}</div>
        @endif

        @if($proposta->itens->isNotEmpty())
            <div class="bloco-titulo">Investimento e escopo</div>
            <table class="itens">
                <thead>
                    <tr>
                        <th>Descrição dos serviços</th>
                        <th class="c" style="width: 60px;">Qtd.</th>
                        <th class="r" style="width: 100px;">Valor unit.</th>
                        <th class="r" style="width: 110px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proposta->itens as $item)
                        <tr>
                            <td class="desc">{{ $item->descricao }}</td>
                            <td class="c">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                            <td class="r">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                            <td class="r" style="font-weight: 700; color: #0F172A;">
                                R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="totais">
                <tr>
                    <td class="rot" style="width: 60%;">Subtotal</td>
                    <td class="val">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</td>
                </tr>
                <tr class="final">
                    <td class="rot">Total final</td>
                    <td class="val">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</td>
                </tr>
            </table>
        @endif

        <div class="assinaturas">
            <div class="nota">
                Este documento tem validade legal e os valores comerciais aqui descritos são de caráter
                estritamente confidencial. Dúvidas? Fale com a gente pelo WhatsApp
                {{ $emp['whatsapp'] }} ou pelo e-mail {{ $emp['email'] }}.
            </div>

            <table>
                <tr>
                    <td>
                        <div class="ass-linha">{{ $emp['responsavel']['assinatura'] }}</div>
                        <div class="ass-nome">Assinatura da Contratada</div>
                        <div class="ass-info">
                            {{ $emp['responsavel']['nome'] }} - {{ $emp['responsavel']['cargo'] }}
                        </div>
                    </td>
                    <td>
                        <div class="ass-linha">
                            @if($proposta->assinado_em)
                                {{ \Illuminate\Support\Str::title($proposta->assinado_por_nome) }}
                            @endif
                        </div>
                        <div class="ass-nome">Assinatura do Contratante</div>
                        @if($proposta->assinado_em)
                            <div class="ass-info">
                                Assinado em {{ \Carbon\Carbon::parse($proposta->assinado_em)->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

    </div>

    {{-- Rodapé fixo nas páginas de conteúdo (a capa é a primeira). --}}
    <div class="rodape">
        <table>
            <tr>
                <td>{{ $emp['nome'] }} &middot; {{ $emp['site'] }}</td>
                <td class="dir">{{ $ref }}</td>
            </tr>
        </table>
    </div>

</body>
</html>
