<?php

namespace App\Helper;

use setasign\Fpdi\Fpdi;

class PdfHelper
{
    public function convertToPdfAndSave($outputPath, \SplFileInfo  $file)
    {
        $inputFilename = $file->getPathName();
        $outputFilename = $file->getBasename($file->getExtension()) . 'pdf';

        file_put_contents($outputPath . "/{$outputFilename}", $this->convertImageToPdf($inputFilename, 'S'));
    }

    function convertImageToPdf($file, $dest)
    {
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->Image($file);

        return $pdf->Output($dest);
    }

    function merge($files, $dest)
    {
        $pdf = new Fpdi();

        foreach ($files as $file) {
            $pageCount =  $pdf->setSourceFile($file->getRealPath());

            for ($i=0; $i < $pageCount; $i++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($i+1);
                $pdf->useTemplate($tplId);
            }
        }

        return $pdf->Output($dest);
    }
}