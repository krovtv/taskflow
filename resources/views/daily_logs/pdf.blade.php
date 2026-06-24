<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Notas Diárias</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1a1a2e; }
        h1 { font-size: 20px; color: #1ec2cf; margin-bottom: 4px; }
        h2 { font-size: 14px; color: #1a1a2e; margin-top: 20px; margin-bottom: 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; }
        .meta { font-size: 10px; color: #94a3b8; margin-bottom: 20px; }
        .entry { margin-bottom: 6px; }
        .content { font-size: 11px; line-height: 1.5; color: #334155; white-space: pre-wrap; }
        .empty { color: #94a3b8; font-style: italic; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #94a3b8; padding: 10px; border-top: 1px solid #e2e8f0; }
        @page { margin: 30px 25px; }
    </style>
</head>
<body>
    <h1>Notas Diárias</h1>
    <p class="meta">
        Gerado em {{ now()->format('d/m/Y H:i') }}
        @if($from) · De {{ $from->format('d/m/Y') }} @endif
        @if($to) · Até {{ $to->format('d/m/Y') }} @endif
    </p>

    @forelse($logs as $log)
        <h2>{{ $log->date->format('d/m/Y') }} — {{ $log->date->translatedFormat('l') }}</h2>
        <div class="entry">
            <p class="content">{{ $log->content }}</p>
        </div>
    @empty
        <p class="empty">Nenhum registro encontrado.</p>
    @endforelse

    <div class="footer">KV Tech Organizer — Notas Diárias</div>
</body>
</html>
