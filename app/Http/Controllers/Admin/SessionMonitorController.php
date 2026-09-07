<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SessionMonitorController extends Controller
{
    public function index(): View
    {
        $now = now()->timestamp;

        $sessions = DB::table('sessions')
            ->whereNotNull('user_id')
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($s) use ($now) {
                $s->is_online = $s->last_activity >= ($now - 300);   // 5 menit
                $s->is_idle   = !$s->is_online && $s->last_activity >= ($now - 1800); // 30 menit
                $s->last_active = \Carbon\Carbon::createFromTimestamp($s->last_activity);
                return $s;
            });

        $userIds = $sessions->pluck('user_id')->unique();
        $users   = User::with(['kantor', 'roles'])
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        $stats = [
            'online'  => $sessions->where('is_online', true)->count(),
            'idle'    => $sessions->where('is_idle', true)->count(),
            'offline' => $sessions->where('is_online', false)->where('is_idle', false)->count(),
            'total'   => $sessions->count(),
        ];

        return view('admin.sessions.index', compact('sessions', 'users', 'stats'));
    }
}
