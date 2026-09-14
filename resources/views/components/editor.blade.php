{{--
    Editor de texto rico (Quill), reutilizável.

    Usado no formulário da proposta e nos modelos. Existe para haver UM editor
    só: dois setups de Quill significariam dois lugares para consertar quando
    algo quebrasse — e o envio do conteúdo para o campo oculto já quebrou uma
    vez desta forma.

    Parâmetros:
      id     identificador único nesta página (se houver mais de um editor,
             cada um precisa do seu, senão o Quill do segundo pega o do primeiro)
      name   nome do campo enviado no formulário
      value  conteúdo inicial (HTML)
      label  rótulo acima do editor (opcional)
      hint   texto de apoio abaixo do rótulo (opcional)
--}}
@props([
    'id'    => 'editor',
    'name'  => 'conteudo',
    'value' => null,
    'label' => null,
    'hint'  => null,
])

@php
    $conteudoInicial = old($name, $value);
@endphp

<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<div class="space-y-1.5">
    @if($label)
        <label class="text-sm font-semibold text-slate-700">{{ $label }}</label>
    @endif
    @if($hint)
        <p class="text-xs text-slate-400">{{ $hint }}</p>
    @endif

    <div id="{{ $id }}-toolbar" class="border border-slate-200 rounded-t-xl bg-slate-50 px-2 py-1 flex flex-wrap gap-1">
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

    <div id="{{ $id }}-editor"
         class="border border-t-0 border-slate-200 rounded-b-xl bg-white min-h-[200px] px-4 py-3 text-sm text-slate-700 focus:outline-none"
         style="min-height:200px">{!! $conteudoInicial !!}</div>

    <input type="hidden" id="{{ $id }}-hidden" name="{{ $name }}" value="{{ $conteudoInicial }}">
</div>

<script>
(function () {
    const editorEl = document.getElementById(@json($id . '-editor'));
    const hidden   = document.getElementById(@json($id . '-hidden'));

    if (! editorEl || ! hidden || typeof Quill === 'undefined') return;

    const quill = new Quill(editorEl, {
        modules: { toolbar: @json('#' . $id . '-toolbar') },
        theme: 'snow',
    });

    // Sincroniza o texto do editor com o campo oculto.
    //
    // Partimos do PRÓPRIO campo e subimos com closest('form'). Nunca usar
    // document.querySelector('form'): ele devolve o primeiro formulário da
    // página, e o primeiro é o botão de sair, da barra lateral. Foi assim que
    // o conteúdo da proposta deixou de salvar.
    function sincronizar() {
        hidden.value = quill.root.innerHTML;
    }

    quill.on('text-change', sincronizar);

    const form = hidden.closest('form');
    if (form) {
        form.addEventListener('submit', sincronizar);
    }

    sincronizar();

    // Exposto para quem precisar mexer no editor de fora (ex.: inserir modelo)
    window['editor_' + @json($id)] = { quill, sincronizar };
})();
</script>
