<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:1')]
class DayOne extends AocCommand
{
    /** @param string[] $input */
    protected function partOne(array $input, OutputInterface $output): void
    {
        $elves = [];

        $curr_elf = [];

        foreach ($input as $line) {
            if ($line === "") {
                $elves[] = $curr_elf;
                $curr_elf = [];
                continue;
            }

            $curr_elf[] = (int) $line;
        }
        $elves[] = $curr_elf;

        $biggest = 0;


        foreach ($elves as $elf) {
            $sum = array_sum($elf);
            if ($sum > $biggest) {
                $biggest = $sum;
            }
        }

        $output->writeln(sprintf('%d', $biggest));
    }

    /** @param string[] $input */
    protected function partTwo(array $input, OutputInterface $output): void
    {
        $elves = [];

        $curr_elf = [];

        foreach ($input as $line) {
            if ($line === "") {
                $elves[] = $curr_elf;
                $curr_elf = [];
                continue;
            }

            $curr_elf[] = (int) $line;
        }
        $elves[] = $curr_elf;

        $sums = [];

        foreach ($elves as $elf) {
            $sums[] = array_sum($elf);
        }

        rsort($sums);

        $output->writeln(sprintf('%d', $sums[0] + $sums[1] + $sums[2]));
    }
}
