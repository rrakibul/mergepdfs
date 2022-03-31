<?php

namespace App\Model;

use App\Helper\Pdf;
use Symfony\Component\Finder\Finder;

class PdfManager
{
    function mergeFiles($inputPath, $outputPath)
    {
        $finder = new Finder();

        $finder->files()->in($inputPath);

        $files = [];
        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $files[] = $absoluteFilePath;
        }

        $pdf = new Pdf();
        file_put_contents($outputPath . '/merged.pdf', $pdf->merge($files, 'S'));
    }
}