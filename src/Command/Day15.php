<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Safe\Exceptions\PcreException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:15')]
class Day15 extends AocCommand
{
    private const RE = "/Sensor at x=(?<x_sensor>-?\d+), y=(?<y_sensor>-?\d+): " .
    "closest beacon is at x=(?<x_beacon>-?\d+), y=(?<y_beacon>-?\d+)/";

    private int $checkY = 2_000_000;

    /** @var array<array{sensor: array{int, int}, beacon: array{int, int}}> $sensors */
    private array $sensors;

    /** @param Vector<string> $input
     * @throws PcreException
     */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $this->readCoordinates($input);
        $line = $this->drawLine($this->checkY);
        ksort($line);
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
            $this->sensors[] = [
                "sensor" => [(int)$matches["x_sensor"], (int)$matches["y_sensor"]],
                "beacon" => [(int)$matches["x_beacon"], (int)$matches["y_beacon"]],
            ];
        }

        if (count($this->sensors) <= 14) {
            $this->checkY = 10;
        }
    }

    /** @return int[] */
    private function drawLine(int $y): array
    {
        $line = [];

        foreach ($this->sensors as $sensorSet) {
            $sensor = $sensorSet['sensor'];
            $dist = self::manhattanDist($sensorSet);

            // draw left
            $x = $sensor[0];
            while (self::manhattanDist(['sensor' => $sensor, 'beacon' => [$x, $y]]) <= $dist) {
                $line[$x--] = 1;
            }

            // draw right
            $x = $sensor[0] + 1;
            while (self::manhattanDist(['sensor' => $sensor, 'beacon' => [$x, $y]]) <= $dist) {
                $line[$x++] = 1;
            }
        }

        // remove beacons and sensors
        foreach ($this->sensors as $sensorSet) {
            foreach ($sensorSet as $thingy) {
                if ($thingy[1] === $this->checkY) {
                    unset($line[$thingy[0]]);
                }
            }
        }

        return $line;
    }

    /** @param array{sensor: array{int, int}, beacon: array{int, int}} $coordinates */
    private static function manhattanDist(array $coordinates): int
    {
        return abs($coordinates["sensor"][0] - $coordinates["beacon"][0]) +
            abs($coordinates["sensor"][1] - $coordinates["beacon"][1]);
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
    }
}
