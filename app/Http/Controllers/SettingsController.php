<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function telegram(Request $request, TelegramService $telegram): View
    {
        $user = $request->user();

        if (! $user->telegram_token) {
            $user->update([
                'telegram_token' => bin2hex(random_bytes(8)),
            ]);
            $user->refresh();
        }

        $botInfo = $telegram->getMe();

        return view('settings.telegram', [
            'user' => $user,
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
        $data = json_decode($request->getContent(), true);

        Log::info('Telegram webhook recebido', ['data' => $data]);

        $message = $data['message'] ?? [];

        if (!isset($message['text'], $message['chat']['id'])) {
            return response('OK');
        }

        $text = $message['text'];
        $chatId = $message['chat']['id'];

        if (str_starts_with($text, '/start')) {
            $parts = explode(' ', $text, 2);
            $token = trim($parts[1] ?? '');

            if (empty($token)) {
                $telegram->sendMessage($chatId, "👋 Olá! Para conectar sua conta, acesse o sistema e clique em \"Conectar com Telegram\".");
                return response('OK');
            }

            $user = User::where('telegram_token', $token)->first();

            if ($user) {
                $user->update([
                    'telegram_chat_id' => $chatId,
                    'telegram_active_at' => now(),
                ]);

                $tasksHoje = $user->tasks()->whereDate('due_date', today())->where('status', '!=', 'concluido')->count();
                $tasksPendentes = $user->tasks()->where('status', '!=', 'concluido')->count();

                $telegram->sendMessage($chatId,
                    "🎉 <b>Bem-vindo(a), {$user->name}!</b>\n\n"
                    . "🔔 Conta vinculada com sucesso!\n\n"
                    . "📌 Você tem <b>{$tasksPendentes}</b> tarefa(s) pendente(s) no total\n"
                    . "📅 Sendo <b>{$tasksHoje}</b> para hoje\n\n"
                    . "A partir de agora você receberá:\n"
                    . "• ⏰ Lembretes de tarefas próximas do prazo\n"
                    . "• 📊 Alertas de SLA\n\n"
                    . "<i>Bora produzir! 🚀</i>"
                );

                Log::info('Telegram: usuário conectado', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'chat_id' => $chatId,
                ]);
            } else {
                $telegram->sendMessage($chatId, "❌ Token inválido ou expirado.\n\nAcesse o sistema e gere um novo link em Configurações > Telegram.");
                Log::warning('Telegram: token inválido', ['token' => $token]);
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
