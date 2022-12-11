<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Set;
use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

use function ctype_lower;
use function intdiv;
use function ord;
use function str_split;

#[AsCommand(name: 'aoc:3')]
class Day3 extends AocCommand
{
    private static function getPriorityForChar(string $char): int
    {
        if (ctype_lower($char)) {
            return ord($char) - 96; // 'a' - 1
        }

        return ord($char) - 38; // 'A' - 1 + 26
    }

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $sum = 0;

        foreach ($input as $rucksack) {
            $all = new Vector(str_split($rucksack));
            $partLength = intdiv($all->count(), 2);

            $part1 = new Set($all->slice(0, $partLength));
            $part2 = new Set($all->slice($partLength, $partLength));

            $both = $part1->intersect($part2)[0];

            $sum += self::getPriorityForChar($both);
        }

        $output->writeln((string)($sum));
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $count = $input->count();
        $start = 0;

        $sum = 0;

        while ($start < $count - 2) {
            /** @var Vector<Set<string>> $rucksacks */
            $rucksacks = $input->slice($start, 3)->map('str_split')->map(static fn($x) => new Set($x));
            $badge = $rucksacks[0]->intersect($rucksacks[1])->intersect($rucksacks[2])[0];

            $sum += self::getPriorityForChar($badge);

            $start += 3;
        }

        $output->writeln((string)($sum));
    }
}
