<?php

namespace App\Support;

use setasign\Fpdi\Tfpdf\Fpdi;

/**
 * FPDI sobre o tFPDF (suporte a fontes TTF/UTF-8), com as fontes do
 * certificado em resources/fonts/certificado/unifont.
 */
class CertificadoPdf extends Fpdi
{
    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);

        $this->fontpath = resource_path('fonts/certificado') . DIRECTORY_SEPARATOR;
    }
}
