<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Safe\Exceptions\PcreException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:14')]
class Day14 extends AocCommand
{
    private const SANDSOURCE = [500, 0];

    private const AIR = 0;
    private const ROCK = 1;
    private const SAND = 2;

    /** @var int[][] */
    private array $cave = [];
    private int $highestY;

    /** @param Vector<string> $input
     * @throws PcreException
     */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $this->readCave($input);
        $this->highestY = max(array_keys($this->cave));
        $output->writeln((string)$this->runSimulationWithABottomlessPit());
    }

    /** @param Vector<string> $input
     * @throws PcreException
     */
    private function readCave(Vector $input): void
    {
        foreach ($input as $pathString) {
            $path = self::stringToPath($pathString);
            $this->drawPath($path);
        }
    }

    /** @return array<array{int, int}>
     * @throws PcreException
     */
    private static function stringToPath(string $pathString): array
    {
        \Safe\preg_match_all("/(?<x>\d+),(?<y>\d+)/", $pathString, $matches, PREG_SET_ORDER);

        $path = [];

        foreach ($matches as $match) {
            $path[] = [$match['x'], $match['y']];
        }

        return $path;
    }

    /** @param array<array{int, int}> $path */
    private function drawPath(array $path): void
    {
        for ($i = 0; $i < count($path) - 1; $i++) {
            $this->drawPathPart($path[$i], $path[$i + 1]);
        }
    }

    /**
     * @param array{int, int} $from
     * @param array{int, int} $to
     */
    private function drawPathPart(array $from, array $to): void
    {
        $xRange = range($from[0], $to[0]);
        $yRange = range($from[1], $to[1]);


        if (count($xRange) > 1) {
            $y = $from[1];
            foreach ($xRange as $x) {
                $this->placePoint([$x, $y], self::ROCK);
            }
        } else {
            $x = $from[0];
            foreach ($yRange as $y) {
                $this->placePoint([$x, $y], self::ROCK);
            }
        }
    }

    /** @param array{int, int} $position */
    private function placePoint(array $position, int $type): void
    {
        [$x, $y] = $position;
        if (!array_key_exists($y, $this->cave)) {
            $this->cave[$y] = [];
        }
        $this->cave[$y][$x] = $type;
    }

    private function runSimulationWithABottomlessPit(): int
    {
        $unitsOfSand = 0;

        while (true) {
            $sandPos = self::SANDSOURCE;

            $this->placePoint($sandPos, self::SAND);

            while ($sandPos[1] < $this->highestY) {
                if (!$this->moveSand($sandPos)) {
                    break;
                }
            }

            if ($sandPos[1] >= $this->highestY) {
                // Sand is falling
                break; // Out of while true;
            }

            $unitsOfSand++;
        }

        return $unitsOfSand;
    }

    /** @param array{int, int} $sandPos */
    private function moveSand(array &$sandPos): bool
    {
        // move down
        if ($this->getPosition([$sandPos[0], $sandPos[1] + 1]) === self::AIR) {
            $this->placePoint($sandPos, self::AIR);
            $this->placePoint([$sandPos[0], $sandPos[1] + 1], self::SAND);

            $sandPos = [$sandPos[0], $sandPos[1] + 1];
            return true;
        }

        // move left
        if ($this->getPosition([$sandPos[0] - 1, $sandPos[1] + 1]) === self::AIR) {
            $this->placePoint($sandPos, self::AIR);
            $this->placePoint([$sandPos[0] - 1, $sandPos[1] + 1], self::SAND);

            $sandPos = [$sandPos[0] - 1, $sandPos[1] + 1];
            return true;
        }

        // move right
        if ($this->getPosition([$sandPos[0] + 1, $sandPos[1] + 1]) === self::AIR) {
            $this->placePoint($sandPos, self::AIR);
            $this->placePoint([$sandPos[0] + 1, $sandPos[1] + 1], self::SAND);

            $sandPos = [$sandPos[0] + 1, $sandPos[1] + 1];
            return true;
        }

        // not possible
        return false;
    }

    /** @param array{int, int} $position */
    private function getPosition(array $position): int
    {
        [$x, $y] = $position;
        if (array_key_exists($y, $this->cave) && array_key_exists($x, $this->cave[$y])) {
            return $this->cave[$y][$x];
        }

        return self::AIR;
    }

    /** @param Vector<string> $input
     * @throws PcreException
     */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $this->readCave($input);
        $this->highestY = max(array_keys($this->cave));
        $output->writeln((string)$this->runSimulationWithAFloor());
    }

    private function runSimulationWithAFloor(): int
    {
        $unitsOfSand = 0;

        while (true) {
            $sandPos = self::SANDSOURCE;

            $this->placePoint($sandPos, self::SAND);

            while ($sandPos[1] !== $this->highestY + 1) {
                if (!$this->moveSand($sandPos)) {
                    break;
                }
            }
            $unitsOfSand++;

            if ($this->getPosition(self::SANDSOURCE) === self::SAND) {
                break;
            }
        }

        return $unitsOfSand;
    }
}
