<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function explode;
use function stream_get_contents;

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

        if (! $input->getOption('one') && ! $input->getOption('two')) {
            $this->partOne($textInput, $output);
            $this->partTwo($textInput, $output);
        }

        return Command::SUCCESS;
    }

    private function getInputFromStdIn(InputInterface $input): array|false
    {
        $inputStream   = $input instanceof StreamableInputInterface ? $input->getStream() : null;
        $inputStream ??= STDIN;

        return explode("\n", stream_get_contents($inputStream));
    }

    /** @param string[] $input */
    abstract protected function partOne(array $input, OutputInterface $output): void;

    /** @param string[] $input */
    abstract protected function partTwo(array $input, OutputInterface $output): void;
}
