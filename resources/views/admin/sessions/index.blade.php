@extends('layouts.app')
@section('title', 'Session Monitor')
@section('page-title', 'Session Monitor')

@section('content')
    <div class="space-y-4">

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @php $statItems = [['label'=>'Total Session','value'=>$stats['total'],'color'=>'slate'],['label'=>'Online','value'=>$stats['online'],'color'=>'green'],['label'=>'Idle','value'=>$stats['idle'],'color'=>'amber'],['label'=>'Offline','value'=>$stats['offline'],'color'=>'red']]; @endphp
            @foreach ($statItems as $s)
                <div class="card card-body text-center py-3">
                    <p class="text-2xl font-bold text-slate-900">{{ $s['value'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center">
            <p class="text-sm text-slate-500">Threshold: Online &lt;5 menit · Idle 5–30 menit · Offline &gt;30 menit</p>
            <a href="{{ route('admin.sessions.index') }}" class="btn-ghost btn-sm">Refresh</a>
        </div>

        <div class="card">
            @if ($sessions->isEmpty())
                <div class="card-body text-center py-12">
                    <p class="text-slate-500">Tidak ada session aktif.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto-style">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Status</th>
                                <th>IP Address</th>
                                <th>Browser / Device</th>
                                <th>Last Activity</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sessions as $sess)
                                @php $u = $users[$sess->user_id] ?? null; @endphp
                                <tr>
                                    <td>
                                        @if ($u)
                                            <a href="{{ route('admin.users.show', $u) }}"
                                                class="font-medium text-sm text-brand-600 hover:underline">{{ $u->name }}</a>
                                            <div class="text-xs text-slate-400">{{ $u->kantor?->nama }}</div>
                                        @else
                                            <span class="text-slate-400 text-sm">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($sess->is_online)
                                            <span class="badge badge-approved">Online</span>
                                        @elseif($sess->is_idle)
                                            <span class="badge badge-pending">Idle</span>
                                        @else
                                            <span class="badge badge-cancelled">Offline</span>
                                        @endif
                                    </td>
                                    <td class="font-mono text-xs">{{ $sess->ip_address ?? '—' }}</td>
                                    <td class="text-xs text-slate-500 max-w-xs truncate">
                                        {{ $sess->user_agent ? Str::limit($sess->user_agent, 50) : '—' }}</td>
                                    <td class="text-xs text-slate-500">
                                        {{ $sess->last_active->locale('id')->diffForHumans() }}
                                        <div class="text-slate-400">
                                            {{ $sess->last_active->locale('id')->isoFormat('HH:mm:ss') }}</div>
                                    </td>
                                    <td class="text-right">
                                        @if ($u)
                                            <div x-data="{ open: false }">
                                                <button @click="open = true" class="btn-ghost btn-sm text-red-600">Force
                                                    Logout</button>
                                                <div x-show="open"
                                                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                                    style="display:none">
                                                    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                                                    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                                                        <h3 class="font-semibold text-slate-900 mb-3">Force Logout Session
                                                        </h3>
                                                        <p class="text-sm text-slate-600 mb-1">User:
                                                            <strong>{{ $u->name }}</strong>
                                                        </p>
                                                        <p class="text-xs font-mono text-slate-400 mb-4">IP:
                                                            {{ $sess->ip_address }}</p>
                                                        <form method="POST"
                                                            action="{{ route('admin.users.forceLogout', $u) }}">
                                                            @csrf
                                                            <input type="hidden" name="session_id"
                                                                value="{{ $sess->id }}">
                                                            <div class="mb-4"><label
                                                                    class="label label-required">Alasan</label>
                                                                <textarea name="reason" rows="2" required class="input w-full resize-none" placeholder="Alasan force logout..."></textarea>
                                                            </div>
                                                            <div class="flex justify-end gap-2"><button type="button"
                                                                    @click="open = false"
                                                                    class="btn-ghost">Batal</button><button type="submit"
                                                                    class="btn-danger btn-sm">Force Logout</button></div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
@endsection
