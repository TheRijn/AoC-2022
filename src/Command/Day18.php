<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Stack;
use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:18')]
class Day18 extends AocCommand
{
    private const AIR = 0;
    private const LAVA = 1;
    private const OUTSIDE_AIR = 2;

    /** @var array{int, int, int} $lava */
    private array $lava;

    /** @var int[][][] $lavaGrid */
    private array $lavaGrid;

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $this->lava = [];

        foreach ($input as $line) {
            $this->lava[] = array_map(static fn($x) => (int)$x, explode(',', $line));
        }

        $this->coordsToGrid(self::OUTSIDE_AIR);

        $area = $this->countSidesFacing();

        $output->writeln((string)$area);
    }

    private function coordsToGrid(int $fill = self::AIR): void
    {
        $this->lavaGrid = array_fill(0, 21, array_fill(0, 21, array_map(static fn() => $fill, range(0, 21))));
        foreach ($this->lava as [$x, $y, $z]) {
            $this->lavaGrid[$x][$y][$z] = self::LAVA;
        }
    }

    private function countSidesFacing(): int
    {
        $total = 0;

        foreach ($this->lava as [$x, $y, $z]) {
            $part = $this->checkSurroundings($x, $y, $z);
            $total += $part;
        }

        return $total;
    }

    private function checkSurroundings(int $x, int $y, int $z): int
    {
        $total = 6;

        foreach (range(max(0, $x - 1), min(20, $x + 1)) as $x2) {
            foreach (range(max(0, $y - 1), min(20, $y + 1)) as $y2) {
                foreach (range(max(0, $z - 1), min(20, $z + 1)) as $z2) {
                    if (
                        ($this->lavaGrid[$x2][$y2][$z2] === self::LAVA ||
                            $this->lavaGrid[$x2][$y2][$z2] === self::AIR) &&
                        (abs($x - $x2) +
                            abs($y - $y2) +
                            abs($z - $z2) === 1)
                    ) {
                        $total--;
                    }
                }
            }
        }

        return $total;
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $this->lava = [];

        foreach ($input as $line) {
            $this->lava[] = array_map(static fn($x) => (int)$x, explode(',', $line));
        }

        $this->coordsToGrid();
        $this->dfsFillOutsideAir();
        $area = $this->countSidesFacing();

        $output->writeln((string)$area);
    }

    private function dfsFillOutsideAir(): void
    {
        $pos = [0, 0, 0];

        $stack = new Stack([$pos]);
        while (!$stack->isEmpty()) {
            [$x, $y, $z] = $stack->pop();

            foreach (range(max(0, $x - 1), min(20, $x + 1)) as $x2) {
                foreach (range(max(0, $y - 1), min(20, $y + 1)) as $y2) {
                    foreach (range(max(0, $z - 1), min(20, $z + 1)) as $z2) {
                        if (
                            (abs($x - $x2) +
                                abs($y - $y2) +
                                abs($z - $z2) !== 1)
                        ) {
                            continue;
                        }

                        if ($this->lavaGrid[$x2][$y2][$z2] === self::AIR) {
                            $this->lavaGrid[$x2][$y2][$z2] = self::OUTSIDE_AIR;
                            $stack->push([$x2, $y2, $z2]);
                        }
                    }
                }
            }
        }
    }
}
