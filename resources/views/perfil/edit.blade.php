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

            <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-4" id="form-perfil">
                @csrf
                @method('PUT')

                <div class="flex items-start gap-5 flex-wrap">

                    {{-- Resultado atual: some quando entra a moldura de ajuste --}}
                    <div id="caixa-atual" class="flex flex-col items-center gap-2">
                        <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0 bg-brand-50 border border-slate-200 grid place-items-center">
                            @if ($user->avatar)
                                <img id="previa" src="{{ route('perfil.avatar') }}?v={{ $user->updated_at?->timestamp }}"
                                     alt="Foto do perfil" class="w-full h-full object-cover">
                            @else
                                <img id="previa" src="" alt="" class="w-full h-full object-cover hidden">
                                <span id="iniciais" class="text-2xl font-bold text-brand-600">{{ $iniciais }}</span>
                            @endif
                        </div>
                        <span class="text-[11px] text-slate-400">como aparece hoje</span>
                    </div>

                    {{-- Moldura de ajuste: só aparece depois de escolher uma foto --}}
                    <div id="ajuste" class="hidden w-full sm:w-auto">
                        <div class="flex items-center gap-5 flex-wrap">
                            <div id="moldura"
                                 class="relative w-40 h-40 rounded-full overflow-hidden bg-slate-900 flex-shrink-0 cursor-move touch-none ring-4 ring-slate-100">
                                <img id="alvo" alt="" class="absolute select-none pointer-events-none" style="transform-origin: top left;">
                                {{-- Linhas de referência, só estética --}}
                                <div class="absolute inset-0 pointer-events-none opacity-25"
                                     style="background-image:linear-gradient(rgba(255,255,255,.4) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.4) 1px, transparent 1px); background-size:33.33% 33.33%; background-position:-1px -1px;"></div>
                            </div>

                            <div class="flex-1 min-w-[190px]">
                                <label for="zoom" class="block text-sm font-medium text-slate-700 mb-1.5">Zoom</label>
                                <input type="range" id="zoom" min="1" max="4" step="0.01" value="1"
                                       class="w-full accent-brand-600">
                                <p class="text-xs text-slate-400 mt-1">Arraste a foto para posicionar. O círculo é o que vai aparecer.</p>
                                <button type="button" id="cancelar-ajuste"
                                        class="mt-3 text-xs font-semibold text-slate-400 hover:text-red-600 transition-colors">
                                    Cancelar ajuste
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 pt-2">
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
    /* Ajuste da foto na moldura, em JS puro (sem biblioteca externa, como o
       ui-avatars que este painel usava).

       Ao escolher o arquivo, a foto entra num círculo do tamanho em que vai
       aparecer. O usuário arrasta e dá zoom, e o recorte é desenhado num
       canvas no envio. Se qualquer passo falhar, o arquivo original segue no
       formulário e o servidor quadra a imagem no centro - por isso nunca
       sobra um avatar vazio. */
    (function () {
        var entrada    = document.getElementById('avatar');
        var alvo       = document.getElementById('alvo');
        var moldura    = document.getElementById('moldura');
        var zoom       = document.getElementById('zoom');
        var ajuste     = document.getElementById('ajuste');
        var caixaAtual = document.getElementById('caixa-atual');
        var cancelar   = document.getElementById('cancelar-ajuste');
        var form       = document.getElementById('form-perfil');

        if (!entrada || !alvo || !moldura || !form) return;

        var F     = 160;   // lado da moldura em px (classe w-40)
        var LADO  = 400;   // lado da imagem final gravada
        var base  = 1;     // escala que faz a foto cobrir a moldura
        var esc   = 1;     // escala atual
        var x = 0, y = 0;  // canto superior esquerdo da foto dentro da moldura
        var pronto = false;

        function medidas() {
            return { dw: alvo.naturalWidth * esc, dh: alvo.naturalHeight * esc };
        }

        /* Impede que apareça fundo: a foto nunca sai de dentro da moldura. */
        function limitar() {
            var m = medidas();
            x = Math.min(0, Math.max(F - m.dw, x));
            y = Math.min(0, Math.max(F - m.dh, y));
        }

        function desenhar() {
            var m = medidas();
            alvo.style.width  = m.dw + 'px';
            alvo.style.height = m.dh + 'px';
            alvo.style.left   = x + 'px';
            alvo.style.top    = y + 'px';
        }

        /* Centraliza mantendo o ponto que está no meio da moldura. */
        function centralizar(anterior) {
            if (!anterior) {
                var m = medidas();
                x = (F - m.dw) / 2;
                y = (F - m.dh) / 2;
            } else {
                var cx = (F / 2 - x) / anterior;
                var cy = (F / 2 - y) / anterior;
                var m2 = medidas();
                x = F / 2 - cx * esc;
                y = F / 2 - cy * esc;
            }
            limitar();
            desenhar();
        }

        /* Escolha do arquivo: carrega e monta a moldura. */
        entrada.addEventListener('change', function (e) {
            var arquivo = e.target.files && e.target.files[0];
            if (!arquivo) return;

            var leitor = new FileReader();
            leitor.onload = function (ev) {
                alvo.onload = function () {
                    // Escala que faz a foto PREENCHER o círculo (cover)
                    base = Math.max(F / alvo.naturalWidth, F / alvo.naturalHeight);
                    esc  = base;
                    zoom.value = 1;
                    pronto = true;
                    centralizar(false);
                    ajuste.classList.remove('hidden');
                    caixaAtual.classList.add('hidden');
                };
                alvo.onerror = function () {
                    // Imagem inválida: segue sem ajuste, o servidor decide
                    pronto = false;
                };
                alvo.src = ev.target.result;
            };
            leitor.readAsDataURL(arquivo);
        });

        /* Zoom */
        zoom.addEventListener('input', function () {
            if (!pronto) return;
            var anterior = esc;
            esc = base * parseFloat(zoom.value);
            centralizar(anterior);
        });

        /* Arrastar */
        var arrastando = false, px = 0, py = 0;
        moldura.addEventListener('pointerdown', function (e) {
            if (!pronto) return;
            arrastando = true;
            px = e.clientX;
            py = e.clientY;
            moldura.setPointerCapture(e.pointerId);
            e.preventDefault();
        });
        moldura.addEventListener('pointermove', function (e) {
            if (!arrastando) return;
            x += e.clientX - px;
            y += e.clientY - py;
            px = e.clientX;
            py = e.clientY;
            limitar();
            desenhar();
        });
        ['pointerup', 'pointercancel'].forEach(function (t) {
            moldura.addEventListener(t, function (e) {
                arrastando = false;
                if (moldura.hasPointerCapture && moldura.hasPointerCapture(e.pointerId)) {
                    moldura.releasePointerCapture(e.pointerId);
                }
            });
        });

        /* Cancelar: volta ao que está salvo */
        cancelar.addEventListener('click', function () {
            pronto = false;
            entrada.value = '';
            ajuste.classList.add('hidden');
            caixaAtual.classList.remove('hidden');
        });

        /* No envio, desenha o recorte escolhido e manda ele no lugar do arquivo */
        form.addEventListener('submit', function (e) {
            if (!pronto || !alvo.naturalWidth) return;

            e.preventDefault();

            var tela = document.createElement('canvas');
            tela.width = LADO;
            tela.height = LADO;
            var ctx = tela.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, LADO, LADO);

            var lado = F / esc;   // lado do recorte, em pixels da foto original
            ctx.drawImage(alvo, -x / esc, -y / esc, lado, lado, 0, 0, LADO, LADO);

            var seguir = function () { form.submit(); };

            if (tela.toBlob) {
                tela.toBlob(function (blob) {
                    try {
                        var dt = new DataTransfer();
                        dt.items.add(new File([blob], 'avatar.jpg', { type: 'image/jpeg' }));
                        entrada.files = dt.files;
                    } catch (err) {
                        // Sem DataTransfer: manda a original, o servidor quadra
                    }
                    seguir();
                }, 'image/jpeg', 0.9);
            } else {
                seguir();
            }
        });
    })();
    </script>
</x-layouts.app>
