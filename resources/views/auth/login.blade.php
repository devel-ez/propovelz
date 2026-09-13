<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Entrar — Painel administrativo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] } } }
        }
    </script>
</head>
<body class="min-h-screen bg-[#F3F4F6] font-sans text-slate-800 antialiased flex items-center justify-center p-5">

    <main class="w-full max-w-[400px]">

        <!-- Marca -->
        <div class="flex items-center justify-center gap-2.5 mb-7">
            <span class="w-10 h-10 rounded-xl bg-[#2563EB] text-white grid place-items-center">
                <svg class="w-6 h-6" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                    <path d="M6 12.5 16 6l10 6.5v2.2L16 8.2 6 14.7v-2.2Z"/>
                    <path d="M6 19.4 16 12.9l10 6.5v2.2L16 15.1 6 21.6v-2.2Z" opacity=".6"/>
                    <path d="M6 25.8 16 19.3l10 6.5V28L16 21.5 6 28v-2.2Z" opacity=".35"/>
                </svg>
            </span>
            <span class="text-[1.05rem] font-semibold tracking-tight text-slate-800">
                Painel <strong class="font-extrabold">administrativo</strong>
            </span>
        </div>

        <!-- Card -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-[0_4px_16px_rgba(15,23,42,.07)] p-8">

            <h1 class="text-lg font-bold text-slate-800 mb-1">Acesso restrito</h1>
            <p class="text-sm text-slate-500 mb-6">Entre com suas credenciais para continuar.</p>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                    @foreach ($errors->all() as $erro)
                        <p class="text-sm font-medium text-red-700">{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">E-mail</label>
                    <input type="email" name="email" id="email" required autofocus autocomplete="username"
                           value="{{ old('email') }}"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm
                                  outline-none transition focus:bg-white focus:border-[#2563EB]
                                  focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Senha</label>
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm
                                  outline-none transition focus:bg-white focus:border-[#2563EB]
                                  focus:ring-2 focus:ring-blue-100">
                </div>

                <label class="flex items-center gap-2.5 text-sm text-slate-600 cursor-pointer select-none">
                    <input type="checkbox" name="remember" value="1"
                           class="w-4 h-4 rounded border-slate-300 text-[#2563EB] focus:ring-blue-200">
                    Continuar conectado neste dispositivo
                </label>

                <button type="submit"
                        class="w-full rounded-xl bg-[#2563EB] px-4 py-3 text-sm font-bold text-white
                               transition hover:bg-[#1D4ED8] focus:outline-none focus:ring-2
                               focus:ring-blue-200 focus:ring-offset-2">
                    Entrar
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            Acesso monitorado. Este painel guarda dados de clientes.
        </p>
    </main>

</body>
</html>
