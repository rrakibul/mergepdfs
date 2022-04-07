<?php

namespace App\Command;

use App\Helper\Finder as AppFinder;
use App\Helper\Pdf;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MergePdfsCommand extends Command
{
    protected static $defaultName = 'app:merge-pdfs';

    protected function configure(): void
    {
        $this
            ->addArgument('inputPath', InputArgument::REQUIRED, 'Path of the folder where files to be merged')
            ->addArgument('outputPath', InputArgument::OPTIONAL, 'Output file path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paths = $this->pickFilePaths($input, $output);

        $this->processFiles($paths['inputPath'], $paths['outputPath']);

        $this->mergeFiles($paths['outputPath'], $input->getArgument('inputPath'));
        
        $output->writeln('<info>Success<info>');
        return Command::SUCCESS;
    }
    
    public function mergeFiles($filesPath, $fileName)
    {
        $files = AppFinder::findFiles($filesPath, ['pdf']);

        $pdf = new Pdf();

        file_put_contents(OUTPUT_PATH . $fileName . '-' .
            date('ymd-Hi') . '.pdf', $pdf->merge($files, 'S'));

        $this->removeDirectory(OUTPUT_PATH . $fileName);
    }

    public function processFiles($inputPath, $outputPath)
    {
        $files = AppFinder::findFiles($inputPath, ['pdf', 'jpeg']);

        $pdf = new Pdf();

        foreach ($files as $file) {
            if ($file->getExtension() == 'pdf') {
                copy($inputPath . DIRECTORY_SEPARATOR . $file->getFilename(),
                $outputPath . DIRECTORY_SEPARATOR . $file->getFilename());
            } else {
                $pdf->convertToPdfAndSave($outputPath, $file);
            }
        }
    }

    public function pickFilePaths(InputInterface $input, OutputInterface $output)
    {
        $inputUserPath = $input->getArgument('inputPath');
        $outputUserPath = $input->getArgument('outputPath');

        $inputUserPath = INPUT_PATH . $inputUserPath;

        if (!file_exists($inputUserPath) || !is_dir($inputUserPath)) {
            $output->writeln('<error>Input folder missing</error>');
            return Command::FAILURE;
        }

        if (empty($outputUserPath)) {
            $pathParts = pathinfo($inputUserPath);
            $outputUserPath = OUTPUT_PATH . $pathParts['basename'];
        } else {
            $outputUserPath = OUTPUT_PATH . $outputUserPath;
        }

        if (!file_exists($outputUserPath)) {
            mkdir($outputUserPath, 0777);
        }

        return [
            'inputPath' => $inputUserPath,
            'outputPath' => $outputUserPath
        ];
    }

    function removeDirectory($dir)
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

            if (!$this->removeDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}