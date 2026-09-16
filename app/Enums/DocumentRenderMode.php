<?php

namespace App\Enums;

/**
 * Context in which a FRUID document is rendered.
 *
 * PDF deliberately has no browser-only protection so Dompdf receives only
 * print-safe markup. Interactive and public documents are protected in the
 * rendered document itself, including when their iframe URL is opened alone.
 */
enum DocumentRenderMode: string
{
    case PDF = 'pdf';
    case INTERACTIVE = 'interactive';
    case PUBLIC = 'public';

    public function isProtectedViewer(): bool
    {
        return $this !== self::PDF;
    }
}
