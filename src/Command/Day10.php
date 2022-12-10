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
    private int $instructionsIndex;
    private int $x;
    private int $instruction;
    private int $cyclesLeftInCurrentInstruction;

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $this->instructionsIndex = 0;
        $neededCycles = new Set([20, 60, 100, 140, 180, 220]);
        $this->x = 1;

        $total = 0;

        $this->instruction = 0;

        $this->cyclesLeftInCurrentInstruction = 0;

        foreach (range(1, 220) as $cycle) {
            $this->newInstruction($input);

            if ($neededCycles->contains($cycle)) {
                $total += $this->x * $cycle;
            }

            $this->cyclesLeftInCurrentInstruction--;
        }

        $output->writeln((string)$total);
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $screen = array_map(static fn() => false, range(1, 240));

        $this->instructionsIndex = 0;
        $this->x = 1;

        $this->instruction = 0;

        $this->cyclesLeftInCurrentInstruction = 0;

        foreach (range(0, 239) as $cycle) {
            $this->newInstruction($input);

            if (abs($cycle % 40 - $this->x) <= 1) {
                $screen[$cycle] = true;
            }

            $this->cyclesLeftInCurrentInstruction--;
        }

        foreach ($screen as $index => $pixel) {
            $output->write($pixel ? '#' : '.');

            if ($index === 0 || ($index + 1) % 40 !== 0) {
                continue;
            }

            $output->writeln('');
        }
    }

    protected function newInstruction(
        Vector $input,
    ): void {
        if ($this->cyclesLeftInCurrentInstruction !== 0) {
            return;
        }

        $this->x += $this->instruction;

        $newInstruction = $input[$this->instructionsIndex++];

        if ($newInstruction === 'noop') {
            $this->instruction = 0;
            $this->cyclesLeftInCurrentInstruction = 1;
        } else {
            preg_match('/addx (?<number>-?\d+)/', $newInstruction, $matches);
            $this->instruction = (int)$matches['number'];
            $this->cyclesLeftInCurrentInstruction = 2;
        }
    }
}
