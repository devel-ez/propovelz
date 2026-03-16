<x-layouts.app>
    <div class="p-6 md:p-8 max-w-3xl">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('clientes.index') }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Editar Cliente</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $cliente->nome }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf
            @method('PUT')

            {{-- Dados básicos --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Dados do Cliente
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nome <span class="text-red-500">*</span></label>
                        <input type="text" name="nome" value="{{ old('nome', $cliente->nome) }}" required
                               placeholder="Nome completo ou razão social"
                               class="w-full px-3.5 py-2.5 text-sm bg-white border @error('nome') border-red-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
                        @error('nome') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $cliente->email) }}"
                               placeholder="email@exemplo.com"
                               class="w-full px-3.5 py-2.5 text-sm bg-white border @error('email') border-red-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone', $cliente->telefone) }}"
                               placeholder="(00) 00000-0000"
                               class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">CPF / CNPJ</label>
                        <input type="text" name="documento" value="{{ old('documento', $cliente->documento) }}"
                               placeholder="Opcional"
                               class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all">
                    </div>
                </div>
            </div>

            {{-- Acessos de Sistemas --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Acessos de Sistemas
                    </h2>
                    <button type="button" onclick="adicionarCredencial()"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 border border-blue-200 hover:border-blue-400 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Adicionar acesso
                    </button>
                </div>

                <div id="credenciais-lista" class="space-y-3">
                    @foreach($cliente->credenciais as $cred)
                        @php $idx = $loop->index; @endphp
                        <div class="credencial-item border border-slate-200 rounded-xl p-4 bg-slate-50/50">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Acesso {{ $loop->iteration }}</span>
                                <button type="button" onclick="removerCredencial(this)"
                                        class="text-slate-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" name="credenciais[{{ $idx }}][id]" value="{{ $cred->id }}">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Sistema *</label>
                                    <input type="text" name="credenciais[{{ $idx }}][sistema]" value="{{ $cred->sistema }}" required
                                           placeholder="Ex: WordPress, cPanel..."
                                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Login / Usuário</label>
                                    <input type="text" name="credenciais[{{ $idx }}][login]" value="{{ $cred->login }}"
                                           placeholder="usuario@email.com"
                                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                                        Senha
                                        <span class="font-normal text-slate-400">(deixe em branco para manter)</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="credenciais[{{ $idx }}][senha]" value=""
                                               placeholder="••••••••"
                                               class="w-full px-3 py-2 pr-9 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white transition-all">
                                        <button type="button" onclick="toggleSenha(this)"
                                                class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-600">
                                            <svg class="w-4 h-4 eye-off" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                            <svg class="w-4 h-4 eye-on hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Observação</label>
                                    <input type="text" name="credenciais[{{ $idx }}][observacao]" value="{{ $cred->observacao }}"
                                           placeholder="URL de acesso, notas, etc."
                                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white transition-all">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($cliente->credenciais->isEmpty())
                    <p id="credenciais-empty" class="text-sm text-slate-400 text-center py-6 italic">
                        Nenhum acesso adicionado ainda.
                    </p>
                @else
                    <p id="credenciais-empty" class="hidden text-sm text-slate-400 text-center py-6 italic">
                        Nenhum acesso adicionado.
                    </p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <a href="{{ route('clientes.index') }}"
                   class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm shadow-blue-200 transition-all">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>

    <script>
        let credencialIndex = {{ $cliente->credenciais->count() }};

        function adicionarCredencial() {
            const lista = document.getElementById('credenciais-lista');
            const empty = document.getElementById('credenciais-empty');
            empty.classList.add('hidden');

            const idx = credencialIndex++;
            const div = document.createElement('div');
            div.className = 'credencial-item border border-slate-200 rounded-xl p-4 bg-slate-50/50';
            div.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Acesso</span>
                    <button type="button" onclick="removerCredencial(this)"
                            class="text-slate-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Sistema *</label>
                        <input type="text" name="credenciais[${idx}][sistema]" required
                               placeholder="Ex: WordPress, cPanel..."
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Login / Usuário</label>
                        <input type="text" name="credenciais[${idx}][login]"
                               placeholder="usuario@email.com"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Senha</label>
                        <div class="relative">
                            <input type="password" name="credenciais[${idx}][senha]"
                                   placeholder="••••••••"
                                   class="w-full px-3 py-2 pr-9 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white transition-all">
                            <button type="button" onclick="toggleSenha(this)"
                                    class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4 eye-off" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                <svg class="w-4 h-4 eye-on hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Observação</label>
                        <input type="text" name="credenciais[${idx}][observacao]"
                               placeholder="URL de acesso, notas, etc."
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white transition-all">
                    </div>
                </div>
            `;
            lista.appendChild(div);
            renumerarAcessos();
        }

        function removerCredencial(btn) {
            btn.closest('.credencial-item').remove();
            const lista = document.getElementById('credenciais-lista');
            const empty = document.getElementById('credenciais-empty');
            if (lista.children.length === 0) empty.classList.remove('hidden');
            renumerarAcessos();
        }

        function renumerarAcessos() {
            document.querySelectorAll('.credencial-item').forEach((el, i) => {
                const span = el.querySelector('span');
                if (span) span.textContent = `Acesso ${i + 1}`;
            });
        }

        function toggleSenha(btn) {
            const input = btn.parentElement.querySelector('input');
            const eyeOff = btn.querySelector('.eye-off');
            const eyeOn  = btn.querySelector('.eye-on');
            if (input.type === 'password') {
                input.type = 'text';
                eyeOff.classList.add('hidden');
                eyeOn.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOff.classList.remove('hidden');
                eyeOn.classList.add('hidden');
            }
        }
    </script>
</x-layouts.app>
