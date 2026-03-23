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
                <h1 class="text-2xl font-bold text-slate-900">Editar Contrato Mensal</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Alterações aplicadas a todas as {{ $faturas->count() }} parcelas
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('faturas.grupo.update', $grupo) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                <h2 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Dados do Contrato</h2>

                {{-- Serviço --}}
                <div>
                    <label for="descricao_servico" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Descrição do Serviço <span class="text-red-500">*</span>
                    </label>
                    <textarea name="descricao_servico" id="descricao_servico" rows="2" required
                              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all resize-none placeholder-slate-400">{{ old('descricao_servico', $primeira->descricao_servico) }}</textarea>
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
                                <option value="{{ $c->id }}" {{ (old('cliente_id') ?? $primeira->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
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
                                <option value="{{ $p->id }}" {{ (old('projeto_id') ?? $primeira->projeto_id) == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Valor mensal --}}
                    <div>
                        <label for="valor" class="block text-sm font-medium text-slate-700 mb-1.5">Valor Mensal (R$)</label>
                        <input type="number" name="valor" id="valor" step="0.01" min="0"
                               value="{{ old('valor', $primeira->valor) }}"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all placeholder-slate-400">
                    </div>

                    {{-- Dia de vencimento --}}
                    <div>
                        <label for="dia_vencimento" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Dia de Vencimento
                            <span class="text-slate-400 font-normal">(1–28)</span>
                        </label>
                        <input type="number" name="dia_vencimento" id="dia_vencimento" min="1" max="28"
                               value="{{ old('dia_vencimento', $primeira->vencimento ? \Carbon\Carbon::parse($primeira->vencimento)->day : 5) }}"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all placeholder-slate-400">
                        <p class="text-xs text-slate-400 mt-1">Recalcula o vencimento de todas as parcelas</p>
                    </div>
                </div>

                {{-- Observações --}}
                <div>
                    <label for="observacoes" class="block text-sm font-medium text-slate-700 mb-1.5">Observações</label>
                    <textarea name="observacoes" id="observacoes" rows="3"
                              placeholder="Informações adicionais, notas, número do contrato..."
                              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all resize-none placeholder-slate-400">{{ old('observacoes', $primeira->observacoes) }}</textarea>
                </div>
            </div>

            {{-- Resumo das parcelas --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-sm font-semibold text-slate-700">Parcelas do Contrato</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Somente o status de pagamento pode ser alterado individualmente</p>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-left">
                            <th class="px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Mês</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Vencimento atual</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @php $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']; @endphp
                        @foreach ($faturas as $f)
                            @php
                                $mr = $f->mes_referencia ? \Carbon\Carbon::parse($f->mes_referencia) : null;
                                $nomeMes = $mr ? ($meses[(int)$mr->format('n') - 1] . ' ' . $mr->format('Y')) : '—';
                            @endphp
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-2.5 font-medium text-slate-700">{{ $nomeMes }}</td>
                                <td class="px-4 py-2.5 text-slate-500">
                                    {{ $f->vencimento ? \Carbon\Carbon::parse($f->vencimento)->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    @if ($f->pago)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            Pago
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700">Pendente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
