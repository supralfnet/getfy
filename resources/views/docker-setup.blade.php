<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuração inicial (Docker) - Getfy</title>
    <style>
        :root { color-scheme: dark; }
        body { margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Liberation Sans", sans-serif; background:#0b0b0f; color:#e4e4e7; }
        .wrap { max-width: 720px; margin: 0 auto; padding: 48px 20px; }
        .card { background:#111827; border:1px solid #1f2937; border-radius:14px; padding: 22px; }
        h1 { margin: 0 0 10px; font-size: 22px; }
        p { margin: 0 0 14px; color:#a1a1aa; line-height: 1.5; }
        label { display:block; font-size: 13px; color:#d4d4d8; margin: 14px 0 6px; }
        input, select { width:100%; padding: 10px 12px; border-radius: 10px; border:1px solid #374151; background:#0b1220; color:#e4e4e7; outline: none; }
        input:focus, select:focus { border-color:#22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.18); }
        .row { display:flex; gap: 12px; }
        .row > div { flex:1; }
        .btn { margin-top: 18px; width:100%; border:0; border-radius: 10px; padding: 12px 14px; background:#22c55e; color:#052e16; font-weight: 700; cursor:pointer; }
        .hint { margin-top: 12px; font-size: 12px; color:#9ca3af; }
        .error { margin-top: 10px; padding: 10px 12px; border-radius: 10px; background:#3f1d1d; border:1px solid #7f1d1d; color:#fecaca; }
        .ok { margin-top: 10px; padding: 10px 12px; border-radius: 10px; background:#0f2a1d; border:1px solid #14532d; color:#bbf7d0; }
        code { background: rgba(255,255,255,0.06); padding: 2px 6px; border-radius: 6px; }
        .top { margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <h1>Configuração inicial (Docker)</h1>
            <p>Defina o domínio/URL pública para a plataforma gerar links corretos (checkout, webhooks, PWA, e-mails, etc.).</p>
        </div>

        <div class="card">
            @if (session('success'))
                <div class="ok">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form method="post" action="{{ url('/docker-setup') }}">
                @csrf

                <div class="row">
                    <div>
                        <label for="scheme">Protocolo</label>
                        <select id="scheme" name="scheme">
                            <option value="http" @selected(old('scheme', $scheme) === 'http')>http</option>
                            <option value="https" @selected(old('scheme', $scheme) === 'https')>https</option>
                        </select>
                    </div>
                    <div>
                        <label for="port">Porta (opcional)</label>
                        <input id="port" name="port" type="number" min="1" max="65535" value="{{ old('port') }}" placeholder="80 / 443 / 8080">
                    </div>
                </div>

                <label for="host">Domínio ou IP</label>
                <input id="host" name="host" type="text" value="{{ old('host', $host) }}" placeholder="ex: seudominio.com ou 187.77.46.197">

                <div class="hint">
                    Sugestão detectada: <code>{{ $suggested_url }}</code>
                </div>

                <button class="btn" type="submit">Salvar e continuar</button>

                <div class="hint">
                    Depois de salvar, você será levado ao login. Se for a primeira vez, o login redireciona para criação do admin.
                </div>
            </form>
        </div>
    </div>
</body>
</html>

