<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

use function array_sum;
use function rsort;
use function sprintf;

#[AsCommand(name: 'aoc:1')]
class Day1 extends AocCommand
{
    /** @param string[] $input */
    protected function partOne(array $input, OutputInterface $output): void
    {
        $elves = [];

        $currElf = [];

        foreach ($input as $line) {
            if ($line === '') {
                $elves[] = $currElf;
                $currElf = [];
                continue;
            }

            $currElf[] = (int) $line;
        }

        $elves[] = $currElf;

        $biggest = 0;

        foreach ($elves as $elf) {
            $sum = array_sum($elf);
            if ($sum <= $biggest) {
                continue;
            }

            $biggest = $sum;
        }

        $output->writeln(sprintf('%d', $biggest));
    }

    /** @param string[] $input */
    protected function partTwo(array $input, OutputInterface $output): void
    {
        $elves = [];

        $currElf = [];

        foreach ($input as $line) {
            if ($line === '') {
                $elves[] = $currElf;
                $currElf = [];
                continue;
            }

            $currElf[] = (int) $line;
        }

        $elves[] = $currElf;

        $sums = [];

        foreach ($elves as $elf) {
            $sums[] = array_sum($elf);
        }

        rsort($sums);

        $output->writeln(sprintf('%d', $sums[0] + $sums[1] + $sums[2]));
    }
}
