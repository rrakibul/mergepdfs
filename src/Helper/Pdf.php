<?php

namespace App\Helper;

use setasign\Fpdi\Fpdi;

class Pdf
{
    function merge($files, $dest)
    {
        $pdf = new Fpdi();

        foreach ($files as $file) {
            $pageCount =  $pdf->setSourceFile($file);

            for ($i=0; $i < $pageCount; $i++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($i+1);
                $pdf->useTemplate($tplId);
            }
        }

        return $pdf->Output($dest);
    }
}