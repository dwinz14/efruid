@props([
    'mode' => 'interactive',
    'traceLabel' => null,
])

@php($isProtectedViewer = in_array($mode, ['interactive', 'public'], true))

@if ($isProtectedViewer)
    <style>
        /* Scope every deterrent to the document viewer, never to the app UI. */
        .document-guard,
        .document-guard * {
            -webkit-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
        }

        .document-guard img,
        .document-guard svg {
            -webkit-user-drag: none;
            user-drag: none;
        }

        .document-guard {
            position: relative;
            isolation: isolate;
        }

        .document-guard__trace {
            position: fixed;
            inset: 0;
            z-index: 200;
            pointer-events: none;
            overflow: hidden;
            opacity: .16;
        }

        .document-guard__trace span {
            position: absolute;
            width: 360px;
            color: #7f1d1d;
            font: 700 13px/1.45 Arial, sans-serif;
            letter-spacing: .7px;
            text-align: center;
            text-transform: uppercase;
            transform: rotate(-32deg);
        }

        .document-guard__trace span:nth-child(1) { top: 10%; left: -8%; }
        .document-guard__trace span:nth-child(2) { top: 35%; left: 26%; }
        .document-guard__trace span:nth-child(3) { top: 60%; left: -6%; }
        .document-guard__trace span:nth-child(4) { top: 82%; left: 29%; }

        @media print {
            /* Interactive/public views are not a download channel. */
            body > * { display: none !important; }
        }
    </style>
@endif

<div class="document-guard document-guard--{{ $mode }}" data-document-guard>
    @if ($isProtectedViewer && $traceLabel)
        <div class="document-guard__trace" aria-hidden="true">
            <span>{{ $traceLabel }}</span><span>{{ $traceLabel }}</span>
            <span>{{ $traceLabel }}</span><span>{{ $traceLabel }}</span>
        </div>
    @endif

    {{ $slot }}
</div>

@if ($isProtectedViewer)
    <script>
        (() => {
            const guard = document.querySelector('[data-document-guard]');
            if (!guard) return;

            guard.querySelectorAll('img, svg').forEach((element) => {
                element.setAttribute('draggable', 'false');
            });

            guard.addEventListener('contextmenu', (event) => event.preventDefault());
            guard.addEventListener('dragstart', (event) => event.preventDefault());
            guard.addEventListener('selectstart', (event) => event.preventDefault());

            window.addEventListener('keydown', (event) => {
                const key = event.key.toLowerCase();
                const blocked = event.key === 'F12'
                    || (event.ctrlKey && event.shiftKey && ['i', 'j', 'c'].includes(key))
                    || (event.ctrlKey && ['u', 'p', 's'].includes(key));

                if (blocked) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            }, true);
        })();
    </script>
@endif
