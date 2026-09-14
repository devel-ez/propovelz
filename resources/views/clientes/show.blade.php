<x-layouts.app>
    @if(session('success'))
        <div id="flash-success"
             class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium animate-fade-in">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @php
        // As chaves são os status que o sistema realmente grava (em português,
        // iguais aos de App\Models\Proposta::STATUS). Estavam em inglês, então
        // nenhum casava e o selo saía sem rótulo e sem cor.
        $statusProposta = [
            'rascunho'    => ['Rascunho',    'bg-slate-100 text-slate-600'],
            'enviada'     => ['Enviada',     'bg-blue-100 text-blue-700'],
            'visualizada' => ['Visualizada', 'bg-indigo-100 text-indigo-700'],
            'aprovada'    => ['Aprovada',    'bg-emerald-100 text-emerald-700'],
            'recusada'    => ['Recusada',    'bg-red-100 text-red-700'],
            'cancelada'   => ['Cancelada',   'bg-slate-200 text-slate-500'],
        ];
    @endphp

    <div class="p-6 md:p-8 max-w-5xl" x-data="{ mostrar: {} }">
        {{-- Header --}}
        <div class="flex items-start gap-3 mb-8">
            <a href="{{ route('clientes.index') }}"
               class="mt-1 inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">{{ $cliente->nome }}</h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-sm text-slate-500">
                    @if($cliente->email)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $cliente->email }}
                        </span>
                    @endif
                    @if($cliente->telefone)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $cliente->telefone }}
                        </span>
                    @endif
                    @if($cliente->documento)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ $cliente->documento }}
                        </span>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('clientes.toggle-ativo', $cliente) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                        title="{{ $cliente->ativo ? 'Inativar: sai dos vencimentos, nada é apagado' : 'Reativar: os vencimentos voltam a contar' }}"
                        class="inline-flex items-center gap-2 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm whitespace-nowrap
                               {{ $cliente->ativo
                                    ? 'bg-white border border-slate-200 hover:bg-amber-50 hover:border-amber-200 text-slate-700'
                                    : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    {{ $cliente->ativo ? 'Inativar' : 'Reativar' }}
                </button>
            </form>
            <a href="{{ route('clientes.edit', $cliente) }}"
               class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
        </div>

        @unless($cliente->ativo)
            <div class="flex items-start gap-3 bg-slate-100 border border-slate-200 rounded-2xl px-5 py-4 mb-8">
                <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-slate-700">Cliente inativo</p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        A hospedagem e o domínio dele <strong>não aparecem</strong> no painel de vencimentos nem no dashboard.
                        Todo o histórico abaixo continua aqui, e nada foi apagado — é só clicar em <strong>Reativar</strong> quando ele voltar.
                    </p>
                </div>
            </div>
        @endunless

        {{-- Números --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Já pago</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Em aberto</p>
                <p class="text-2xl font-black text-slate-700 mt-1">R$ {{ number_format($totalAberto, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Vencido</p>
                <p class="text-2xl font-black mt-1 {{ $totalVencido > 0 ? 'text-red-600' : 'text-slate-300' }}">
                    R$ {{ number_format($totalVencido, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Acessos --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                Acessos
            </h2>

            @if($cliente->credenciais->isEmpty())
                <p class="text-sm text-slate-400">Nenhum acesso cadastrado.</p>
            @else
                <div class="space-y-2">
                    @foreach($cliente->credenciais as $cred)
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 bg-slate-50 rounded-xl px-4 py-3">
                            <span class="text-sm font-semibold text-slate-700 min-w-[140px]">{{ $cred->sistema }}</span>
                            <span class="text-sm text-slate-600">{{ $cred->login ?: '—' }}</span>

                            <span class="inline-flex items-center gap-2 ml-auto">
                                <span class="font-mono text-sm text-slate-600"
                                      x-text="mostrar[{{ $cred->id }}] ? @js($cred->senha) : '••••••••'"></span>
                                <button type="button" @click="mostrar[{{ $cred->id }}] = !mostrar[{{ $cred->id }}]"
                                        class="text-slate-400 hover:text-slate-700 transition-colors cursor-pointer"
                                        :title="mostrar[{{ $cred->id }}] ? 'Ocultar' : 'Mostrar'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </span>

                            @if($cred->observacao)
                                <span class="w-full text-xs text-slate-400 mt-1">{{ $cred->observacao }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Projetos --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-700">Projetos</h2>
                <a href="{{ route('projetos.create') }}" class="text-xs font-semibold text-blue-600 hover:underline">Novo projeto</a>
            </div>

            @if($cliente->projetos->isEmpty())
                <p class="text-sm text-slate-400">Nenhum projeto para este cliente.</p>
            @else
                <div class="space-y-2">
                    @foreach($cliente->projetos as $projeto)
                        <a href="{{ route('projetos.show', $projeto) }}"
                           class="flex items-center justify-between gap-4 bg-slate-50 hover:bg-slate-100 rounded-xl px-4 py-3 transition-colors">
                            <span class="text-sm font-medium text-slate-700">{{ $projeto->nome }}</span>
                            <span class="text-xs text-slate-500">{{ $projeto->status }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Propostas --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-700">Propostas</h2>
                <a href="{{ route('propostas.create') }}" class="text-xs font-semibold text-blue-600 hover:underline">Nova proposta</a>
            </div>

            @if($cliente->propostas->isEmpty())
                <p class="text-sm text-slate-400">Nenhuma proposta para este cliente.</p>
            @else
                <div class="space-y-2">
                    @foreach($cliente->propostas as $proposta)
                        @php $s = $statusProposta[$proposta->status] ?? [$proposta->status, 'bg-slate-100 text-slate-600']; @endphp
                        <a href="{{ route('propostas.show', $proposta) }}"
                           class="flex flex-wrap items-center gap-x-4 gap-y-1 bg-slate-50 hover:bg-slate-100 rounded-xl px-4 py-3 transition-colors">
                            <span class="text-sm font-medium text-slate-700 flex-1 min-w-[180px]">{{ $proposta->titulo }}</span>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $s[1] }}">{{ $s[0] }}</span>
                            <span class="text-sm font-semibold text-slate-600">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Faturas --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-700">Faturas</h2>
                <a href="{{ route('faturas.create') }}" class="text-xs font-semibold text-blue-600 hover:underline">Nova fatura</a>
            </div>

            @if($cliente->faturas->isEmpty())
                <p class="text-sm text-slate-400">Nenhuma fatura para este cliente.</p>
            @else
                <div class="space-y-2">
                    @foreach($cliente->faturas as $fatura)
                        @php
                            $atrasada = ! $fatura->pago && $fatura->vencimento && $fatura->vencimento->isPast();
                        @endphp
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 bg-slate-50 rounded-xl px-4 py-3">
                            <span class="text-sm text-slate-700 flex-1 min-w-[180px]">{{ $fatura->descricao_servico }}</span>

                            @if($fatura->vencimento)
                                <span class="text-xs {{ $atrasada ? 'text-red-500 font-semibold' : 'text-slate-500' }}">
                                    {{ $fatura->vencimento->format('d/m/Y') }}
                                </span>
                            @endif

                            <span class="text-sm font-semibold text-slate-700">R$ {{ number_format($fatura->valor, 2, ',', '.') }}</span>

                            @if($fatura->pago)
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700">Pago</span>
                            @elseif($atrasada)
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-red-100 text-red-700">Vencida</span>
                            @else
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-amber-100 text-amber-700">Pendente</span>
                            @endif

                            <a href="{{ route('faturas.pdf', $fatura) }}"
                               class="text-slate-400 hover:text-blue-600 transition-colors" title="Baixar PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
