{{-- Toast notification — trigger via window.showToast('pesan', 'success|error|info|warning') --}}
<div x-data="{
    show: false,
    message: '',
    type: 'success',
    timer: null,
    showToast(msg, type = 'success') {
        clearTimeout(this.timer);
        this.message = msg;
        this.type = type;
        this.show = true;
        this.timer = setTimeout(() => this.show = false, 3500);
    }
}" x-on:show-toast.window="showToast($event.detail.message, $event.detail.type)" x-show="show"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
    :class="{
        'toast-success': type === 'success',
        'toast-error': type === 'error',
        'toast-info': type === 'info',
        'toast-warning': type === 'warning',
    }"
    style="display:none" role="alert">
    <svg x-show="type === 'success'" class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
            clip-rule="evenodd" />
    </svg>
    <svg x-show="type === 'error'" class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
            clip-rule="evenodd" />
    </svg>
    <svg x-show="type === 'info'" class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
            clip-rule="evenodd" />
    </svg>
    <span x-text="message" class="flex-1"></span>
    <button @click="show = false" class="flex-shrink-0 opacity-60 hover:opacity-100">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>
</div>
