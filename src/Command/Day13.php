<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:13')]
class Day13 extends AocCommand
{
    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $pairs = [];

        for ($i = 0; $i < $input->count() / 3; $i += 3) {
            $pairs[] = [\Safe\json_decode($input->get($i)), \Safe\json_decode($input->get($i + 1))];
        }

        dump($pairs);
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
    }
}
