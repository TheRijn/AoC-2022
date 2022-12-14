<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:4')]
class Day4 extends AocCommand
{
    private const RE = '/(\d+)-(\d+),(\d+)-(\d+)/m';

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $count = 0;

        foreach ($input as $line) {
            \Safe\preg_match(self::RE, $line, $matches);
            $numbers = array_map(fn($x) => (int)$x, array_slice($matches, 1, 4));

            if (
                ($numbers[0] <= $numbers[2] && $numbers[1] >= $numbers[3]) ||
                ($numbers[0] >= $numbers[2] && $numbers[1] <= $numbers[3])
            ) {
                $count++;
            }
        }

        $output->writeln((string)($count));
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $count = 0;

        foreach ($input as $line) {
            \Safe\preg_match(self::RE, $line, $matches);
            $numbers = array_map(static fn($x) => (int)$x, array_slice($matches, 1, 4));

            if ((min($numbers[1], $numbers[3]) - max($numbers[0], $numbers[2]) + 1) >= 1) {
                $count++;
            }
        }

        $output->writeln((string)($count));
    }
}
