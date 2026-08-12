<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // ── Ambil count notif belum dibaca (untuk polling) ────────────────────

    public function count(): JsonResponse
    {
        return response()->json([
            'count' => auth()->user()
                ->unreadNotifications()
                ->count(),
        ]);
    }

    // ── Ambil 10 notif terbaru (untuk dropdown) ───────────────────────────

    public function index(): JsonResponse
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'data'       => $n->data,
                'read'       => ! is_null($n->read_at),
                'created_at' => $n->created_at
                    ->locale('id')
                    ->diffForHumans(),
            ]);

        return response()->json(['notifications' => $notifications]);
    }

    // ── Mark satu notif sebagai dibaca ────────────────────────────────────

    public function markRead(Request $request): JsonResponse
    {
        $notif = auth()->user()
            ->notifications()
            ->where('id', $request->id)
            ->first();

        if ($notif) {
            $notif->markAsRead();
        }

        return response()->json(['ok' => true]);
    }

    // ── Mark semua sebagai dibaca ─────────────────────────────────────────

    public function markAllRead(): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['ok' => true]);
    }
}
