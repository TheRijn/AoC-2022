<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

use function array_intersect;
use function array_map;
use function array_slice;
use function array_values;
use function count;
use function ctype_lower;
use function intdiv;
use function ord;
use function sprintf;
use function str_split;

#[AsCommand(name: 'aoc:3')]
class Day3 extends AocCommand
{
    private function getPriorityForChar(string $char): int
    {
        if (ctype_lower($char)) {
            return ord($char) - 96; // 'a' - 1
        }

        return ord($char) - 38; // 'A' - 1 + 26
    }

    /** @param string[] $input */
    protected function partOne(array $input, OutputInterface $output): void
    {
        $sum = 0;

        foreach ($input as $rucksack) {
            $all        = str_split($rucksack);
            $partLength = intdiv(count($all), 2);

            $part1 = array_slice($all, 0, $partLength);
            $part2 = array_slice($all, $partLength, $partLength);

            $both = array_values(array_intersect($part1, $part2))[0];

            $sum += $this->getPriorityForChar($both);
        }

        $output->writeln((string) ($sum));
    }

    /** @param string[] $input */
    protected function partTwo(array $input, OutputInterface $output): void
    {
        $count = count($input);
        $start = 0;

        $sum = 0;

        while ($start < $count - 2) {
            $rucksacks = array_map('str_split', array_slice($input, $start, 3));
            $badge     = array_values(array_intersect(...$rucksacks))[0];

            $sum += $this->getPriorityForChar($badge);

            $start += 3;
        }

        $output->writeln((string) ($sum));
    }
}
