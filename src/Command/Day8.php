<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

use function array_map;
use function array_reverse;
use function array_slice;
use function count;
use function max;
use function str_split;

#[AsCommand(name: 'aoc:8')]
class Day8 extends AocCommand
{
    /** @var array<int[]> */
    private array $grid;
    /** @var array<int[]> */
    private array $gridTransposed;

    /**
     * @param Vector<string> $input
     *
     * @return array<int[]>
     */
    private static function readGrid(Vector $input): array
    {
        $grid = [];

        foreach ($input as $line) {
            $grid[] = array_map(static fn($x) => (int)$x, str_split($line));
        }

        return $grid;
    }

    /**
     * @param array<int[]> $grid
     *
     * @return array<int[]>
     */
    private static function transpose(array $grid): array
    {
        return array_map(null, ...$grid);
    }

    private function checkVisibility(int $i, int $j): bool
    {
        $tree = $this->grid[$i][$j];

        return self::checkRange($tree, $this->gridTransposed[$j], 0, $i - 1) || // top
            self::checkRange($tree, $this->grid[$i], $j + 1, count($this->grid) - 1) || // right
            self::checkRange($tree, $this->gridTransposed[$j], $i + 1, count($this->gridTransposed) - 1) || // bottom
            self::checkRange($tree, $this->grid[$i], 0, $j - 1); // left
    }

    /** @param int[] $line */
    private static function checkRange(int $tree, array $line, int $start, int $end): bool
    {
        for ($i = $start; $i <= $end; $i++) {
            if ($line[$i] >= $tree) {
                return false;
            }
        }

        return true;
    }

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $this->grid = self::readGrid($input);
        $this->gridTransposed = self::transpose($this->grid);

        $total = 0;

        for ($i = 1; $i < count($this->grid) - 1; $i++) {
            for ($j = 1; $j < count($this->gridTransposed) - 1; $j++) {
                if (!$this->checkVisibility($i, $j)) {
                    continue;
                }

                $total++;
            }
        }

        $total += count($this->grid) * 2 + count($this->gridTransposed) * 2 - 4;

        $output->writeln((string)$total);
    }

    private function calculateScenicScore(int $i, int $j): int
    {
        $tree = $this->grid[$i][$j];

        return self::calculateVisibleTrees($tree, array_reverse(array_slice($this->gridTransposed[$j], 0, $i))) * // top
            self::calculateVisibleTrees($tree, array_slice($this->grid[$i], $j + 1)) * // right
            self::calculateVisibleTrees($tree, array_slice($this->gridTransposed[$j], $i + 1)) * //bottom
            self::calculateVisibleTrees($tree, array_reverse(array_slice($this->grid[$i], 0, $j))); // left
    }

    /** @param int[] $trees */
    private static function calculateVisibleTrees(int $tree, array $trees): int
    {
        $total = 0;

        foreach ($trees as $lookATree) {
            $total++;

            if ($lookATree >= $tree) {
                break;
            }
        }

        return $total;
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        if (!isset($this->grid)) {
            $this->grid = self::readGrid($input);
            $this->gridTransposed = self::transpose($this->grid);
        }

        $best = 0;

        for ($i = 0; $i < count($this->grid) - 1; $i++) {
            for ($j = 0; $j < count($this->gridTransposed) - 1; $j++) {
                $best = max($best, $this->calculateScenicScore($i, $j));
            }
        }

        $output->writeln((string)$best);
    }
}
