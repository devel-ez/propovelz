<x-layouts.app>
    {{-- Flash messages --}}
    @if(session('success'))
        <div id="flash-success"
             class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium animate-fade-in">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="flash-error"
             class="fixed top-5 right-5 z-50 flex items-start gap-3 bg-red-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium max-w-lg">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @php
        $estilos = [
            'vencido'  => ['classe' => 'bg-red-100 text-red-700 border-red-200',           'rotulo' => 'Vencido'],
            'alerta'   => ['classe' => 'bg-amber-100 text-amber-700 border-amber-200',     'rotulo' => 'Vence em breve'],
            'ok'       => ['classe' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'rotulo' => 'Em dia'],
            'sem-data' => ['classe' => 'bg-slate-100 text-slate-600 border-slate-200',     'rotulo' => 'Sem data'],
        ];
    @endphp

    <div class="p-6 md:p-8 max-w-full">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Hospedagens e Domínios</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Vencimentos de todos os projetos, do mais urgente ao menos.
                    Alerta a partir de {{ \App\Support\Vencimentos::ALERTA_LABEL }} do vencimento.
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Um domínio só precisa de um comando para trazer a data certa
                     do registro. Hospedagem não tem essa fonte: depende do
                     comprovante, por isso só dá para digitar. --}}
                <form method="POST" action="{{ route('hospedagens.dominios.todos') }}"
                      onsubmit="return confirm('Consultar o whois de todos os domínios? Pode levar alguns segundos.')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2.5 rounded-xl border bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-700 transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Atualizar todos os domínios
                    </button>
                </form>

                <a href="{{ route('hospedagens.index', $incluirInativos ? [] : ['inativos' => 1]) }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2.5 rounded-xl border transition-colors whitespace-nowrap
                          {{ $incluirInativos
                                ? 'bg-slate-800 border-slate-800 text-white hover:bg-slate-700'
                                : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ $incluirInativos ? 'Ocultar clientes inativos' : 'Mostrar clientes inativos' }}
                </a>
            </div>
        </div>

        {{-- Resumo --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Vencidos</p>
                <p class="text-3xl font-black mt-1 {{ $resumo['vencidos'] ? 'text-red-600' : 'text-slate-300' }}">
                    {{ $resumo['vencidos'] }}
                </p>
                <p class="text-xs text-slate-400 mt-1">precisam de renovação</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Vencem em breve</p>
                <p class="text-3xl font-black mt-1 {{ $resumo['alerta'] ? 'text-amber-600' : 'text-slate-300' }}">
                    {{ $resumo['alerta'] }}
                </p>
                <p class="text-xs text-slate-400 mt-1">nos próximos {{ \App\Support\Vencimentos::ALERTA_LABEL }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Em dia</p>
                <p class="text-3xl font-black mt-1 text-emerald-600">{{ $resumo['ok'] }}</p>
                <p class="text-xs text-slate-400 mt-1">sem preocupação agora</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sem data</p>
                <p class="text-3xl font-black mt-1 {{ $resumo['sem_data'] ? 'text-slate-600' : 'text-slate-300' }}">
                    {{ $resumo['sem_data'] }}
                </p>
                <p class="text-xs text-slate-400 mt-1">cadastre a vigência</p>
            </div>
        </div>

        {{-- Tabela --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            @if($itens->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-slate-700">Nada por aqui ainda</p>
                    <p class="text-sm text-slate-400 mt-1 max-w-md">
                        Este painel mostra a hospedagem e o domínio dos projetos.
                        Preencha esses campos ao criar ou editar um projeto.
                    </p>
                    <a href="{{ route('projetos.index') }}"
                       class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:underline">
                        Ir para Projetos
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/60">
                                <th class="text-left font-semibold text-slate-500 px-6 py-3.5">Tipo</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Cliente</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Projeto</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Descrição</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Vencimento</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Situação</th>
                                <th class="text-left font-semibold text-slate-500 px-4 py-3.5">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($itens as $item)
                                @php $e = $estilos[$item['situacao']]; @endphp
                                <tr class="hover:bg-slate-50/40 transition-colors {{ $item['inativo'] ? 'bg-slate-50/70' : '' }}">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg
                                            {{ $item['tipo'] === 'Domínio' ? 'bg-indigo-50 text-indigo-700' : 'bg-blue-50 text-blue-700' }}">
                                            {{ $item['tipo'] }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">
                                        @if($item['cliente'])
                                            <a href="{{ route('clientes.show', $item['cliente']->id) }}"
                                               class="font-medium {{ $item['inativo'] ? 'text-slate-400' : 'text-slate-700' }} hover:text-blue-600 transition-colors">
                                                {{ $item['cliente']->nome }}
                                            </a>
                                            @if($item['inativo'])
                                                <span class="ml-1.5 text-[10px] font-bold bg-slate-200 text-slate-500 px-1.5 py-0.5 rounded-full uppercase tracking-wide">
                                                    Inativo
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        <a href="{{ route('projetos.show', $item['projeto']->id) }}"
                                           class="text-slate-600 hover:text-blue-600 transition-colors">
                                            {{ $item['projeto']->nome }}
                                        </a>
                                    </td>

                                    <td class="px-4 py-4 text-slate-500">{{ $item['descricao'] }}</td>

                                    <td class="px-4 py-4">
                                        @if($item['vigencia'])
                                            <span class="font-medium text-slate-700">{{ $item['vigencia']->format('d/m/Y') }}</span>
                                            <span class="block text-xs mt-0.5
                                                {{ $item['situacao'] === 'vencido' ? 'text-red-500' : ($item['situacao'] === 'alerta' ? 'text-amber-600' : 'text-slate-400') }}">
                                                @if($item['dias'] < 0)
                                                    venceu há {{ abs($item['dias']) }} {{ abs($item['dias']) === 1 ? 'dia' : 'dias' }}
                                                @elseif($item['dias'] === 0)
                                                    vence hoje
                                                @else
                                                    em {{ $item['dias'] }} {{ $item['dias'] === 1 ? 'dia' : 'dias' }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-lg border {{ $e['classe'] }}">
                                            {{ $e['rotulo'] }}
                                        </span>
                                    </td>

                                    {{-- Ações: a data é sempre digitável; o domínio
                                         ainda pode ser buscado no whois. --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <form method="POST"
                                                  action="{{ $item['tipo'] === 'Domínio'
                                                        ? route('hospedagens.dominio', $item['projeto']->id)
                                                        : route('hospedagens.hospedagem', $item['projeto']->id) }}"
                                                  class="flex items-center gap-1.5">
                                                @csrf
                                                @method('PATCH')
                                                <input type="date" name="vigencia"
                                                       value="{{ $item['vigencia']?->format('Y-m-d') }}"
                                                       title="Vencimento (deixe vazio para remover)"
                                                       class="text-xs border border-slate-200 rounded-lg px-2 py-1.5 text-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 w-[8.6rem]">
                                                <button type="submit" title="Salvar vencimento"
                                                        class="p-1.5 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>

                                            @if($item['tipo'] === 'Domínio')
                                                <form method="POST"
                                                      action="{{ route('hospedagens.dominio.whois', $item['projeto']->id) }}"
                                                      onsubmit="this.querySelector('button').disabled = true">
                                                    @csrf
                                                    <button type="submit" title="Buscar a data no whois"
                                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer disabled:opacity-40">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/40">
                    <p class="text-xs text-slate-400">
                        {{ $resumo['total'] }} {{ $resumo['total'] === 1 ? 'item' : 'itens' }} no total
                        · hospedagem e domínio de um mesmo projeto aparecem em linhas separadas porque vencem em datas diferentes
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
