<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposta — {{ $proposta->titulo }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .prose h1 { font-size: 1.4rem; font-weight: 700; margin: 1rem 0 .5rem; color: #0f172a; }
        .prose h2 { font-size: 1.15rem; font-weight: 600; margin: 1rem 0 .4rem; color: #1e293b; }
        .prose h3 { font-size: 1rem; font-weight: 600; margin: .75rem 0 .3rem; color: #334155; }
        .prose p  { margin: .5rem 0; color: #475569; line-height: 1.65; }
        .prose ul, .prose ol { padding-left: 1.5rem; margin: .5rem 0; color: #475569; }
        .prose li { margin: .2rem 0; }
        .prose strong { color: #0f172a; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    {{-- Top bar --}}
    <div class="bg-white border-b border-slate-200 px-6 py-4">
        <div class="max-w-3xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="font-semibold text-slate-700 text-sm">Propovelz</span>
            </div>
            @if($proposta->data_validade)
                <span class="text-xs text-slate-500">
                    Válida até {{ \Carbon\Carbon::parse($proposta->data_validade)->format('d/m/Y') }}
                </span>
            @endif
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-10 space-y-6">

        {{-- Flash: assinatura confirmada --}}
        @if(session('sucesso_assinatura'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-green-900">Proposta assinada com sucesso! 🎉</p>
                    <p class="text-sm text-green-700 mt-0.5">{{ session('sucesso_assinatura') }}</p>
                </div>
            </div>
        @endif

        @if(session('aviso'))
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm text-amber-800">
                {{ session('aviso') }}
            </div>
        @endif

        {{-- Proposal header card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $proposta->titulo }}</h1>
                    <p class="text-slate-500 mt-1">Para: <strong class="text-slate-700">{{ $proposta->cliente?->nome }}</strong></p>
                </div>
                @php
                    $statusInfo = match($proposta->status) {
                        'aprovada'    => ['label' => 'Aprovada',    'class' => 'bg-green-100 text-green-700'],
                        'recusada'    => ['label' => 'Recusada',   'class' => 'bg-red-100 text-red-700'],
                        'cancelada'   => ['label' => 'Cancelada',  'class' => 'bg-orange-100 text-orange-700'],
                        'visualizada' => ['label' => 'Visualizada','class' => 'bg-purple-100 text-purple-700'],
                        'enviada'     => ['label' => 'Enviada',    'class' => 'bg-blue-100 text-blue-700'],
                        default       => ['label' => 'Rascunho',   'class' => 'bg-slate-100 text-slate-600'],
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold {{ $statusInfo['class'] }} whitespace-nowrap flex-shrink-0">
                    {{ $statusInfo['label'] }}
                </span>
            </div>

            {{-- Conteúdo / corpo da proposta --}}
            @if($proposta->conteudo)
                <div class="prose max-w-none border-t border-slate-100 pt-4 mt-4">
                    {!! $proposta->conteudo !!}
                </div>
            @endif
        </div>

        {{-- Items table --}}
        @if($proposta->itens->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 pt-5 pb-2 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Itens / Serviços</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-6 py-3 text-left">Descrição</th>
                                <th class="px-4 py-3 text-center w-20">Qtd</th>
                                <th class="px-4 py-3 text-right w-32">Valor Unit.</th>
                                <th class="px-4 py-3 text-right w-36">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($proposta->itens as $item)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-6 py-4 text-slate-700">{{ $item->descricao }}</td>
                                    <td class="px-4 py-4 text-center text-slate-500">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-slate-500">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right font-semibold text-slate-800">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-blue-50 border-t-2 border-blue-100">
                                <td colspan="3" class="px-6 py-4 text-sm font-bold text-slate-700 text-right">Total:</td>
                                <td class="px-4 py-4 text-right text-xl font-extrabold text-blue-700">
                                    R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif

        {{-- Signature section --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            @if($proposta->assinado_em)
                {{-- Already signed --}}
                <div class="text-center py-4">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Proposta Assinada!</h3>
                    <p class="text-slate-500 mt-1 text-sm">
                        Assinado por <strong class="text-slate-700">{{ $proposta->assinado_por_nome }}</strong>
                        em {{ \Carbon\Carbon::parse($proposta->assinado_em)->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($proposta->assinado_em)->format('H:i') }}.
                    </p>
                </div>
            @elseif(in_array($proposta->status, ['recusada', 'cancelada']))
                <div class="text-center py-4 text-slate-500">
                    <p class="text-sm">Esta proposta foi <strong>{{ $proposta->status }}</strong> e não está disponível para assinatura.</p>
                </div>
            @else
                {{-- Signature form --}}
                <h3 class="text-base font-bold text-slate-900 mb-1">Assinar e Aprovar</h3>
                <p class="text-sm text-slate-500 mb-5">Ao assinar, você confirma o aceite desta proposta. Digite seu nome completo e clique em "Assinar".</p>

                <form method="POST" action="{{ route('propostas.assinar', $proposta->token_publico) }}">
                    @csrf
                    <div class="space-y-4">
                        @error('nome_assinante')
                            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-2 text-xs text-red-700">{{ $message }}</div>
                        @enderror
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="nome_assinante">
                                Seu nome completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nome_assinante"
                                   name="nome_assinante"
                                   value="{{ old('nome_assinante') }}"
                                   required
                                   placeholder="Ex: João da Silva"
                                   autocomplete="name"
                                   class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all">
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3.5 rounded-xl transition-colors shadow-sm shadow-blue-200 text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Assinar e Aprovar Proposta
                        </button>
                        <p class="text-center text-xs text-slate-400">
                            Ao assinar, você concorda com os termos desta proposta comercial.
                        </p>
                    </div>
                </form>
            @endif
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-slate-400 pb-6">
            Documento gerado via Propovelz · {{ now()->format('Y') }}
        </p>
    </div>
</body>
</html>
