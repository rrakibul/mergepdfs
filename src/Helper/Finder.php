<?php

namespace App\Helper;

class Finder
{
    static function findFiles($inputPath, $allowedExtensions = [])
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in($inputPath);

        if (count($allowedExtensions) > 0) {
            foreach ($allowedExtensions as $extension) {
                $extension = strtolower($extension);
                $finder->name("*.{$extension}");
            }
        }

        $files = [];

        foreach ($finder as $file) {
            $files[] = $file->getRealPath();
        }

        return $files;
    }
}