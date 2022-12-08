<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:8')]
class Day8 extends AocCommand
{
    private array $grid;
    private array $grid_transposed;

    private static function readGrid(Vector $input) : array
    {
        $grid = [];

        foreach ($input as $line) {
            $grid[] = array_map(static fn($x) => (int) $x, str_split($line));
        }

        return $grid;
    }

    private static function transpose(array $grid) : array
    {
        return array_map(null, ...$grid);
    }

    private function checkVisibility(int $i, int $j) : bool
    {
        $tree = $this->grid[$i][$j];
        return self::checkRange($this->grid_transposed[$j], 0, $i - 1, $tree) || // top
            self::checkRange($this->grid[$i], $j + 1, count($this->grid) - 1, $tree) || // right
            self::checkRange($this->grid_transposed[$j], $i + 1, count($this->grid_transposed) - 1, $tree) || // bottom
            self::checkRange($this->grid[$i], 0, $j - 1, $tree); // left
    }

    private static function checkRange(array $line, int $start, int $end, int $tree) : bool
    {
        for ($i = $start; $i <= $end; $i++) {
            if ($line[$i] >= $tree) {
                return false;
            }
        }

        return true;
    }

    /** @param Vector $input */
    protected function partOne(Vector $input, OutputInterface $output) : void
    {
        $this->grid            = self::readGrid($input);
        $this->grid_transposed = self::transpose($this->grid);

        $total = 0;

        for ($i = 1; $i < count($this->grid) - 1; $i++) {
            for ($j = 1; $j < count($this->grid_transposed) - 1; $j++) {
                if ($this->checkVisibility($i, $j)) {
                    $total++;
                }
            }
        }

        $total += count($this->grid) * 2 + count($this->grid_transposed) * 2 - 4;

        $output->writeln((string) $total);
    }

    /** @param Vector $input */
    protected function partTwo(Vector $input, OutputInterface $output) : void
    {
    }
}
