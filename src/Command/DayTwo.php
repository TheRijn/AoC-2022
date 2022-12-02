<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:2')]
class DayTwo extends AocCommand
{
    private const ROCK = 1; // rock
    private const PAPER = 2; // paper
    private const SCISSORS = 3; // scissors

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

            if ($me === 'X') {
                $total += self::ROCK;
            } elseif ($me === 'Y') {
                $total += self::PAPER;
            } elseif ($me === 'Z') {
                $total += self::SCISSORS;
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
        $total = 0;

        foreach ($input as $line) {
            if ($line === "") {
                continue;
            }

            $shouldWin = $line[2];
            $other = $line[0];
            if ($shouldWin === 'X') {// lose
                if ($other === 'A') {
                    $total += self::SCISSORS;
                } elseif ($other === 'B') {
                    $total += self::ROCK;
                } elseif ($other === 'C') {
                    $total += self::PAPER;
                }
            } elseif ($shouldWin === 'Y') { // tie
                $total += self::TIE;
                if ($other === 'A') {
                    $total += self::ROCK;
                } elseif ($other === 'B') {
                    $total += self::PAPER;
                } elseif ($other === 'C') {
                    $total += self::SCISSORS;
                }
            } else { // Win (Z)
                $total += self::WIN;
                if ($other === 'A') {
                    $total += self::PAPER;
                } elseif ($other === 'B') {
                    $total += self::SCISSORS;
                } elseif ($other === 'C') {
                    $total += self::ROCK;
                }
            }

        }
        $output->writeln(sprintf('%d', $total));
    }
}
