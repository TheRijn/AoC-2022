<?php

declare(strict_types=1);

namespace App\Command;

use Ds\PriorityQueue;
use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:12')]
/**
 * @phpstan-type Position array{int, int}
 */
class Day12 extends AocCommand
{
    /** @var array<int[]> */
    private array $grid = [];
    /** @var array<int[]> */
    private array $distances = [];

    /** @phpstan-var Position */
    private $start;

    /** @phpstan-var Position */
    private $end;

    /** @phpstan-var PriorityQueue<array{int, Position}> $queue */
    private PriorityQueue $queue;

    private int $height;
    private int $width;

    /** @param Vector<string> $input */
    private function readAndDoTheDijkstra(Vector $input): int
    {
        // Read grid
        foreach ($input as $y => $line) {
            $row = [];
            foreach (str_split($line) as $x => $cell) {
                if ($cell === 'S') {
                    $this->start = [$y, $x];
                    $value = 0;
                } elseif ($cell === 'E') {
                    $this->end = [$y, $x];
                    $value = 25;
                } else {
                    $value = ord($cell) - ord('a');
                }

                $row[] = $value;
            }
            $this->grid[] = $row;
        }

        $this->height = count($this->grid);
        $this->width = count($this->grid[0]);

        // Set distances to inf
        foreach ($this->grid as $row) {
            $this->distances[] = array_map(static fn() => PHP_INT_MAX, $row);
        }

        $this->queue = new PriorityQueue();


        $this->queue->push([0, $this->start], 0);
        $this->setDistance($this->start, 0);

        while (!$this->queue->isEmpty()) {
            [$distU, $u] = $this->queue->pop();

            if ($this->getDistance($u) < $distU) {
                continue;
            }

            foreach ($this->getNeighbors($u) as $v) {
                if ($this->getDistance($v) > $this->getDistance($u) + 1) {
                    $this->setDistance($v, $this->getDistance($u) + 1);
                    $this->addToQueue($v, $this->getDistance($v));
                }
            }
        }

        return $this->getDistance($this->end);
    }

    /** @phpstan-param Position $position */
    private function addToQueue($position, int $distance): void
    {
        $prioDistance = -$distance;

        $this->queue->push([$distance, $position], $prioDistance);
    }

    /** @phpstan-param Position $position */
    private function getHeight($position): int
    {
        return $this->grid[$position[0]][$position[1]];
    }

    /** @phpstan-param Position $position */
    private function getDistance($position): int
    {
        return $this->distances[$position[0]][$position[1]];
    }

    /** @phpstan-param Position $position */
    private function setDistance($position, int $distance): void
    {
        $this->distances[$position[0]][$position[1]] = $distance;
    }

    /**
     * @phpstan-param Position $position
     *
     * @phpstan-return Position[]
     */
    public function getNeighbors($position): array
    {
        $neighbors = [];

        $possibleNeighbors = [];

        foreach (range($position[0] - 1, $position[0] + 1) as $y) {
            foreach (range($position[1] - 1, $position[1] + 1) as $x) {
                if (
                    ((abs($position[0] - $y) >= 1) && (abs($position[1] - $x) >= 1)) ||
                    $y < 0 || $x < 0 || $y >= $this->height || $x >= $this->width ||
                    ($y === $position[0] && $x === $position[1])
                ) {
                    continue;
                }
                $possibleNeighbors[] = [$y, $x];
            }
        }

        $height = $this->getHeight($position);

        foreach ($possibleNeighbors as $neighbor) {
            $newHeight = $this->getHeight($neighbor);
            if ($newHeight <= $height + 1) {
                $neighbors[] = $neighbor;
            }
        }

        return $neighbors;
    }


    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $output->writeln((string)$this->readAndDoTheDijkstra($input));
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
    }
}
