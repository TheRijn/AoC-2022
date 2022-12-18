<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:18')]
class Day18 extends AocCommand
{
    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        /** @var array{int, int, int} $lava */
        $lava = [];

        foreach ($input as $line) {
            $lava[] = array_map(static fn($x) => (int)$x, explode(',', $line));
        }

        $area = count($lava) * 6;

        foreach ($lava as $first) {
            foreach ($lava as $second) {
                if (
                    abs($first[0] - $second[0]) +
                    abs($first[1] - $second[1]) +
                    abs($first[2] - $second[2]) === 1
                ) {
                    $area--;
                }
            }
        }
        $output->writeln((string)$area);
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
    }
}
