<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:2')]
class DayTwo extends AocCommand
{
    private const X = 1; // rock
    private const Y = 2; // paper
    private const Z = 3; // scissors

    private const TIE   = 3;
    private const WIN   = 6;

    /** @param string[] $input */
    protected function partOne(array $input, OutputInterface $output): void
    {
        $total = 0;

        foreach ($input as $line) {
            if ($line === "") {
                continue;
            }

            $me = $line[2];

            switch ($me) {
                case 'X':
                    $total += self::X;
                    break;
                case 'Y':
                    $total += self::Y;
                    break;
                case 'Z':
                    $total += self::Z;
                    break;
            }

            $other = $line[0];

            if (($me === 'Y' && $other === 'A') ||
                ($me === 'Z' && $other === 'B') ||
                ($me === 'X' && $other === 'C')
            ) {
                $total += self::WIN;
            } elseif (($me === 'X' && $other === 'A') ||
                ($me === 'Y' && $other === 'B') ||
                ($me === 'Z' && $other === 'C')) {
                $total += self::TIE;
            }
        }
        $output->writeln(sprintf("%d", $total));
    }

    /** @param string[] $input */
    protected function partTwo(array $input, OutputInterface $output): void
    {

    }
}
