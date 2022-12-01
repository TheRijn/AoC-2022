<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:2')]
class DayTwo extends AocCommand
{
    /** @param string[] $input */
    protected function partOne(array $input, OutputInterface $output): void
    {

    }

    /** @param string[] $input */
    protected function partTwo(array $input, OutputInterface $output): void
    {

    }
}
