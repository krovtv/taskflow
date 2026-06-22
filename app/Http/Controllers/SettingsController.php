<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function telegram(Request $request, TelegramService $telegram): View
    {
        $botInfo = $telegram->getMe();

        return view('settings.telegram', [
            'user' => $request->user(),
            'botInfo' => $botInfo,
        ]);
    }

    public function telegramUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'telegram_chat_id' => ['nullable', 'string', 'max:50'],
        ]);

        $request->user()->update([
            'telegram_chat_id' => $request->input('telegram_chat_id') ?: null,
            'telegram_active_at' => $request->input('telegram_chat_id') ? now() : null,
        ]);

        return back()->with('success', 'Configuração do Telegram salva!');
    }

    public function telegramDisconnect(Request $request): RedirectResponse
    {
        $request->user()->update([
            'telegram_chat_id' => null,
            'telegram_active_at' => null,
        ]);

        return back()->with('success', 'Telegram desconectado.');
    }

    public function telegramTest(Request $request, TelegramService $telegram): RedirectResponse
    {
        $user = $request->user();

        if (! $user->telegram_chat_id) {
            return back()->withErrors(['telegram' => 'Nenhum chat ID configurado.']);
        }

        $sent = $telegram->sendMessage(
            $user->telegram_chat_id,
            "✅ <b>KV Tech Organizer</b>\n\nNotificação de teste funcionando perfeitamente!\n\nVocê receberá lembretes de tarefas próximas do prazo aqui."
        );

        if ($sent) {
            return back()->with('success', 'Mensagem de teste enviada com sucesso!');
        }

        return back()->withErrors(['telegram' => 'Falha ao enviar mensagem. Verifique o Chat ID e se o bot foi iniciado.']);
    }
}
