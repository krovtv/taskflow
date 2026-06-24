<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudyDashboardController;
use App\Http\Controllers\StudyFlashcardController;
use App\Http\Controllers\StudySessionController;
use App\Http\Controllers\StudySpecializationController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas públicas (visitante)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/registrar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registrar', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Rotas autenticadas
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/phases', [ProjectController::class, 'phaseStore'])->name('projects.phases.store');
    Route::put('/projects/{project}/phases/{phase}', [ProjectController::class, 'phaseUpdate'])->name('projects.phases.update');
    Route::delete('/projects/{project}/phases/{phase}', [ProjectController::class, 'phaseDestroy'])->name('projects.phases.destroy');

    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::get('/tasks/search/json', [TaskController::class, 'search'])->name('tasks.search');
    Route::post('/tasks/{task}/upload', [TaskController::class, 'upload'])->name('tasks.upload');
    Route::get('/tasks/{task}/download/{attachment}', [TaskController::class, 'download'])->name('tasks.download');
    Route::delete('/tasks/{task}/attachment/{attachment}', [TaskController::class, 'deleteAttachment'])->name('tasks.attachment.delete');
    Route::post('/tasks/batch/status', [TaskController::class, 'batchStatus'])->name('tasks.batch.status');
    Route::delete('/tasks/batch/destroy', [TaskController::class, 'batchDestroy'])->name('tasks.batch.destroy');

    Route::get('/configuracoes/categorias', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categorias/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');

    Route::get('/relatorios', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/relatorios/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');

    Route::get('/notificacoes', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notificacoes/{id}/ler', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notificacoes/{id}/redirect', [NotificationController::class, 'redirect'])->name('notifications.redirect');
    Route::patch('/notificacoes/ler-todas', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Daily Log
    Route::get('/diario', [\App\Http\Controllers\DailyLogController::class, 'index'])->name('daily-log.index');
    Route::post('/diario', [\App\Http\Controllers\DailyLogController::class, 'update'])->name('daily-log.update');
    Route::get('/diario/exportar/txt', [\App\Http\Controllers\DailyLogController::class, 'exportTxt'])->name('daily-log.export-txt');
    Route::get('/diario/exportar/pdf', [\App\Http\Controllers\DailyLogController::class, 'exportPdf'])->name('daily-log.export-pdf');
    Route::get('/diario/{date}', [\App\Http\Controllers\DailyLogController::class, 'showDate'])->name('daily-log.date');

    // Task Timer
    Route::post('/tasks/{task}/timer/start', [\App\Http\Controllers\TaskTimerController::class, 'start'])->name('tasks.timer.start');
    Route::post('/tasks/{task}/timer/stop', [\App\Http\Controllers\TaskTimerController::class, 'stop'])->name('tasks.timer.stop');
    Route::get('/tasks/timer/status', [\App\Http\Controllers\TaskTimerController::class, 'status'])->name('tasks.timer.status');

    Route::get('/configuracoes/telegram', [SettingsController::class, 'telegram'])->name('settings.telegram');
    Route::post('/configuracoes/telegram', [SettingsController::class, 'telegramUpdate'])->name('settings.telegram.update');
    Route::get('/configuracoes/telegram/teste', [SettingsController::class, 'telegramTest'])->name('settings.telegram.test');
    Route::get('/configuracoes/telegram/desconectar', [SettingsController::class, 'telegramDisconnect'])->name('settings.telegram.disconnect');

    // Study module
    Route::prefix('estudos')->name('studies.')->group(function () {
        Route::get('/', [StudyDashboardController::class, 'index'])->name('dashboard');

        Route::get('/especializacoes', [StudySpecializationController::class, 'index'])->name('specializations.index');
        Route::get('/especializacoes/{specialization}', [StudySpecializationController::class, 'show'])->name('specializations.show');
        Route::post('/especializacoes', [StudySpecializationController::class, 'store'])->name('specializations.store');
        Route::put('/especializacoes/{specialization}', [StudySpecializationController::class, 'update'])->name('specializations.update');
        Route::delete('/especializacoes/{specialization}', [StudySpecializationController::class, 'destroy'])->name('specializations.destroy');

        Route::post('/especializacoes/{specialization}/notes', [StudyNoteController::class, 'store'])->name('notes.store');
        Route::put('/notes/{note}', [StudyNoteController::class, 'update'])->name('notes.update');
        Route::delete('/notes/{note}', [StudyNoteController::class, 'destroy'])->name('notes.destroy');

        Route::get('/flashcards', [StudyFlashcardController::class, 'index'])->name('flashcards.index');
        Route::post('/flashcards', [StudyFlashcardController::class, 'store'])->name('flashcards.store');
        Route::put('/flashcards/{flashcard}', [StudyFlashcardController::class, 'update'])->name('flashcards.update');
        Route::delete('/flashcards/{flashcard}', [StudyFlashcardController::class, 'destroy'])->name('flashcards.destroy');
        Route::get('/flashcards/revisar', [StudyFlashcardController::class, 'review'])->name('flashcards.review');
        Route::post('/flashcards/revisar/enviar', [StudyFlashcardController::class, 'submitReview'])->name('flashcards.submit-review');

        Route::get('/timer', [StudySessionController::class, 'index'])->name('timer.index');
        Route::post('/timer/iniciar', [StudySessionController::class, 'start'])->name('timer.start');
        Route::post('/timer/parar', [StudySessionController::class, 'stop'])->name('timer.stop');
        Route::get('/timer/status', [StudySessionController::class, 'status'])->name('timer.status');
    });
});
