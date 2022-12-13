<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Safe\Exceptions\JsonException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:13')]
/**
 * @phpstan-type Package array<Package|int>
 */
class Day13 extends AocCommand
{
    /**
     * @phpstan-param Package|int $left
     * @phpstan-param Package|int $right
     * @return bool|null
     */
    private static function compairs(array|int $left, array|int $right): ?bool
    {
        // Both integers
        if (is_int($left) && is_int($right)) {
            if ($left === $right) {
                return null;
            }

            return $left < $right;
        }

        // Both lists
        if (is_array($left) && is_array($right)) {
            $smallestCount = min(count($left), count($right));

            for ($i = 0; $i < $smallestCount; $i++) {
                $result = self::compairs($left[$i], $right[$i]);

                if ($result !== null) {
                    return $result;
                }
            }

            if (count($left) === count($right)) {
                return null;
            }

            return count($left) < count($right);
        }

        // Exactly one integer
        return self::compairs(
            is_array($left) ? $left : [$left],
            is_array($right) ? $right : [$right]
        );
    }

    /** @param Vector<string> $input
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
            $result = self::compairs($left, $right);

            if ($result === true) {
                $rightPackages[] = $index + 1;
            }
        }

        $output->writeln((string)array_sum($rightPackages));
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
    }
}
