<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function webhook(Request $request, TelegramService $telegram): \Illuminate\Http\Response
    {
        $data = $request->all();
        $message = $data['message'] ?? [];

        if (!isset($message['text'], $message['chat']['id'])) {
            return response('OK');
        }

        $text = $message['text'];
        $chatId = $message['chat']['id'];

        if (str_starts_with($text, '/start')) {
            $parts = explode(' ', $text, 2);
            $email = trim($parts[1] ?? '');

            if (empty($email)) {
                $telegram->sendMessage($chatId, "👋 Olá! Para conectar sua conta, acesse o sistema e clique em \"Conectar com Telegram\".");
                return response('OK');
            }

            $user = User::where('email', $email)->first();

            if ($user) {
                $user->update([
                    'telegram_chat_id' => $chatId,
                    'telegram_active_at' => now(),
                ]);
                $telegram->sendMessage($chatId, "✅ Conta vinculada com sucesso, <b>{$user->name}</b>!\n\nAgora você receberá notificações de tarefas aqui.");
            } else {
                $telegram->sendMessage($chatId, "❌ E-mail <b>{$email}</b> não encontrado.\n\nVerifique se digitou o mesmo e-mail cadastrado no sistema.");
            }
        }

        return response('OK');
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
