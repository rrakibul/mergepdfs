<?php

namespace App\Command;

use App\Model\PdfManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MergePdfsCommand extends Command
{
    // the name of the command (the part after "bin/console")
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

        $userInputFilePath = $input->getArgument('inputFilePath');
        $userOutputFilePath = $input->getArgument('outputFilePath');

        $userInputFilePath = INPUT_PATH . $userInputFilePath;

        if (!file_exists($userInputFilePath) || !is_dir($userInputFilePath)) {
            $output->writeln('<error>Input folder missing</error>');
            return Command::FAILURE;
        }

        if (empty($userOutputFilePath)) {
            $pathParts = pathinfo($userInputFilePath);
            $userOutputFilePath = OUTPUT_PATH . $pathParts['basename'];
        } else {
            $userOutputFilePath = OUTPUT_PATH . $userOutputFilePath;
        }

        if (!file_exists($userOutputFilePath)) {
            mkdir($userOutputFilePath, 0777);
        }

        $pdfManager = new PdfManager();
        $pdfManager->mergeFiles($userInputFilePath, $userOutputFilePath);

        $output->writeln('<info>Success<info>');
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}