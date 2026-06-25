<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Tarefas</title>
    <style>
        @page { margin: 20mm 18mm 22mm; }
        body { font-family: 'DejaVu Serif', serif; color: #1e293b; line-height: 1.6; font-size: 10.5pt; }
        .header { text-align: center; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 3px solid #1ec2cf; }
        .header .logo-img { max-width: 150px; max-height: 65px; margin-bottom: 10px; }
        .header h1 { color: #070821; font-size: 18pt; margin: 0 0 4px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { color: #64748b; font-size: 8pt; margin: 0; font-family: 'DejaVu Sans', sans-serif; }

        .section-title { background: #070821; color: #fff; padding: 6px 14px; font-size: 9pt; font-weight: 700; text-transform: uppercase; margin: 22px 0 10px; border-radius: 3px; letter-spacing: 0.8px; font-family: 'DejaVu Sans', sans-serif; }
        .stats-grid { width: 100%; margin-bottom: 16px; border-collapse: collapse; }
        .stats-grid td { width: 20%; text-align: center; padding: 9px 5px; border: 1px solid #e2e8f0; font-family: 'DejaVu Sans', sans-serif; }
        .stats-grid td:first-child { border-left: 3px solid #1ec2cf; }
        .stats-grid .num { font-size: 16pt; font-weight: 800; color: #070821; display: block; }
        .stats-grid .lab { font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }

        table.tasks { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-top: 8px; }
        table.tasks th { background: #f8fafc; color: #475569; font-size: 7pt; text-transform: uppercase; letter-spacing: 0.8px; padding: 7px 9px; text-align: left; border-bottom: 2px solid #e2e8f0; font-family: 'DejaVu Sans', sans-serif; }
        table.tasks td { padding: 6px 9px; border-bottom: 1px solid #f1f5f9; }
        table.tasks tr:nth-child(even) td { background: #fafafa; }

        .badge { display: inline-block; padding: 2px 7px; border-radius: 3px; font-size: 7pt; font-weight: 700; font-family: 'DejaVu Sans', sans-serif; }
        .badge-urgente { background: #fef2f2; color: #dc2626; }
        .badge-alta { background: #fffbeb; color: #d97706; }
        .badge-media { background: #eff6ff; color: #2563eb; }
        .badge-baixa { background: #f1f5f9; color: #64748b; }
        .badge-atrasada { background: #fef2f2; color: #dc2626; }
        .badge-pendente { background: #f1f5f9; color: #64748b; }
        .badge-em_andamento { background: #fffbeb; color: #d97706; }
        .badge-concluido { background: #f0fdf4; color: #16a34a; }

        .cat-section { margin-bottom: 18px; }
        .cat-section h3 { font-size: 10pt; color: #070821; border-left: 3px solid #1ec2cf; padding-left: 10px; margin: 0 0 5px; font-family: 'DejaVu Sans', sans-serif; }
        .cat-section .summary { font-size: 8pt; color: #64748b; margin-bottom: 4px; }
        .progress-bar { background: #e2e8f0; height: 5px; border-radius: 3px; margin: 3px 0; }
        .progress-bar .fill { background: #1ec2cf; height: 5px; border-radius: 3px; }

        .footer { position: fixed; bottom: -18mm; left: 0; right: 0; text-align: center; font-size: 7pt; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 4px; font-family: 'DejaVu Sans', sans-serif; }
        .footer strong { color: #1ec2cf; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="header">
        @php $logoPath = public_path('images/logo.svg'); @endphp
        @if(file_exists($logoPath))
            <img src="{{ $logoPath }}" alt="KV Tech" class="logo-img">
        @else
            <div style="font-size:20pt;font-weight:900;color:#070821;margin-bottom:4px;">KV <span style="color:#1ec2cf;">TECH</span></div>
        @endif
        <h1>Relatório de Tarefas</h1>
        <p>Gerado em {{ $data }} por {{ $nome }}</p>
    </div>

    {{-- ESTATÍSTICAS GLOBAIS --}}
    <table class="stats-grid" cellspacing="0">
        <tr>
            <td><span class="num">{{ $stats['total'] }}</span><span class="lab">Total</span></td>
            <td><span class="num">{{ $stats['pendentes'] }}</span><span class="lab">Pendentes</span></td>
            <td><span class="num">{{ $stats['andamento'] }}</span><span class="lab">Em andamento</span></td>
            <td><span class="num">{{ $stats['concluidas'] }}</span><span class="lab">Concluídas</span></td>
            <td><span class="num">{{ $stats['atrasadas'] }}</span><span class="lab">Atrasadas</span></td>
        </tr>
    </table>

    {{-- POR CATEGORIA --}}
    @if(count($porCategoria))
        <div class="section-title">Por Categoria</div>
        @foreach($porCategoria as $cat)
            @if($cat['total'] > 0)
                <div class="cat-section">
                    <h3>{{ $cat['label'] }}</h3>
                    <div class="summary">{{ $cat['concluidas'] }}/{{ $cat['total'] }} concluídas ({{ $cat['total'] > 0 ? round(($cat['concluidas']/$cat['total'])*100) : 0 }}%)</div>
                    <div class="progress-bar"><div class="fill" style="width: {{ $cat['total'] > 0 ? ($cat['concluidas']/$cat['total'])*100 : 0 }}%"></div></div>
                </div>
            @endif
        @endforeach
    @endif

    {{-- LISTA DE TAREFAS --}}
    @if(count($tasks))
        <div class="section-title">Lista de Tarefas</div>
        <table class="tasks" cellspacing="0">
            <thead>
                <tr>
                    <th>Tarefa</th>
                    <th>Categoria</th>
                    <th>Prioridade</th>
                    <th>Prazo</th>
                    <th>Status</th>
                    <th>Progresso</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>
                            <strong>{{ $task->title }}</strong>
                            @if($task->description)
                                <br><span style="color:#94a3b8;font-size:7pt;">{{ \Illuminate\Support\Str::limit($task->description, 60) }}</span>
                            @endif
                        </td>
                        <td>{{ $task->category_label }}</td>
                        <td><span class="badge badge-{{ $task->priority }}">{{ $task->priority_label }}</span></td>
                        <td style="white-space:nowrap;">{{ $task->due_date->format('d/m/Y H:i') }}</td>
                        <td><span class="badge {{ $task->isOverdue() ? 'badge-atrasada' : 'badge-' . $task->status }}">{{ $task->status_label }}</span></td>
                        <td style="white-space:nowrap;">
                            @if($task->progress !== null) {{ $task->progress }}% @else — @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align:center;color:#94a3b8;margin-top:30px;">Nenhuma tarefa encontrada para os filtros aplicados.</p>
    @endif

    <div class="footer"><strong>KV TECH</strong> Organizer — Relatório gerado automaticamente</div>
</body>
</html>