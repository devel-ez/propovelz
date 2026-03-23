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
                <h1 class="text-2xl font-bold text-slate-900">Editar Fatura</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    @if ($fatura->tipo === 'mensal')
                        @php
                            $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
                            $mr = $fatura->mes_referencia;
                            $mrCarbon = $mr ? \Carbon\Carbon::parse($mr) : null;
                            $labelMes = $mrCarbon ? ($meses[(int)$mrCarbon->format('n') - 1] . ' ' . $mrCarbon->format('Y')) : '';
                        @endphp
                        Contrato mensal — {{ $labelMes }}
                    @else
                        Fatura única
                    @endif
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('faturas.update', $fatura) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                <h2 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Dados da Fatura</h2>

                {{-- Serviço --}}
                <div>
                    <label for="descricao_servico" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Descrição do Serviço <span class="text-red-500">*</span>
                    </label>
                    <textarea name="descricao_servico" id="descricao_servico" rows="2" required
                              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all resize-none placeholder-slate-400">{{ old('descricao_servico', $fatura->descricao_servico) }}</textarea>
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
                                <option value="{{ $c->id }}" {{ (old('cliente_id') ?? $fatura->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
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
                                <option value="{{ $p->id }}" {{ (old('projeto_id') ?? $fatura->projeto_id) == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Valor --}}
                    <div>
                        <label for="valor" class="block text-sm font-medium text-slate-700 mb-1.5">Valor (R$)</label>
                        <input type="number" name="valor" id="valor" step="0.01" min="0"
                               value="{{ old('valor', $fatura->valor) }}"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all placeholder-slate-400">
                    </div>

                    {{-- Vencimento --}}
                    <div>
                        <label for="vencimento" class="block text-sm font-medium text-slate-700 mb-1.5">Vencimento</label>
                        <input type="date" name="vencimento" id="vencimento"
                               value="{{ old('vencimento', $fatura->vencimento ? \Carbon\Carbon::parse($fatura->vencimento)->format('Y-m-d') : '') }}"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                    </div>
                </div>

                {{-- Observações --}}
                <div>
                    <label for="observacoes" class="block text-sm font-medium text-slate-700 mb-1.5">Observações</label>
                    <textarea name="observacoes" id="observacoes" rows="3"
                              placeholder="Informações adicionais, notas, número do contrato..."
                              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all resize-none placeholder-slate-400">{{ old('observacoes', $fatura->observacoes) }}</textarea>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('faturas.index') }}"
                   class="px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-brand-200 transition-colors">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
