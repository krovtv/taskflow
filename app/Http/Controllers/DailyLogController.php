<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyLogController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->today();
        $log = DailyLog::firstOrCreate(
            ['user_id' => $request->user()->id, 'date' => $today],
            ['content' => '']
        );

        $recentLogs = DailyLog::where('user_id', $request->user()->id)
            ->where('date', '<', $today)
            ->latest('date')
            ->take(14)
            ->get();

        return view('daily_logs.index', compact('log', 'recentLogs'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'content' => 'nullable|string',
        ]);

        $log = DailyLog::updateOrCreate(
            ['user_id' => $request->user()->id, 'date' => Carbon::parse($data['date'])],
            ['content' => $data['content'] ?? '']
        );

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'log' => $log]);
        }

        return redirect()->route('daily-log.date', $data['date'])->with('success', 'Nota salva!');
    }

    public function showDate(Request $request, string $date)
    {
        $date = Carbon::parse($date);
        $log = DailyLog::firstOrNew(
            ['user_id' => $request->user()->id, 'date' => $date],
            ['content' => '']
        );

        return view('daily_logs.show', compact('log', 'date'));
    }

    public function exportTxt(Request $request)
    {
        $from = $request->query('from') ? Carbon::parse($request->query('from')) : null;
        $to = $request->query('to') ? Carbon::parse($request->query('to')) : null;

        $logs = DailyLog::where('user_id', $request->user()->id)
            ->where('content', '!=', '')
            ->when($from, fn ($q) => $q->where('date', '>=', $from))
            ->when($to, fn ($q) => $q->where('date', '<=', $to))
            ->latest('date')
            ->get();

        $lines = [];
        $lines[] = '=== DIÁRIO DE BORDO ===';
        $lines[] = 'Gerado em: ' . now()->format('d/m/Y H:i');
        $lines[] = str_repeat('=', 50);
        $lines[] = '';

        foreach ($logs as $log) {
            $lines[] = '--- ' . $log->date->format('d/m/Y') . ' (' . $log->date->translatedFormat('l') . ') ---';
            $lines[] = '';
            $lines[] = $log->content;
            $lines[] = '';
            $lines[] = str_repeat('-', 40);
            $lines[] = '';
        }

        if (empty($logs)) {
            $lines[] = 'Nenhum registro encontrado.';
        }

        $content = implode("\n", $lines);

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="diario-' . now()->format('Y-m-d') . '.txt"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return back()->with('error', 'PDF requer o pacote barryvdh/laravel-dompdf. Execute: composer require barryvdh/laravel-dompdf');
        }

        $from = $request->query('from') ? Carbon::parse($request->query('from')) : null;
        $to = $request->query('to') ? Carbon::parse($request->query('to')) : null;

        $logs = DailyLog::where('user_id', $request->user()->id)
            ->where('content', '!=', '')
            ->when($from, fn ($q) => $q->where('date', '>=', $from))
            ->when($to, fn ($q) => $q->where('date', '<=', $to))
            ->latest('date')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('daily_logs.pdf', compact('logs', 'from', 'to'));

        return $pdf->download('diario-' . now()->format('Y-m-d') . '.pdf');
    }
}
