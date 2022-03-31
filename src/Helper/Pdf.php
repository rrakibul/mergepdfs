<?php

namespace App\Helper;

use setasign\Fpdi\Fpdi;

class Pdf
{
    function merge($files, $dest)
    {
        $pdf = new Fpdi();

        foreach ($files as $file) {
            // set the source file and get the number of pages in the document
            $pageCount =  $pdf->setSourceFile($file);

            for ($i=0; $i < $pageCount; $i++) {
                //create a page
                $pdf->AddPage();
                //import a page then get the id and will be used in the template
                $tplId = $pdf->importPage($i+1);
                //use the template of the imporated page
                $pdf->useTemplate($tplId);
            }
        }

        //return the generated PDF
        return $pdf->Output($dest);
    }
}