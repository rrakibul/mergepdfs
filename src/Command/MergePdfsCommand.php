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
            ->addArgument('inputFilePath', InputArgument::REQUIRED, 'Path of the folder where files to be merged')
            ->addArgument('outputFilePath', InputArgument::OPTIONAL, 'Output file path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paths = $this->pickFilePaths($input, $output);
        $this->mergeAndSaveFile($paths['inputFilePath'], $paths['outputFilePath']);

        $output->writeln('<info>Success<info>');
        return Command::SUCCESS;
    }

    public function pickFilePaths(InputInterface $input, OutputInterface $output)
    {
        $inputFilePath = $input->getArgument('inputFilePath');
        $outputFilePath = $input->getArgument('outputFilePath');

        $inputFilePath = INPUT_PATH . $inputFilePath;

        if (!file_exists($inputFilePath) || !is_dir($inputFilePath)) {
            $output->writeln('<error>Input folder missing</error>');
            return Command::FAILURE;
        }

        if (empty($outputFilePath)) {
            $pathParts = pathinfo($inputFilePath);
            $outputFilePath = OUTPUT_PATH . $pathParts['basename'];
        } else {
            $outputFilePath = OUTPUT_PATH . $outputFilePath;
        }

        if (!file_exists($outputFilePath)) {
            mkdir($outputFilePath, 0777);
        }

        return [
            'inputFilePath' => $inputFilePath,
            'outputFilePath' => $outputFilePath
        ];
    }

    public function mergeAndSaveFile($inputPath, $outputPath, $outFilename = 'merged.pdf')
    {
        $files = AppFinder::findFiles($inputPath, ['pdf']);

        $pdf = new Pdf();

        file_put_contents($outputPath . "/$outFilename", $pdf->merge($files, 'S'));
    }
}