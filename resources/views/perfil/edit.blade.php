<x-layouts.app>
    @php
        // Iniciais para quando não houver foto: pega a primeira letra das duas
        // primeiras palavras do nome.
        $partes   = preg_split('/\s+/', trim($user->name));
        $iniciais = mb_strtoupper(
            mb_substr($partes[0] ?? '', 0, 1) . mb_substr($partes[1] ?? '', 0, 1)
        );
    @endphp

    <div class="p-6 md:p-8 max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Perfil</h1>
            <p class="text-sm text-slate-500 mt-0.5">Seus dados de acesso ao painel</p>
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ---------------- Foto ---------------- --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm mb-6">
            <h2 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3 mb-5">Foto</h2>

            <div class="flex items-center gap-5 flex-wrap">
                {{-- Pré-visualização: mostra a foto atual e troca na hora ao escolher outra --}}
                <div class="w-20 h-20 rounded-2xl overflow-hidden flex-shrink-0 bg-brand-50 border border-slate-200 grid place-items-center">
                    @if ($user->avatar)
                        <img id="previa" src="{{ route('perfil.avatar') }}?v={{ $user->updated_at?->timestamp }}"
                             alt="Foto do perfil" class="w-full h-full object-cover">
                    @else
                        <img id="previa" src="" alt="" class="w-full h-full object-cover hidden">
                        <span id="iniciais" class="text-2xl font-bold text-brand-600">{{ $iniciais }}</span>
                    @endif
                </div>

                <div class="flex-1 min-w-[240px]">
                    <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        @method('PUT')

                        {{-- Campos de dados vêm aqui junto: um só botão de salvar --}}
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nome</label>
                                <input type="text" name="name" id="name" required maxlength="120"
                                       value="{{ old('name', $user->name) }}"
                                       class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">E-mail (login)</label>
                                <input type="email" name="email" id="email" required maxlength="180"
                                       value="{{ old('email', $user->email) }}"
                                       class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="senha_atual_perfil" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Senha atual
                                <span class="text-xs font-normal text-slate-400">— só é exigida se você mudar o e-mail</span>
                            </label>
                            <input type="password" name="senha_atual_perfil" id="senha_atual_perfil"
                                   autocomplete="current-password"
                                   class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                            @error('senha_atual_perfil') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="avatar" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Nova foto
                                <span class="text-xs font-normal text-slate-400">— JPG, PNG ou WebP, até 2 MB</span>
                            </label>
                            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp"
                                   class="w-full text-sm text-slate-600 file:mr-3 file:px-3.5 file:py-2 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:cursor-pointer">
                            @error('avatar') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-brand-200 transition-colors">
                            Salvar perfil
                        </button>
                    </form>

                    @if ($user->avatar)
                        <form method="POST" action="{{ route('perfil.avatar.remover') }}" class="mt-2"
                              onsubmit="return confirm('Remover a foto do perfil?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-red-600 transition-colors">
                                Remover foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- ---------------- Senha ---------------- --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3 mb-5">Trocar senha</h2>

            <form method="POST" action="{{ route('perfil.senha') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="senha_atual" class="block text-sm font-medium text-slate-700 mb-1.5">Senha atual</label>
                    <input type="password" name="senha_atual" id="senha_atual" required
                           autocomplete="current-password"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                    @error('senha_atual') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Nova senha</label>
                        <input type="password" name="password" id="password" required minlength="8"
                               autocomplete="new-password"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                        <p class="text-xs text-slate-400 mt-1">Mínimo de 8 caracteres.</p>
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Repita a nova senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                               autocomplete="new-password"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400 transition-all">
                    </div>
                </div>

                <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-700 flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ao trocar a senha, sua sessão atual continua aberta.
                </div>

                <button type="submit"
                        class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                    Trocar senha
                </button>
            </form>
        </div>
    </div>

    <script>
        // Mostra a foto escolhida antes de salvar, para não subir no escuro.
        document.getElementById('avatar')?.addEventListener('change', function (e) {
            var arquivo = e.target.files && e.target.files[0];
            if (!arquivo) return;
            var leitor = new FileReader();
            leitor.onload = function (ev) {
                var img = document.getElementById('previa');
                var ini = document.getElementById('iniciais');
                img.src = ev.target.result;
                img.classList.remove('hidden');
                if (ini) ini.classList.add('hidden');
            };
            leitor.readAsDataURL(arquivo);
        });
    </script>
</x-layouts.app>
