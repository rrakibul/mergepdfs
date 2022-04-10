<?php

namespace App\Command;

use App\Helper\FileHelper;
use App\Helper\PdfHelper;
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
            ->addArgument('input_dir_name', InputArgument::REQUIRED, 'Path of the folder where files to be merged')
            ->addArgument('output_filename', InputArgument::OPTIONAL, 'Output file name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paths = $this->pickFilePaths($input, $output);

        $this->processFiles($paths['inputPath'], $paths['outputPath']);

        $outfileName = ($input->getArgument('output_filename')) ? $input->getArgument('output_filename')
            : $input->getArgument('input_dir_name');

        $this->mergeFiles($paths['outputPath'], $outfileName);
        
        $output->writeln('<info>Success<info>');
        return Command::SUCCESS;
    }
    
    private function mergeFiles($filesPath, $fileName)
    {
        $files = FileHelper::findFiles($filesPath, ['pdf']);

        $pdf = new PdfHelper();

        file_put_contents(OUTPUT_PATH . $fileName . '-' .
            date('ymd-Hi') . '.pdf', $pdf->merge($files, 'S'));

        FileHelper::removeDir(OUTPUT_PATH . $fileName);
    }

    private function processFiles($inputPath, $outputPath)
    {
        $files = FileHelper::findFiles($inputPath, ['pdf', 'jpeg']);

        $pdf = new PdfHelper();

        foreach ($files as $file) {
            if ($file->getExtension() == 'pdf') {
                copy($inputPath . DIRECTORY_SEPARATOR . $file->getFilename(),
                $outputPath . DIRECTORY_SEPARATOR . $file->getFilename());
            } else {
                $pdf->convertToPdfAndSave($outputPath, $file);
            }
        }
    }

    private function pickFilePaths(InputInterface $input, OutputInterface $output)
    {
        $inputDirName = $input->getArgument('input_dir_name');
        $outputFilename = $input->getArgument('output_filename');

        $inputPath = INPUT_PATH . $inputDirName;

        if (!file_exists($inputPath) || !is_dir($inputPath)) {
            $output->writeln('<error>Input folder missing</error>');
            return Command::FAILURE;
        }

        $outputPath = OUTPUT_PATH . $inputDirName;

        if (!empty($outputFilename)) {
            $outputPath = OUTPUT_PATH . $outputFilename;
        }

        if (!file_exists($outputPath)) {
            mkdir($outputPath, 0777);
        }

        return [
            'inputPath' => $inputPath,
            'outputPath' => $outputPath
        ];
    }
}