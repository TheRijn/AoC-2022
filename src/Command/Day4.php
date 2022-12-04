<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:4')]
class Day4 extends AocCommand
{
    private const RE = '/(\d+)-(\d+),(\d+)-(\d+)/m';

    /** @param string[] $input */
    protected function partOne(array $input, OutputInterface $output): void
    {
        $count = 0;

        foreach ($input as $line) {
            if ($line === '') {
                continue;
            }

            preg_match(self::RE, $line, $matches);
            $numbers = array_map(fn ($x) => (int) $x, array_slice($matches, 1, 4));

            if (($numbers[0] <= $numbers[2] && $numbers[1] >= $numbers[3]) ||
                ($numbers[0] >= $numbers[2] && $numbers[1] <= $numbers[3])) {
                $count++;
            }
        }

        $output->writeln(sprintf('%d', $count));
    }

    /** @param string[] $input */
    protected function partTwo(array $input, OutputInterface $output): void
    {
        $count = 0;

        foreach ($input as $line) {
            if ($line === '') {
                continue;
            }

            preg_match(self::RE, $line, $matches);
            $numbers = array_map(fn ($x) => (int) $x, array_slice($matches, 1, 4));

            if ((min($numbers[1], $numbers[3]) - max($numbers[0], $numbers[2]) + 1) >= 1) {
                $count++;
            }
        }

        $output->writeln(sprintf('%d', $count));
    }
}
