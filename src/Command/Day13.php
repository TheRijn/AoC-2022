<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Safe\Exceptions\JsonException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:13')]
class Day13 extends AocCommand
{
    /**
     * @param mixed[]|int $left
     * @param mixed[]|int $right
     * @return int
     */
    private static function compairs(array|int $left, array|int $right): int
    {
        // Both integers
        if (is_int($left) && is_int($right)) {
            return $left <=> $right;
        }

        // Both lists
        if (is_array($left) && is_array($right)) {
            $smallestCount = min(count($left), count($right));

            for ($i = 0; $i < $smallestCount; $i++) {
                if (0 !== $result = self::compairs($left[$i], $right[$i])) {
                    return $result;
                }
            }

            return count($left) <=> count($right);
        }

        // Exactly one integer
        return self::compairs(
            is_array($left) ? $left : [$left],
            is_array($right) ? $right : [$right]
        );
    }

    /**
     * @param Vector<string> $input
     * @throws JsonException
     */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $pairs = [];
        for ($i = 0; $i < $input->count(); $i += 3) {
            $pairs[] = [\Safe\json_decode($input->get($i)), \Safe\json_decode($input->get($i + 1))];
        }

        $rightPackages = [];
        foreach ($pairs as $index => [$left, $right]) {
            if (self::compairs($left, $right) === -1) {
                $rightPackages[] = $index + 1;
            }
        }

        $output->writeln((string)array_sum($rightPackages));
    }

    /**
     * @param Vector<string> $input
     * @throws JsonException
     */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $packets = [[[2]], [[6]]];

        for ($i = 0; $i < $input->count(); $i += 3) {
            $packets[] = \Safe\json_decode($input->get($i));
            $packets[] = \Safe\json_decode($input->get($i + 1));
        }

        usort($packets, self::compairs(...));

        $output->writeln(
            (string)(
                (array_search([[2]], $packets) + 1) *
                (array_search([[6]], $packets) + 1)
            )
        );
    }
}
