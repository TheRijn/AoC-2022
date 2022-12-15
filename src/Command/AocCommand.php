<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function explode;

use const STDIN;

abstract class AocCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('one', '1', InputOption::VALUE_NONE, 'Execute part One')
            ->addOption('two', '2', InputOption::VALUE_NONE, 'Execute part Two');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $textInput = $this->getInputFromStdIn($input);
        if ($input->getOption('one')) {
            $this->partOne($textInput, $output);
        }

        if ($input->getOption('two')) {
            $this->partTwo($textInput, $output);
        }

        if (!$input->getOption('one') && !$input->getOption('two')) {
            $this->partOne($textInput, $output);
            $this->partTwo($textInput, $output);
        }

        return Command::SUCCESS;
    }

    /**
     * https://github.com/symfony/symfony/issues/37835
     * @return Vector<string>
     */
    private function getInputFromStdIn(InputInterface $input): Vector
    {
        $inputStream = $input instanceof StreamableInputInterface ? $input->getStream() : null;
        $inputStream ??= STDIN;

        $contents = \Safe\stream_get_contents($inputStream);

        return new Vector(explode("\n", rtrim($contents)));
    }

    /** @param Vector<string> $input */
    abstract protected function partOne(Vector $input, OutputInterface $output): void;

    /** @param Vector<string> $input */
    abstract protected function partTwo(Vector $input, OutputInterface $output): void;
}
