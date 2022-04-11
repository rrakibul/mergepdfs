<?php

use App\Command\MergePdfCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class MergePdfCommandTest extends TestCase
{
    private ?CommandTester $commandTester;

    protected function setUp(): void
    {
        $application = new Application();
        $application->add(new MergePdfCommand());
        $command = $application->find('app:merge-pdfs');
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown(): void
    {
        $this->commandTester = null;
    }

    public function testExecute()
    {
        $this->commandTester->execute(['input_dir_name' => 'files', 'output_filename' => 'outputfile']);

        $this->assertEquals('Success', $this->commandTester->getDisplay());
    }
}