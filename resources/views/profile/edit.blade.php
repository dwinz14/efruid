@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- ── SECTION: Data Diri ── --}}
    <div class="card" id="profil">
        <div class="card-header">
            <h2 class="text-base font-semibold text-slate-800">Data Diri</h2>
            <p class="text-sm text-slate-500 mt-0.5">Informasi akun Anda di sistem eFRUID</p>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert-success mb-5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('profile.update') }}"
                x-data="profileForm()"
            >
                @csrf
                @method('PUT')

                <script>
                    function profileForm() {
                        return {
                            jabatanId: '{{ old('jabatan_id', $user->jabatan_id) }}',
                            isLainnya: false,
                            jabatans: @json($jabatans->map(fn($j) => ['id' => $j->id, 'is_lainnya' => $j->is_lainnya])),
                            init() { this.checkLainnya(); },
                            checkLainnya() {
                                const found = this.jabatans.find(j => String(j.id) === String(this.jabatanId));
                                this.isLainnya = found ? found.is_lainnya : false;
                            }
                        }
                    }
                </script>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    {{-- Nama --}}
                    <div class="sm:col-span-2">
                        <label class="label label-required">Nama Lengkap</label>
                        <input
                            name="name" type="text"
                            value="{{ old('name', $user->name) }}"
                            class="input @error('name') input-error @enderror"
                            placeholder="Nama sesuai identitas"
                            required
                        >
                        @error('name') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- NIK — read only --}}
                    <div>
                        <label class="label">NIK Karyawan</label>
                        <input
                            type="text" value="{{ $user->nik }}"
                            class="input bg-slate-50 text-slate-500 cursor-not-allowed"
                            readonly tabindex="-1"
                        >
                        <p class="mt-1 text-xs text-slate-400">NIK tidak dapat diubah setelah registrasi</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="label label-required">Email</label>
                        <input
                            name="email" type="email"
                            value="{{ old('email', $user->email) }}"
                            class="input @error('email') input-error @enderror"
                            required
                        >
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kantor --}}
                    <div>
                        <label class="label label-required">Kantor</label>
                        <select name="kantor_id" class="input @error('kantor_id') input-error @enderror" required>
                            <option value="">— Pilih Kantor —</option>
                            @foreach($kantors as $kantor)
                                <option value="{{ $kantor->id }}"
                                    @selected(old('kantor_id', $user->kantor_id) == $kantor->id)>
                                    {{ $kantor->label }}
                                </option>
                            @endforeach
                        </select>
                        @error('kantor_id') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label class="label label-required">Jabatan</label>
                        <select
                            name="jabatan_id"
                            class="input @error('jabatan_id') input-error @enderror"
                            x-model="jabatanId"
                            @change="checkLainnya()"
                            required
                        >
                            <option value="">— Pilih Jabatan —</option>
                            @foreach($jabatans as $jabatan)
                                <option value="{{ $jabatan->id }}"
                                    @selected(old('jabatan_id', $user->jabatan_id) == $jabatan->id)>
                                    {{ $jabatan->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan_id') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jabatan custom --}}
                    <div class="sm:col-span-2" x-show="isLainnya" x-transition>
                        <label class="label label-required">Nama Jabatan</label>
                        <input
                            name="jabatan_custom" type="text"
                            value="{{ old('jabatan_custom', $user->jabatan_custom) }}"
                            class="input @error('jabatan_custom') input-error @enderror"
                            placeholder="Tulis jabatan Anda"
                            :required="isLainnya"
                        >
                        @error('jabatan_custom') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role (read only) --}}
                    <div class="sm:col-span-2">
                        <label class="label">Role</label>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @forelse($user->roles as $role)
                                <span class="badge badge-pending">{{ $role->label }}</span>
                            @empty
                                <span class="text-sm text-slate-400">Belum ada role</span>
                            @endforelse
                        </div>
                        <p class="mt-1 text-xs text-slate-400">Role diatur oleh administrator</p>
                    </div>

                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── SECTION: Ganti Password ── --}}
    <div class="card" id="password">
        <div class="card-header">
            <h2 class="text-base font-semibold text-slate-800">Ganti Password</h2>
            <p class="text-sm text-slate-500 mt-0.5">Minimal 8 karakter, kombinasi huruf dan angka</p>
        </div>
        <div class="card-body">
            <form
                method="POST"
                action="{{ route('profile.password') }}"
                x-data="{ loading: false }"
                @submit="loading = true"
            >
                @csrf
                @method('PUT')

                <div class="space-y-4">

                    <div x-data="{ show: false }">
                        <label class="label label-required">Password Saat Ini</label>
                        <div class="relative">
                            <input
                                name="current_password"
                                :type="show ? 'text' : 'password'"
                                class="input pr-10 @error('current_password') input-error @enderror"
                                placeholder="Password lama"
                                required
                            >
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600"
                                tabindex="-1">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ show: false }">
                        <label class="label label-required">Password Baru</label>
                        <div class="relative">
                            <input
                                name="password"
                                :type="show ? 'text' : 'password'"
                                class="input pr-10 @error('password') input-error @enderror"
                                placeholder="Min. 8 karakter, huruf + angka"
                                required
                            >
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600"
                                tabindex="-1">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label label-required">Konfirmasi Password Baru</label>
                        <input
                            name="password_confirmation" type="password"
                            class="input"
                            placeholder="Ulangi password baru"
                            required
                        >
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-primary" :disabled="loading">
                        <span x-text="loading ? 'Menyimpan...' : 'Ganti Password'">Ganti Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── SECTION: Tanda Tangan Digital ── --}}
    <div class="card" id="signature">
        <div class="card-header">
            <h2 class="text-base font-semibold text-slate-800">Tanda Tangan Digital</h2>
            <p class="text-sm text-slate-500 mt-0.5">
                Digunakan untuk approval permohonan. Format PNG, background transparan, maks 2MB.
            </p>
        </div>
        <div class="card-body" x-data="signatureManager()">

            <script>
                function signatureManager() {
                    return {
                        tab: 'preview',   // 'preview' | 'upload' | 'draw'
                        hasSignature: {{ $user->signature_path ? 'true' : 'false' }},
                        canvas: null,
                        ctx: null,
                        drawing: false,
                        lastX: 0,
                        lastY: 0,
                        isEmpty: true,
                        uploadLoading: false,
                        saveLoading: false,

                        switchTab(t) {
                            this.tab = t;
                            if (t === 'draw') {
                                this.$nextTick(() => this.initCanvas());
                            }
                        },

                        initCanvas() {
                            this.canvas = this.$refs.sigCanvas;
                            if (!this.canvas) return;
                            this.ctx = this.canvas.getContext('2d');
                            this.ctx.strokeStyle = '#1e3a8a';
                            this.ctx.lineWidth = 2.5;
                            this.ctx.lineCap = 'round';
                            this.ctx.lineJoin = 'round';
                            this.isEmpty = true;
                        },

                        getPos(e) {
                            const rect = this.canvas.getBoundingClientRect();
                            const src  = e.touches ? e.touches[0] : e;
                            return {
                                x: (src.clientX - rect.left) * (this.canvas.width / rect.width),
                                y: (src.clientY - rect.top)  * (this.canvas.height / rect.height),
                            };
                        },

                        startDraw(e) {
                            e.preventDefault();
                            this.drawing = true;
                            const pos = this.getPos(e);
                            this.lastX = pos.x;
                            this.lastY = pos.y;
                        },

                        draw(e) {
                            e.preventDefault();
                            if (!this.drawing) return;
                            const pos = this.getPos(e);
                            this.ctx.beginPath();
                            this.ctx.moveTo(this.lastX, this.lastY);
                            this.ctx.lineTo(pos.x, pos.y);
                            this.ctx.stroke();
                            this.lastX = pos.x;
                            this.lastY = pos.y;
                            this.isEmpty = false;
                        },

                        stopDraw(e) {
                            e.preventDefault();
                            this.drawing = false;
                        },

                        clearCanvas() {
                            if (!this.canvas) return;
                            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                            this.isEmpty = true;
                        },

                        async saveCanvas() {
                            if (this.isEmpty) {
                                alert('Tanda tangan masih kosong.');
                                return;
                            }
                            this.saveLoading = true;
                            const dataUrl = this.canvas.toDataURL('image/png');
                            document.getElementById('signatureData').value = dataUrl;
                            document.getElementById('canvasForm').submit();
                        },

                        previewFile(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            if (file.type !== 'image/png') {
                                alert('File harus berformat PNG.');
                                e.target.value = '';
                                return;
                            }
                            if (file.size > 2 * 1024 * 1024) {
                                alert('Ukuran file maksimal 2MB.');
                                e.target.value = '';
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = (ev) => {
                                this.$refs.uploadPreview.src = ev.target.result;
                                this.$refs.uploadPreview.classList.remove('hidden');
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }
            </script>

            {{-- Preview TTD saat ini --}}
            <div class="mb-6">
                <p class="text-sm font-medium text-slate-700 mb-2">Status Tanda Tangan</p>
                <div x-show="hasSignature" class="flex items-start gap-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <img
                        src="{{ $user->signature_path ? route('signature.show', $user) : '' }}"
                        alt="Tanda tangan"
                        class="h-20 w-auto object-contain border border-slate-200 rounded bg-white p-1"
                    >
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-800">Tanda tangan tersimpan</p>
                        <p class="text-xs text-green-600 mt-0.5">Akan digunakan saat approval permohonan</p>
                        <form
                            method="POST"
                            action="{{ route('profile.signature.delete') }}"
                            class="mt-3"
                            onsubmit="return confirm('Hapus tanda tangan? Aksi ini tidak dapat dibatalkan.')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">
                                Hapus Tanda Tangan
                            </button>
                        </form>
                    </div>
                </div>
                <div x-show="!hasSignature" class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm font-medium text-amber-800">Belum ada tanda tangan</p>
                    <p class="text-xs text-amber-600 mt-0.5">
                        Tanda tangan diperlukan untuk proses approval. Upload atau buat di bawah.
                    </p>
                </div>
            </div>

            {{-- Tab switcher --}}
            <div class="flex gap-2 mb-4 border-b border-slate-200">
                <button
                    type="button"
                    @click="switchTab('upload')"
                    :class="tab === 'upload'
                        ? 'border-b-2 border-brand-600 text-brand-600 font-medium'
                        : 'text-slate-500 hover:text-slate-700'"
                    class="px-4 py-2 text-sm transition-colors -mb-px"
                >
                    Upload File
                </button>
                <button
                    type="button"
                    @click="switchTab('draw')"
                    :class="tab === 'draw'
                        ? 'border-b-2 border-brand-600 text-brand-600 font-medium'
                        : 'text-slate-500 hover:text-slate-700'"
                    class="px-4 py-2 text-sm transition-colors -mb-px"
                >
                    Buat Tanda Tangan
                </button>
            </div>

            {{-- Tab: Upload --}}
            <div x-show="tab === 'upload'" x-transition>
                <form
                    method="POST"
                    action="{{ route('profile.signature.upload') }}"
                    enctype="multipart/form-data"
                    @submit="uploadLoading = true"
                >
                    @csrf

                    @error('signature_file')
                        <div class="alert-danger mb-4">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <div class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-brand-400 transition-colors">
                        <input
                            type="file"
                            name="signature_file"
                            accept=".png,image/png"
                            id="signatureFile"
                            class="hidden"
                            @change="previewFile($event)"
                        >
                        <label for="signatureFile" class="cursor-pointer">
                            <svg class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            <p class="text-sm text-slate-600 font-medium">Klik untuk pilih file</p>
                            <p class="text-xs text-slate-400 mt-1">PNG saja, maks 2MB, background transparan</p>
                        </label>
                    </div>

                    {{-- Preview upload --}}
                    <div class="mt-4 text-center hidden" id="uploadPreviewWrap">
                        <img
                            x-ref="uploadPreview"
                            src=""
                            alt="Preview"
                            class="hidden h-24 mx-auto border border-slate-200 rounded bg-slate-50 p-2"
                        >
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="btn-primary" :disabled="uploadLoading">
                            <svg x-show="uploadLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <span x-text="uploadLoading ? 'Menyimpan...' : 'Simpan Tanda Tangan'">Simpan Tanda Tangan</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tab: Draw Canvas --}}
            <div x-show="tab === 'draw'" x-transition>

                @error('signature_data')
                    <div class="alert-danger mb-4">
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <div class="mb-2 flex items-center justify-between">
                    <p class="text-sm text-slate-600">Tanda tangani di area bawah ini</p>
                    <button type="button" @click="clearCanvas()" class="btn-ghost btn-sm">
                        Hapus & Ulangi
                    </button>
                </div>

                {{-- Canvas area --}}
                <div class="border-2 border-slate-300 rounded-lg overflow-hidden bg-white cursor-crosshair touch-none">
                    <canvas
                        x-ref="sigCanvas"
                        width="600"
                        height="200"
                        class="w-full"
                        @mousedown="startDraw($event)"
                        @mousemove="draw($event)"
                        @mouseup="stopDraw($event)"
                        @mouseleave="stopDraw($event)"
                        @touchstart="startDraw($event)"
                        @touchmove="draw($event)"
                        @touchend="stopDraw($event)"
                    ></canvas>
                </div>
                <p class="text-xs text-slate-400 mt-1.5">Support mouse dan layar sentuh</p>

                {{-- Form hidden untuk submit canvas --}}
                <form
                    id="canvasForm"
                    method="POST"
                    action="{{ route('profile.signature.canvas') }}"
                >
                    @csrf
                    <input type="hidden" id="signatureData" name="signature_data">
                </form>

                <div class="mt-4 flex justify-end">
                    <button
                        type="button"
                        @click="saveCanvas()"
                        class="btn-primary"
                        :disabled="saveLoading || isEmpty"
                    >
                        <svg x-show="saveLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span x-text="saveLoading ? 'Menyimpan...' : 'Simpan Tanda Tangan'">Simpan Tanda Tangan</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
