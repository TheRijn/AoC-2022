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
        $elves = $this->loadElves($input);

        $biggest = 0;

        foreach ($elves as $elf) {
            $sum = array_sum($elf);
            if ($sum <= $biggest) {
                continue;
            }

            $biggest = $sum;
        }

        $output->writeln((string) ($biggest));
    }

    /** @param string[] $input */
    protected function partTwo(array $input, OutputInterface $output): void
    {
        $elves = $this->loadElves($input);

        $sums = [];

        foreach ($elves as $elf) {
            $sums[] = array_sum($elf);
        }

        rsort($sums);

        $output->writeln(sprintf('%d', $sums[0] + $sums[1] + $sums[2]));
    }

    /**
     * @param string[] $input
     * @return array<int[]>
     */
    protected function loadElves(array $input) : array
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
        return $elves;
    }
}
