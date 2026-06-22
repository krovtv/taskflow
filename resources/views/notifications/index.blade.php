@extends('layouts.app')
@section('title', 'Notificações')
@section('heading', 'Notificações')

@section('content')
<div class="flex justify-end mb-4 animate-in">
    <form method="POST" action="{{ route('notifications.readAll') }}">
        @csrf @method('PATCH')
        <button class="text-sm font-semibold text-kvteal hover:text-kvteal-dark transition-colors inline-flex items-center gap-1.5 bg-kvteal/5 hover:bg-kvteal/10 px-4 py-2 rounded-xl">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Marcar todas como lidas
        </button>
    </form>
</div>

<div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 divide-y divide-slate-100 dark:divide-gray-800 shadow-sm overflow-hidden animate-in">
    @forelse($notifications as $n)
        <div class="px-5 py-4 flex items-start justify-between transition-colors {{ $n->read_at ? 'opacity-50' : 'bg-gradient-to-r from-kvteal/[0.03] to-transparent' }}">
            <a href="{{ route('notifications.redirect', $n->id) }}" class="flex items-start gap-3.5 min-w-0 group flex-1">
                <div class="w-9 h-9 rounded-xl {{ $n->read_at ? 'bg-slate-100 dark:bg-gray-700' : 'bg-kvteal/10' }} flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 {{ $n->read_at ? 'text-slate-400 dark:text-slate-500' : 'text-kvteal' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 group-hover:text-kvteal transition-colors">{{ $n->data['message'] ?? 'Notificação' }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 font-medium">{{ $n->created_at->diffForHumans() }}</p>
                </div>
            </a>
            @unless($n->read_at)
                <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                    @csrf @method('PATCH')
                    <button class="text-xs font-semibold text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-3 py-1.5 rounded-lg transition-all ml-3 shrink-0">
                        Ler
                    </button>
                </form>
            @endunless
        </div>
    @empty
        <div class="px-5 py-14 text-center">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
            </div>
            <p class="text-slate-400 dark:text-slate-500 font-semibold">Nenhuma notificação</p>
            <p class="text-slate-300 dark:text-gray-600 text-xs mt-0.5">Tudo tranquilo por aqui</p>
        </div>
    @endforelse
</div>

<div class="mt-5">{{ $notifications->links() }}</div>
@endsection