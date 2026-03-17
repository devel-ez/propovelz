<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 md:p-6 space-y-6">

    {{-- Nome --}}
    <div>
        <label for="nome" class="block text-sm font-semibold text-slate-700 mb-1.5">Nome do Projeto *</label>
        <input type="text" name="nome" id="nome" required
               value="{{ old('nome', $projeto->nome ?? '') }}"
               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm @error('nome') border-red-300 focus:ring-red-100 focus:border-red-400 @enderror">
        @error('nome')
            <p class="mt-1.5 text-sm text-red-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Cliente --}}
        <div>
            <label for="cliente_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Cliente Relacionado *</label>
            <select name="cliente_id" id="cliente_id" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm appearance-none @error('cliente_id') border-red-300 focus:ring-red-100 focus:border-red-400 @enderror"
                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2394a3b8%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.2em;">
                <option value="">Selecione um cliente...</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $projeto->cliente_id ?? '') == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nome }}
                    </option>
                @endforeach
            </select>
            @error('cliente_id')
                <p class="mt-1.5 text-sm text-red-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Status --}}
        <div>
            <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status *</label>
            <select name="status" id="status" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm appearance-none @error('status') border-red-300 focus:ring-red-100 focus:border-red-400 @enderror"
                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2394a3b8%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.2em;">
                @foreach(['Agendado', 'Andamento', 'Desenvolvimento', 'Concluido', 'Pausado', 'Cancelado'] as $st)
                    <option value="{{ $st }}" {{ old('status', $projeto->status ?? '') == $st ? 'selected' : '' }}>
                        {{ $st }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1.5 text-sm text-red-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    {{-- Descrição --}}
    <div>
        <label for="descricao" class="block text-sm font-semibold text-slate-700 mb-1.5">Descrição <span class="text-slate-400 font-normal">(Opcional)</span></label>
        <textarea name="descricao" id="descricao" rows="4"
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm resize-y @error('descricao') border-red-300 focus:ring-red-100 focus:border-red-400 @enderror">{{ old('descricao', $projeto->descricao ?? '') }}</textarea>
        @error('descricao')
            <p class="mt-1.5 text-sm text-red-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
        {{-- Hospedagem --}}
        <div>
            <label for="hospedagem_tipo" class="block text-sm font-semibold text-slate-700 mb-1.5">Tipo de Hospedagem</label>
            <select name="hospedagem_tipo" id="hospedagem_tipo"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm appearance-none @error('hospedagem_tipo') border-red-300 focus:ring-red-100 focus:border-red-400 @enderror"
                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2394a3b8%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.2em;">
                <option value="">Selecione...</option>
                <option value="Hospedagem Veltech" {{ old('hospedagem_tipo', $projeto->hospedagem_tipo ?? '') == 'Hospedagem Veltech' ? 'selected' : '' }}>Hospedagem Veltech</option>
                <option value="Hospedagem externa" {{ old('hospedagem_tipo', $projeto->hospedagem_tipo ?? '') == 'Hospedagem externa' ? 'selected' : '' }}>Hospedagem externa</option>
            </select>
        </div>

        <div>
            <label for="hospedagem_vigencia" class="block text-sm font-semibold text-slate-700 mb-1.5">Vigência da Hospedagem</label>
            <input type="date" name="hospedagem_vigencia" id="hospedagem_vigencia"
                   value="{{ old('hospedagem_vigencia', (!empty($projeto->hospedagem_vigencia)) ? \Carbon\Carbon::parse($projeto->hospedagem_vigencia)->format('Y-m-d') : '') }}"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm @error('hospedagem_vigencia') border-red-300 focus:ring-red-100 focus:border-red-400 @enderror">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pt-4 border-t border-slate-100" x-data="whoisConfig()">
        {{-- Domínio --}}
        <div class="md:col-span-5">
            <label for="dominio" class="block text-sm font-semibold text-slate-700 mb-1.5">Domínio</label>
            <input type="text" name="dominio" id="dominio" placeholder="exemplo.com.br"
                   x-model="domain"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm @error('dominio') border-red-300 focus:ring-red-100 focus:border-red-400 @enderror">
        </div>

        <div class="md:col-span-2 flex items-end">
            <button type="button" @click="consultarWhois" :disabled="loading || !domain"
                    class="w-full h-[46px] px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
                <span x-show="!loading">Consultar</span>
                <span x-show="loading" class="animate-spin inline-block w-4 h-4 border-2 border-slate-400 border-t-slate-700 rounded-full"></span>
            </button>
        </div>

        <div class="md:col-span-5">
            <label for="dominio_vigencia" class="block text-sm font-semibold text-slate-700 mb-1.5">Vigência do Domínio</label>
            <input type="date" name="dominio_vigencia" id="dominio_vigencia" x-model="vigencia"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm @error('dominio_vigencia') border-red-300 focus:ring-red-100 focus:border-red-400 @enderror">
        </div>

        <div class="md:col-span-12 -mt-4">
            <p x-show="errorMsg" x-text="errorMsg" class="text-xs text-red-600 font-medium"></p>
            <p x-show="successMsg" x-text="successMsg" class="text-xs text-emerald-600 font-medium"></p>
        </div>
    </div>

    <script>
        function whoisConfig() {
            return {
                domain: '{{ old('dominio', $projeto->dominio ?? '') }}',
                vigencia: '{{ old('dominio_vigencia', (!empty($projeto->dominio_vigencia)) ? \Carbon\Carbon::parse($projeto->dominio_vigencia)->format('Y-m-d') : '') }}',
                loading: false,
                errorMsg: '',
                successMsg: '',
                consultarWhois() {
                    if (!this.domain) return;
                    this.loading = true;
                    this.errorMsg = '';
                    this.successMsg = '';

                    fetch('{{ route('whois.consultar') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ domain: this.domain })
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) {
                            throw new Error(data.error || data.message || 'Erro na consulta');
                        }
                        return data;
                    })
                    .then(data => {
                        this.loading = false;
                        if (data.expires_at) {
                            this.vigencia = data.expires_at;
                            this.successMsg = 'Vigência atualizada com sucesso!';
                            setTimeout(() => this.successMsg = '', 4000);
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.errorMsg = err.message || 'Erro de conexão ao consultar domínio.';
                    });
                }
            }
        }
    </script>

    <div class="pt-4 border-t border-slate-100">
        {{-- Propostas Relacionadas (Opcional) --}}
        <label class="block text-sm font-semibold text-slate-700 mb-2">Propostas Relacionadas</label>
        @if($propostas->isEmpty())
            <p class="text-sm text-slate-500 italic">Nenhuma proposta cadastrada no sistema.</p>
        @else
            <div class="max-h-48 overflow-y-auto space-y-2 pr-2">
                @php
                    $selectedPropostas = old('propostas', isset($projeto) ? $projeto->propostas->pluck('id')->toArray() : []);
                @endphp
                @foreach($propostas as $proposta)
                    <label class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="propostas[]" value="{{ $proposta->id }}"
                                   {{ in_array($proposta->id, $selectedPropostas) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-slate-50 border-slate-300 rounded focus:ring-blue-500 focus:ring-2">
                        </div>
                        <div class="flex-1 text-sm">
                            <p class="font-medium text-slate-800">{{ $proposta->titulo }}</p>
                            @if($proposta->cliente)
                                <p class="text-slate-500 text-xs">Cliente: {{ $proposta->cliente->nome }}</p>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
            @error('propostas')
                <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
            @enderror
        @endif
    </div>

</div>
