{{--
  Shared form partial for create and edit proposta.
  Expects: $clientes, $statuses, and optionally $proposta (for edit mode).
--}}
@php $p = $proposta ?? null; @endphp

{{-- Errors --}}
@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
        <ul class="space-y-0.5 list-disc list-inside">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Título --}}
<div class="space-y-1.5">
    <label class="text-sm font-semibold text-slate-700" for="titulo">Título da Proposta <span class="text-red-500">*</span></label>
    <input type="text"
           id="titulo"
           name="titulo"
           value="{{ old('titulo', $p?->titulo) }}"
           required
           placeholder="Ex: Desenvolvimento de site institucional"
           class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all @error('titulo') border-red-400 @enderror">
    @error('titulo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>

{{-- Cliente --}}
<div class="space-y-1.5">
    <label class="text-sm font-semibold text-slate-700" for="cliente_id">Cliente <span class="text-red-500">*</span></label>
    <select id="cliente_id"
            name="cliente_id"
            required
            class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all @error('cliente_id') border-red-400 @enderror">
        <option value="">Selecione um cliente...</option>
        @foreach($clientes as $cliente)
            <option value="{{ $cliente->id }}" @selected(old('cliente_id', $p?->cliente_id) == $cliente->id)>
                {{ $cliente->nome }}
            </option>
        @endforeach
    </select>
    @error('cliente_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>

{{-- Status + Validade (2 cols) --}}
<div class="grid grid-cols-2 gap-4">
    <div class="space-y-1.5">
        <label class="text-sm font-semibold text-slate-700" for="status">Status</label>
        <select id="status" name="status"
                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
            @foreach($statuses as $key => $s)
                <option value="{{ $key }}" @selected(old('status', $p?->status ?? 'rascunho') === $key)>
                    {{ $s['label'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="space-y-1.5">
        <label class="text-sm font-semibold text-slate-700" for="data_validade">Data de Validade</label>
        <input type="date"
               id="data_validade"
               name="data_validade"
               value="{{ old('data_validade', $p && $p->data_validade ? \Carbon\Carbon::parse($p->data_validade)->format('Y-m-d') : '') }}"
               class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
        @error('data_validade') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Conteúdo / Editor de texto --}}
<div class="space-y-1.5">
    <label class="text-sm font-semibold text-slate-700">Conteúdo da Proposta</label>
    <p class="text-xs text-slate-400">Use o editor abaixo para escrever a apresentação, escopo, condições, etc.</p>
    {{-- Quill toolbar + editor --}}
    <div id="quill-toolbar" class="border border-slate-200 rounded-t-xl bg-slate-50 px-2 py-1 flex flex-wrap gap-1">
        <button type="button" class="ql-bold p-1 rounded hover:bg-slate-200 text-slate-600 font-bold text-sm">B</button>
        <button type="button" class="ql-italic p-1 rounded hover:bg-slate-200 text-slate-600 italic text-sm">I</button>
        <button type="button" class="ql-underline p-1 rounded hover:bg-slate-200 text-slate-600 underline text-sm">U</button>
        <span class="w-px bg-slate-300 mx-1 self-stretch"></span>
        <button type="button" class="ql-list p-1 rounded hover:bg-slate-200 text-slate-600 text-sm" value="ordered">1.</button>
        <button type="button" class="ql-list p-1 rounded hover:bg-slate-200 text-slate-600 text-sm" value="bullet">•</button>
        <span class="w-px bg-slate-300 mx-1 self-stretch"></span>
        <select class="ql-header text-xs border border-slate-200 rounded px-1 bg-white">
            <option value="">Normal</option>
            <option value="1">Título 1</option>
            <option value="2">Título 2</option>
            <option value="3">Título 3</option>
        </select>
    </div>
    <div id="quill-editor"
         class="border border-t-0 border-slate-200 rounded-b-xl bg-white min-h-[200px] px-4 py-3 text-sm text-slate-700 focus:outline-none"
         style="min-height:200px">{!! old('conteudo', $p?->conteudo) !!}</div>
    <input type="hidden" id="conteudo-hidden" name="conteudo" value="{{ old('conteudo', $p?->conteudo) }}">
</div>

{{-- ===== TABELA DE ITENS ===== --}}
<div class="space-y-3">
    <div class="flex items-center justify-between">
        <label class="text-sm font-semibold text-slate-700">Itens / Serviços</label>
        <button type="button" id="btn-add-item"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 border border-blue-200 hover:border-blue-400 rounded-lg px-3 py-1.5 bg-blue-50 hover:bg-blue-100 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Adicionar Item
        </button>
    </div>

    <div class="rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-2.5 text-left w-auto">Descrição</th>
                    <th class="px-3 py-2.5 text-center w-24">Qtd</th>
                    <th class="px-3 py-2.5 text-right w-32">Valor Unit.</th>
                    <th class="px-3 py-2.5 text-right w-32">Total</th>
                    <th class="px-3 py-2.5 w-10"></th>
                </tr>
            </thead>
            <tbody id="itens-tbody">
                {{-- Itens existentes (edit) ou linha vazia (create) --}}
                @php
                    $itensExistentes = old('itens', $p?->itens?->toArray() ?? []);
                    if (empty($itensExistentes)) $itensExistentes = [[]]; // ao menos 1 linha
                @endphp

                @foreach($itensExistentes as $idx => $item)
                    <tr class="item-row border-t border-slate-100" data-index="{{ $idx }}">
                        <td class="px-3 py-2">
                            <input type="text"
                                   name="itens[{{ $idx }}][descricao]"
                                   placeholder="Descreva o serviço ou produto…"
                                   value="{{ $item['descricao'] ?? '' }}"
                                   class="w-full px-3 py-1.5 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 item-descricao">
                        </td>
                        <td class="px-2 py-2">
                            <input type="number"
                                   name="itens[{{ $idx }}][quantidade]"
                                   value="{{ $item['quantidade'] ?? 1 }}"
                                   min="0" step="0.01"
                                   class="w-full px-2 py-1.5 text-sm border border-slate-200 rounded-lg bg-white text-center focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 item-qtd">
                        </td>
                        <td class="px-2 py-2">
                            <input type="number"
                                   name="itens[{{ $idx }}][valor_unitario]"
                                   value="{{ $item['valor_unitario'] ?? '' }}"
                                   min="0" step="0.01"
                                   placeholder="0,00"
                                   class="w-full px-2 py-1.5 text-sm border border-slate-200 rounded-lg bg-white text-right focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 item-unit">
                        </td>
                        <td class="px-3 py-2 text-right">
                            <span class="item-total font-semibold text-slate-800">
                                @php
                                    $t = (float)($item['valor_total'] ?? (($item['quantidade'] ?? 1) * ($item['valor_unitario'] ?? 0)));
                                @endphp
                                R$ {{ number_format($t, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-2 py-2 text-center">
                            <button type="button"
                                    class="btn-remove-item text-slate-300 hover:text-red-500 transition-colors"
                                    title="Remover item">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-slate-50 border-t-2 border-slate-200">
                    <td colspan="3" class="px-4 py-3 text-sm font-semibold text-slate-600 text-right">Total Geral:</td>
                    <td class="px-3 py-3 text-right">
                        <span id="total-geral" class="text-base font-bold text-slate-900">R$ 0,00</span>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    {{-- Campo hidden para o valor_total real (calculado) --}}
    <input type="hidden" name="valor_total" id="valor_total_hidden" value="{{ $p?->valor_total ?? 0 }}">
</div>

{{-- ===== Scripts Quill + Items JS ===== --}}
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
(function () {
    // -------- QUILL EDITOR --------
    const quill = new Quill('#quill-editor', {
        modules: { toolbar: '#quill-toolbar' },
        theme: 'snow',
    });

    // Sinc Quill → hidden input antes de enviar o form
    const form = document.querySelector('form');
    form.addEventListener('submit', function () {
        document.getElementById('conteudo-hidden').value = quill.root.innerHTML;
    });

    // -------- ITEMS TABLE --------
    const tbody   = document.getElementById('itens-tbody');
    const totalEl = document.getElementById('total-geral');
    const hiddenTotal = document.getElementById('valor_total_hidden');

    function fmt(n) {
        return 'R$ ' + parseFloat(n || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function calcRow(row) {
        const qtd  = parseFloat(row.querySelector('.item-qtd').value)  || 0;
        const unit = parseFloat(row.querySelector('.item-unit').value) || 0;
        const total = (qtd * unit);
        row.querySelector('.item-total').textContent = fmt(total);
        return total;
    }

    function calcAll() {
        let grand = 0;
        document.querySelectorAll('#itens-tbody .item-row').forEach(r => grand += calcRow(r));
        totalEl.textContent = fmt(grand);
        hiddenTotal.value   = grand.toFixed(2);
    }

    function reindexRows() {
        document.querySelectorAll('#itens-tbody .item-row').forEach((row, idx) => {
            row.dataset.index = idx;
            row.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/itens\[\d+\]/, `itens[${idx}]`);
            });
        });
    }

    function newRowHtml(idx) {
        return `<tr class="item-row border-t border-slate-100" data-index="${idx}">
            <td class="px-3 py-2">
                <input type="text" name="itens[${idx}][descricao]" placeholder="Descreva o serviço ou produto…"
                       class="w-full px-3 py-1.5 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 item-descricao">
            </td>
            <td class="px-2 py-2">
                <input type="number" name="itens[${idx}][quantidade]" value="1" min="0" step="0.01"
                       class="w-full px-2 py-1.5 text-sm border border-slate-200 rounded-lg bg-white text-center focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 item-qtd">
            </td>
            <td class="px-2 py-2">
                <input type="number" name="itens[${idx}][valor_unitario]" min="0" step="0.01" placeholder="0,00"
                       class="w-full px-2 py-1.5 text-sm border border-slate-200 rounded-lg bg-white text-right focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 item-unit">
            </td>
            <td class="px-3 py-2 text-right">
                <span class="item-total font-semibold text-slate-800">R$ 0,00</span>
            </td>
            <td class="px-2 py-2 text-center">
                <button type="button" class="btn-remove-item text-slate-300 hover:text-red-500 transition-colors" title="Remover item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </td>
        </tr>`;
    }

    // Adicionar item
    document.getElementById('btn-add-item').addEventListener('click', function () {
        const idx = document.querySelectorAll('#itens-tbody .item-row').length;
        tbody.insertAdjacentHTML('beforeend', newRowHtml(idx));
        calcAll();
    });

    // Remover item (delegação)
    tbody.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-remove-item');
        if (!btn) return;
        const row = btn.closest('.item-row');
        // Mantém ao menos 1 linha
        if (document.querySelectorAll('#itens-tbody .item-row').length <= 1) {
            row.querySelector('.item-descricao').value = '';
            row.querySelector('.item-qtd').value = '1';
            row.querySelector('.item-unit').value = '';
            calcAll();
            return;
        }
        row.remove();
        reindexRows();
        calcAll();
    });

    // Calcular ao digitar
    tbody.addEventListener('input', function (e) {
        if (e.target.matches('.item-qtd, .item-unit')) {
            calcAll();
        }
    });

    // Calcular ao carregar (modo edição)
    calcAll();
})();
</script>
