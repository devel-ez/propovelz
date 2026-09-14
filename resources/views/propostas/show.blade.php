<x-layouts.app>
    <div class="p-6 md:p-8 max-w-5xl mx-auto">
        {{-- Header / Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('propostas.index') }}" class="p-2 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Detalhes da Proposta</h1>
                    <p class="text-sm text-slate-500 font-medium">Visualização interna do documento</p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                @php
                    $statusInfo = $statuses[$proposta->status] ?? ['label' => $proposta->status, 'color' => 'gray'];
                    $colorMap = [
                        'gray'   => 'bg-slate-100 text-slate-600 border-slate-200',
                        'blue'   => 'bg-blue-50 text-blue-700 border-blue-200',
                        'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'green'  => 'bg-green-50 text-green-700 border-green-200',
                        'red'    => 'bg-red-50 text-red-700 border-red-200',
                        'orange' => 'bg-orange-50 text-orange-700 border-orange-200',
                    ];
                    $badgeClass = $colorMap[$statusInfo['color']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                @endphp
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg border text-sm font-semibold shadow-sm {{ $badgeClass }}">
                    <div class="w-2 h-2 rounded-full mr-2 {{ str_replace(['bg-', '-50', '-100'], ['bg-', '-500', '-500'], explode(' ', $badgeClass)[0]) }} opacity-75"></div>
                    {{ $statusInfo['label'] }}
                </span>

                <a href="{{ route('propostas.pdf', $proposta) }}" target="_blank"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-900 px-4 py-2.5 rounded-xl transition-all shadow-sm">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    Gerar PDF
                </a>

                <a href="{{ route('propostas.edit', $proposta) }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 hover:shadow-md px-4 py-2.5 rounded-xl transition-all shadow-sm border border-brand-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
            </div>
        </div>

        {{-- Alertas e Links --}}
        @if($proposta->token_publico)
            <div class="bg-white border-l-4 border-l-green-500 border-y border-r border-slate-200 rounded-xl p-5 mb-8 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Proposta disponível para o cliente
                    </h3>
                    <p class="text-sm text-slate-500 mb-2">Compartilhe o link abaixo para o cliente visualizar e assinar digitalmente.</p>
                    <div class="flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200 w-max max-w-full overflow-hidden">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <a href="{{ route('propostas.publica', $proposta->token_publico) }}" target="_blank" class="text-sm text-brand-600 hover:text-brand-800 hover:underline truncate">
                            {{ route('propostas.publica', $proposta->token_publico) }}
                        </a>
                    </div>
                </div>
                <div class="flex flex-col gap-2 flex-shrink-0">
                    <a href="https://wa.me/?text={{ urlencode('Olá! Sua proposta está pronta. Acesse e assine: ' . route('propostas.publica', $proposta->token_publico)) }}"
                       target="_blank"
                       class="inline-flex items-center justify-center gap-2 text-sm font-bold text-white bg-[#25D366] hover:bg-[#1ebe5d] shadow-sm rounded-xl px-4 py-2.5 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        @else
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-indigo-900 mb-1">Pronto para enviar ao cliente?</h3>
                    <p class="text-sm text-indigo-700">Gere um link seguro para coletar a assinatura online e fechar o negócio.</p>
                </div>
                <form method="POST" action="{{ route('propostas.gerar-link', $proposta) }}">
                    @csrf
                    <button class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm rounded-xl px-5 py-2.5 transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        Gerar Link Público
                    </button>
                </form>
            </div>
        @endif

        {{-- Preview do Documento (A4 Simulation) --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative group">
            


            <div class="px-8 py-10 sm:p-12">
                {{-- Header Documento --}}
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 border-b border-slate-100 pb-8 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-brand-600 to-cyan-500 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-8 h-8" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                                <path d="M6 12.5 16 6l10 6.5v2.2L16 8.2 6 14.7v-2.2Z"/>
                                <path d="M6 19.4 16 12.9l10 6.5v2.2L16 15.1 6 21.6v-2.2Z" opacity=".6"/>
                                <path d="M6 25.8 16 19.3l10 6.5V28L16 21.5 6 28v-2.2Z" opacity=".35"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 leading-tight">{{ $proposta->titulo }}</h2>
                            <p class="text-sm font-semibold text-brand-600 mt-1">Crie Sites Pro</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-6 sm:items-end w-full sm:w-auto mt-6 sm:mt-0">
                        {{-- Badge Assinada (Se houver) --}}
                        @if($proposta->assinado_em)
                            <div class="relative group w-fit">
                                <div class="w-32 h-32 absolute -top-4 -right-4 opacity-10 pointer-events-none">
                                    <svg class="w-full h-full text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1.177-7.86l-2.765-2.767L7 12.431l3.118 3.121a1 1 0 001.414 0l5.952-5.95-1.062-1.062-5.6 5.6z"/></svg>
                                </div>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-right shadow-sm relative z-20 backdrop-blur-sm bg-opacity-90">
                                    <div class="text-green-700 font-bold text-xs uppercase tracking-wider flex items-center justify-end gap-1 mb-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        Documento Assinado
                                    </div>
                                    <div class="text-sm font-semibold text-slate-800">{{ $proposta->assinado_por_nome }}</div>
                                    <div class="text-[10px] text-slate-500 font-medium">{{ \Carbon\Carbon::parse($proposta->assinado_em)->format('d/m/Y \à\s H:i') }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-x-8 gap-y-4 sm:text-right">
                            <div>
                                <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-0.5">Cliente</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $proposta->cliente?->nome }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-0.5">Criada em</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $proposta->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-0.5">Validade</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $proposta->data_validade ? \Carbon\Carbon::parse($proposta->data_validade)->format('d/m/Y') : 'Sem validade restrita' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-0.5">Ref</p>
                                <p class="text-sm font-semibold text-slate-800">#PROP-{{ str_pad($proposta->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Conteúdo Markdown/HTML --}}
                @if($proposta->conteudo)
                <div class="prose prose-slate prose-sm sm:prose-base max-w-none text-slate-700 leading-relaxed mb-12">
                    {!! $proposta->conteudo !!}
                </div>
                @endif

                {{-- Tabela de Itens --}}
                @if($proposta->itens->isNotEmpty())
                <div class="mb-12">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4 border-b border-slate-200 pb-2">Investimento e Escopo</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left align-middle border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="py-3 font-bold text-slate-500 uppercase text-xs tracking-wider">Descrição dos Serviços/Produtos</th>
                                    <th class="py-3 font-bold text-slate-500 uppercase text-xs tracking-wider text-center w-24">Qtd.</th>
                                    <th class="py-3 font-bold text-slate-500 uppercase text-xs tracking-wider text-right w-32">V. Unit.</th>
                                    <th class="py-3 font-bold text-slate-500 uppercase text-xs tracking-wider text-right w-36">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($proposta->itens as $item)
                                    <tr class="group hover:bg-slate-50 transition-colors">
                                        <td class="py-4 pr-4 font-medium text-slate-800">{{ $item->descricao }}</td>
                                        <td class="py-4 px-2 text-center text-slate-600">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                                        <td class="py-4 px-2 text-right text-slate-600">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                        <td class="py-4 pl-4 text-right font-bold text-slate-800">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex flex-col items-end mt-4 pt-4 border-t-2 border-slate-900">
                        <div class="w-full sm:w-1/2 md:w-1/3 flex items-center justify-between pb-2">
                            <span class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Subtotal</span>
                            <span class="text-sm font-semibold text-slate-700">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</span>
                        </div>
                        <div class="w-full sm:w-1/2 md:w-1/3 flex items-center justify-between pt-2 border-t border-slate-200">
                            <span class="text-base font-bold text-slate-900 uppercase">Total Final</span>
                            <span class="text-2xl font-black text-brand-600">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- Termos e Assinaturas --}}
                <div class="pt-12 border-t border-slate-200">
                    <div class="text-xs text-slate-500 max-w-sm mb-10">
                        <p class="mb-1">Este documento tem validade legal e os valores descritos são confidenciais.</p>
                        <p>Dúvidas? Entre em contato conosco através do e-mail de suporte institucional.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-12 sm:gap-8">
                        {{-- Contratada: assinatura automática --}}
                        <div class="w-full sm:w-64 text-center">
                            <div class="border-b-2 border-slate-400 pb-1 mb-2 h-12 flex items-end justify-center">
                                <span class="font-signature text-2xl text-blue-900 opacity-80" style="font-family: 'Brush Script MT', cursive, sans-serif;">{{ config('empresa.responsavel.assinatura') }}</span>
                            </div>
                            <p class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-0.5">Assinatura da Contratada</p>
                            <p class="text-[10px] text-slate-400 font-medium tracking-wide">
                                {{ config('empresa.responsavel.nome') }} - {{ config('empresa.responsavel.cargo') }}
                            </p>
                        </div>

                        {{-- Contratante: em branco até assinar pelo link --}}
                        <div class="w-full sm:w-64 text-center">
                            <div class="border-b-2 border-slate-400 pb-1 mb-2 h-12 flex items-end justify-center">
                                @if($proposta->assinado_em)
                                    <span class="font-signature text-2xl text-blue-900 opacity-80" style="font-family: 'Brush Script MT', cursive, sans-serif;">{{ $proposta->assinado_por_nome }}</span>
                                @endif
                            </div>
                            <p class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-0.5">Assinatura do Contratante</p>
                            @if($proposta->assinado_em)
                                <p class="text-[10px] text-slate-400 font-medium tracking-wide">
                                    Assinado em {{ \Carbon\Carbon::parse($proposta->assinado_em)->format('d/m/Y \à\s H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-brand-50 h-3 border-t border-brand-100 flex shadow-[inset_0_2px_4px_rgba(0,0,0,0.05)]"></div>
        </div>
    </div>
</x-layouts.app>
