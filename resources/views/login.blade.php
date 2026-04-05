<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LG Electronics — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <style>
        :root { --lg-red:#A50034; }
        body { background: #0d0d0f; color: #f0f0f0; }
    </style>
</head>
<body>

<div style="height:4px; background:linear-gradient(90deg,#A50034 0%,#d4003f 55%,#ff4060 100%);"></div>

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-[#161618] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <img src="{{ asset('logo-lg-100-44.svg') }}" alt="LG Electronics" style="height:44px;width:auto;" />
            <div>
                <div class="font-semibold">LG Electronics</div>
                <div class="text-xs text-white/60">Planta A · Manaus</div>
            </div>
        </div>

        <h1 class="text-xl font-semibold mb-1">Login</h1>
        <p class="text-sm text-white/60 mb-5">Use as credenciais de teste para entrar.</p>

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs mb-1 text-white/70">Email</label>
                <input
                    name="email"
                    type="email"
                    class="w-full rounded-lg bg-[#1e1e21] border border-white/10 px-3 py-2 outline-none focus:border-[#A50034]"
                    value="{{ old('email', 'test@test.com') }}"
                    autocomplete="off"
                />
                @error('email')
                    <div class="text-xs text-red-400 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-xs mb-1 text-white/70">Senha</label>
                <input
                    name="password"
                    type="password"
                    class="w-full rounded-lg bg-[#1e1e21] border border-white/10 px-3 py-2 outline-none focus:border-[#A50034]"
                    value="test123"
                    autocomplete="off"
                />
            </div>

            <button type="submit" class="w-full rounded-lg bg-[#A50034] hover:bg-[#8f002c] transition px-4 py-2 font-semibold">
                Entrar
            </button>

            <div class="text-xs text-white/60">
                Teste:
                <span class="font-mono">test@test.com</span>
                /
                <span class="font-mono">test123</span>
            </div>
        </form>
    </div>
</div>

</body>
</html>
