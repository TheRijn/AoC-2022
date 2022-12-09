<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

use function abs;
use function array_key_exists;
use function array_map;
use function array_sum;
use function explode;
use function range;

#[AsCommand(name: 'aoc:9')]
class Day9 extends AocCommand
{
    /** @param int[] $head */
    private static function moveHead(array &$head, string $direction): void
    {
        switch ($direction) {
            case 'U':
                $head[0]++;
                break;
            case 'R':
                $head[1]++;
                break;
            case 'D':
                $head[0]--;
                break;
            case 'L':
                $head[1]--;
                break;
            default:
                throw new InvalidArgumentException();
        }
    }

    /**
     * @param int[] $head
     * @param int[] $knot
     */
    private static function moveKnotToHeadIfNeeded(array $head, array &$knot): void
    {
        if (abs($head[0] - $knot[0]) <= 1 && abs($head[1] - $knot[1]) <= 1) {
            return;
        }

        if ($head[0] === $knot[0]) {
            $knot[1] += $head[1] > $knot[1] ? 1 : -1;
        } elseif ($head[1] === $knot[1]) {
            $knot[0] += $head[0] > $knot[0] ? 1 : -1;
        } else {
            $knot[0] += $head[0] > $knot[0] ? 1 : -1;
            $knot[1] += $head[1] > $knot[1] ? 1 : -1;
        }
    }

    private static function partX(Vector $input, OutputInterface $output, int $knots): void
    {
        $grid = [];
        $rope = array_map(static fn () => [0, 0], range(1, $knots));

        foreach ($input as $line) {
            [$direction, $steps] = explode(' ', $line);

            for ($i = 0; $i < (int) $steps; $i++) {
                self::moveHead($rope[0], $direction);

                for ($j = 0; $j < $knots - 1; $j++) {
                    self::moveKnotToHeadIfNeeded($rope[$j], $rope[$j + 1]);
                }

                // Save tail pos in grid
                if (! array_key_exists($rope[$knots - 1][0], $grid)) {
                    $grid[$rope[$knots - 1][0]] = [];
                }

                $grid[$rope[$knots - 1][0]][$rope[$knots - 1][1]] = 1;
            }
        }

        $total = array_sum(array_map('array_sum', $grid));

        $output->writeln((string) $total);
    }

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        self::partX($input, $output, 2);
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        self::partX($input, $output, 10);
    }
}
