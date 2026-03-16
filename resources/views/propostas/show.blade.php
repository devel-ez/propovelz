<x-layouts.app>
    <div class="p-6 md:p-8 max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('propostas.index') }}" class="p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">{{ $proposta->titulo }}</h1>
                    <p class="text-sm text-slate-500">Cliente: {{ $proposta->cliente?->nome }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
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
                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold {{ $badgeClass }}">
                    {{ $statusInfo['label'] }}
                </span>
                <a href="{{ route('propostas.edit', $proposta) }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
            </div>
        </div>

        {{-- Link público --}}
        @if($proposta->token_publico)
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-green-700 mb-1">🔗 Link público ativo</p>
                    <code class="text-xs text-green-900 break-all">{{ route('propostas.publica', $proposta->token_publico) }}</code>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="https://wa.me/?text={{ urlencode('Olá! Sua proposta está pronta. Acesse e assine: ' . route('propostas.publica', $proposta->token_publico)) }}"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-[#25D366] hover:bg-[#1ebe5d] rounded-lg px-3 py-2 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                </div>
                @if($proposta->assinado_em)
                    <div class="flex items-center gap-2 text-xs text-green-800 bg-green-100 rounded-xl px-3 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Assinado por <strong>{{ $proposta->assinado_por_nome }}</strong> em {{ \Carbon\Carbon::parse($proposta->assinado_em)->format('d/m/Y H:i') }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-6 flex items-center justify-between">
                <p class="text-sm text-slate-500">Gere um link para enviar ao cliente e receber a assinatura online.</p>
                <form method="POST" action="{{ route('propostas.gerar-link', $proposta) }}">
                    @csrf
                    <button class="inline-flex items-center gap-1.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl px-4 py-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        Gerar Link
                    </button>
                </form>
            </div>
        @endif

        {{-- Meta info --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-100 p-4">
                <p class="text-xs text-slate-400 mb-1">Valor Total</p>
                <p class="text-lg font-bold text-slate-900">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-4">
                <p class="text-xs text-slate-400 mb-1">Validade</p>
                <p class="text-sm font-semibold text-slate-700">
                    {{ $proposta->data_validade ? \Carbon\Carbon::parse($proposta->data_validade)->format('d/m/Y') : '—' }}
                </p>
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-4">
                <p class="text-xs text-slate-400 mb-1">Criada em</p>
                <p class="text-sm font-semibold text-slate-700">{{ $proposta->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        {{-- Content --}}
        @if($proposta->conteudo)
            <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-6">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Conteúdo</h2>
                <div class="prose prose-slate max-w-none text-sm leading-relaxed">{!! $proposta->conteudo !!}</div>
            </div>
        @endif

        {{-- Items table --}}
        @if($proposta->itens->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden mb-6">
                <div class="px-6 pt-5 pb-3 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Itens / Serviços</h2>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-3 text-left">Descrição</th>
                            <th class="px-4 py-3 text-center w-20">Qtd</th>
                            <th class="px-4 py-3 text-right w-32">Unit.</th>
                            <th class="px-4 py-3 text-right w-32">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($proposta->itens as $item)
                            <tr>
                                <td class="px-6 py-3 text-slate-700">{{ $item->descricao }}</td>
                                <td class="px-4 py-3 text-center text-slate-500">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-slate-500">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-slate-800">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 border-t-2 border-slate-200">
                            <td colspan="3" class="px-6 py-4 text-sm font-semibold text-slate-600 text-right">Total Geral:</td>
                            <td class="px-4 py-4 text-right text-base font-bold text-slate-900">
                                R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</x-layouts.app>
