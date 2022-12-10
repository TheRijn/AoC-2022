<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Set;
use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

use function abs;
use function array_map;
use function preg_match;
use function range;

#[AsCommand(name: 'aoc:10')]
class Day10 extends AocCommand
{
    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $instructionsIndex = 0;
        $neededCycles      = new Set([20, 60, 100, 140, 180, 220]);
        $x                 = 1;

        $total = 0;

        $instruction = 0;

        $cyclesLeftInCurrentInstruction = 0;

        foreach (range(1, 220) as $cycle) {
            if ($cyclesLeftInCurrentInstruction === 0) {
                $x += $instruction;

                $newInstruction = $input[$instructionsIndex++];

                if ($newInstruction === 'noop') {
                    $instruction                    = 0;
                    $cyclesLeftInCurrentInstruction = 1;
                } else {
                    preg_match('/addx (?<number>-?\d+)/', $newInstruction, $matches);
                    $instruction                    = (int) $matches['number'];
                    $cyclesLeftInCurrentInstruction = 2;
                }
            }

            if ($neededCycles->contains($cycle)) {
                $total += $x * $cycle;
            }

            $cyclesLeftInCurrentInstruction--;
        }

        $output->writeln((string) $total);
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $screen = array_map(static fn () => false, range(1, 240));

        $instructionsIndex = 0;
        $x                 = 1;

        $instruction = 0;

        $cyclesLeftInCurrentInstruction = 0;

        foreach (range(0, 239) as $cycle) {
            if ($cyclesLeftInCurrentInstruction === 0) {
                $x += $instruction;

                $newInstruction = $input[$instructionsIndex++];

                if ($newInstruction === 'noop') {
                    $instruction                    = 0;
                    $cyclesLeftInCurrentInstruction = 1;
                } else {
                    preg_match('/addx (?<number>-?\d+)/', $newInstruction, $matches);
                    $instruction                    = (int) $matches['number'];
                    $cyclesLeftInCurrentInstruction = 2;
                }
            }

            if (abs($cycle % 40 - $x) <= 1) {
                $screen[$cycle] = true;
            }

            $cyclesLeftInCurrentInstruction--;
        }

        foreach ($screen as $index => $pixel) {
            $output->write($pixel ? '#' : '.');

            if ($index === 0 || ($index + 1) % 40 !== 0) {
                continue;
            }

            $output->writeln('');
        }
    }
}
