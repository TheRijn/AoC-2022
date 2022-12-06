<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:7')]
class Day7 extends AocCommand
{
    /** @param Vector $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
    }

    /** @param Vector $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
    }
}
