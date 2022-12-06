<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:1')]
class Day1 extends AocCommand
{
    /** @param Vector $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $elves = $this->loadElfSums($input);

        $output->writeln((string) $elves->sorted()->last());
    }

    /** @param Vector $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $sums = $this->loadElfSums($input);

        $output->writeln((string) $sums->sorted()->slice(-3, 3)->sum());
    }

    /**
     * @param string[] $input
     *
     * @return Vector<int>
     */
    protected function loadElfSums(Vector $input): Vector
    {
        $elves = new Vector();

        $currElf = new Vector();

        foreach ($input as $line) {
            if ($line === '') {
                $elves[] = $currElf;
                $currElf = new Vector();
                continue;
            }

            $currElf[] = (int) $line;
        }

        $elves[] = $currElf;

        return $elves->map(static fn ($x) => $x->sum());
    }
}
