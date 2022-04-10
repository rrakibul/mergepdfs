<?php

namespace App\Helper;

class FileHelper
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

        foreach ($finder->files() as $file) {
            $files[] = $file;
        }

        return $files;
    }

    static function removeDir($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::removeDir($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}