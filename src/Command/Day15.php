<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Safe\Exceptions\PcreException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:15')]
class Day15 extends AocCommand
{
    private const RE = "/Sensor at x=(?<x_sensor>-?\d+), y=(?<y_sensor>-?\d+): " .
    "closest beacon is at x=(?<x_beacon>-?\d+), y=(?<y_beacon>-?\d+)/";

    private int $checkY = 2_000_000;
    private int $maxXY = 4_000_000;

    /** @var array<array{array{int, int}, int}> $sensors */
    private array $sensors;

    /** @var array<array{int, int}> $beacons */
    private array $beacons;

    /** @param Vector<string> $input
     * @throws PcreException
     */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $this->readCoordinates($input);
        $line = $this->drawLine($this->checkY);
        $output->writeln((string)count($line));
    }

    /**
     * @param Vector<string> $input
     * @throws PcreException
     */
    private function readCoordinates(Vector $input): void
    {
        $this->sensors = [];

        foreach ($input as $line) {
            \Safe\preg_match(self::RE, $line, $matches);
            $sensor = [(int)$matches["x_sensor"], (int)$matches["y_sensor"]];
            $beacon = [(int)$matches["x_beacon"], (int)$matches["y_beacon"]];

            $dist = self::manhattanDist($sensor, $beacon);

            $this->sensors[] = [$sensor, $dist];
            $this->beacons[] = $beacon;
        }

        if (count($this->sensors) <= 14) {
            $this->checkY = 10;
            $this->maxXY = 20;
        }
    }

    /** @return int[] */
    private function drawLine(int $y): array
    {
        $line = [];

        foreach ($this->sensors as [$sensor, $dist]) {
            // draw left
            $x = $sensor[0];
            while (self::manhattanDist($sensor, [$x, $y]) <= $dist) {
                $line[$x--] = 1;
            }

            // draw right
            $x = $sensor[0] + 1;
            while (self::manhattanDist($sensor, [$x, $y]) <= $dist) {
                $line[$x++] = 1;
            }
        }

        // remove beacons and sensors
        foreach ($this->sensors as [$sensor]) {
            if ($sensor[1] === $this->checkY) {
                unset($line[$sensor[0]]);
            }
        }
        foreach ($this->beacons as $beacon) {
            if ($beacon[1] === $this->checkY) {
                unset($line[$beacon[0]]);
            }
        }

        return $line;
    }


    /**
     * @param array{int, int} $first
     * @param array{int, int} $second
     */
    private static function manhattanDist(array $first, array $second): int
    {
        return abs($first[0] - $second[0]) +
            abs($first[1] - $second[1]);
    }

    /**
     * @param Vector<string> $input
     * @throws PcreException
     */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $this->readCoordinates($input);

        $progressBar = new ProgressBar($output);
        $progressBar->setFormat('very_verbose');


        $output->writeln((string)($x * 4_000_000 + $y));
    }
}
