<x-layouts.app>
    <div class="p-6 md:p-8 max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('faturas.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Nova Fatura</h1>
                <p class="text-sm text-slate-500 mt-0.5">Crie uma fatura única ou gere 12 meses de uma vez</p>
            </div>
        </div>

        <form method="POST" action="{{ route('faturas.store') }}" class="space-y-6" x-data="{ tipo: 'unica' }">
            @csrf

            {{-- Tipo --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                <label class="block text-sm font-semibold text-slate-700 mb-3">Tipo de Fatura</label>
                <div class="flex gap-3">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="tipo" value="unica" x-model="tipo" class="sr-only">
                        <div :class="tipo === 'unica' ? 'border-brand-500 bg-brand-50 text-brand-700 ring-2 ring-brand-100' : 'border-slate-200 text-slate-600'"
                             class="border-2 rounded-xl px-4 py-3 flex items-center gap-3 transition-all">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                            <div>
                                <span class="font-semibold text-sm block">Fatura Única</span>
                                <span class="text-xs opacity-70">Cobrança pontual</span>
                            </div>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="tipo" value="mensal" x-model="tipo" class="sr-only">
                        <div :class="tipo === 'mensal' ? 'border-brand-500 bg-brand-50 text-brand-700 ring-2 ring-brand-100' : 'border-slate-200 text-slate-600'"
                             class="border-2 rounded-xl px-4 py-3 flex items-center gap-3 transition-all">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <div>
                                <span class="font-semibold text-sm block">Contrato Mensal</span>
                                <span class="text-xs opacity-70">Gera 12 meses de uma vez</span>
                            </div>
                        </div>
                    </label>
                </div>

                {{-- Aviso mensal --}}
                <div x-show="tipo === 'mensal'" x-transition
                     class="mt-3 px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-700 flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Serão criadas <strong>12 faturas mensais</strong> automaticamente (vencimento dia 5 de cada mês), agrupadas sob o mesmo contrato.
                </div>
            </div>

            {{-- Dados principais --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                <h2 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Dados da Fatura</h2>

                {{-- Serviço --}}
                <div>
                    <label for="descricao_servico" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Descrição do Serviço <span class="text-red-500">*</span>
                    </label>
                    <textarea name="descricao_servico" id="descricao_servico" rows="2" required
                              placeholder="Ex: Manutenção mensal do sistema de gestão XYZ"
                              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all resize-none placeholder-slate-400">{{ old('descricao_servico') }}</textarea>
                    @error('descricao_servico') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Cliente --}}
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-slate-700 mb-1.5">Cliente</label>
                        <select name="cliente_id" id="cliente_id"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all bg-white">
                            <option value="">-- Selecionar --</option>
                            @foreach ($clientes as $c)
                                <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Projeto --}}
                    <div>
                        <label for="projeto_id" class="block text-sm font-medium text-slate-700 mb-1.5">Projeto</label>
                        <select name="projeto_id" id="projeto_id"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all bg-white">
                            <option value="">-- Nenhum --</option>
                            @foreach ($projetos as $p)
                                <option value="{{ $p->id }}" {{ old('projeto_id') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Valor --}}
                    <div>
                        <label for="valor" class="block text-sm font-medium text-slate-700 mb-1.5">Valor (R$)</label>
                        <input type="number" name="valor" id="valor" step="0.01" min="0"
                               value="{{ old('valor') }}" placeholder="0,00"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all placeholder-slate-400">
                    </div>

                    {{-- Data de vencimento --}}
                    <div x-show="tipo === 'unica'">
                        <label for="vencimento" class="block text-sm font-medium text-slate-700 mb-1.5">Vencimento</label>
                        <input type="date" name="vencimento" id="vencimento"
                               value="{{ old('vencimento') }}"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                    </div>

                    {{-- Mês início (mensal) --}}
                    <div x-show="tipo === 'mensal'">
                        <label for="mes_inicio" class="block text-sm font-medium text-slate-700 mb-1.5">Mês de Início</label>
                        <input type="month" name="mes_inicio" id="mes_inicio"
                               value="{{ old('mes_inicio', now()->format('Y-m')) }}"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                    </div>
                </div>

                {{-- Observações --}}
                <div>
                    <label for="observacoes" class="block text-sm font-medium text-slate-700 mb-1.5">Observações</label>
                    <textarea name="observacoes" id="observacoes" rows="3"
                              placeholder="Informações adicionais, notas, número do contrato..."
                              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all resize-none placeholder-slate-400">{{ old('observacoes') }}</textarea>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('faturas.index') }}"
                   class="px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-brand-200 transition-colors"
                        x-text="tipo === 'mensal' ? 'Criar 12 Faturas Mensais' : 'Criar Fatura'">
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
