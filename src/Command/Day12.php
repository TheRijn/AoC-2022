<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:12')]
class Day12 extends AocCommand
{
    /** @var array<array<string>> */
    private array $grid = [];
    /** @var array<array<int>> */
    private array $distance = [];

    /** @var array{int, int} */
    private array $currentPos;

    private function readGrid(Vector $input): void
    {
        // Read grid
        foreach ($input as $line) {
            $this->grid[] = str_split($line);
        }

        // Set distances to inf
        foreach ($this->grid as $row) {
            $this->distance[] = array_map(static fn() => PHP_INT_MAX, $row);
        }
        // Find start
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $height) {
                if ($height === 'S') {
                    $this->currentPos = [$y, $x];
                    $this->distance[$y][$x] = 0;
                    break;
                }
            }
        }
    }

    private function getNeighbors(int $y, int $x): array
    {
    }

    private function doTheDijkstra()
    {
    }

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $this->readGrid($input);
        $this->doTheDijkstra();
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
    }
}
