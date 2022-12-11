<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Set;
use Ds\Vector;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

use function str_split;

#[AsCommand(name: 'aoc:6')]
class Day6 extends AocCommand
{
    /**
     * @param Vector<string> $string
     */
    private static function findUniquePart(Vector $string, int $length): int
    {
        for ($i = $length; $length <= $string->count(); $i++) {
            if ((new Set($string->slice($i - $length, $length)))->count() === $length) {
                return $i;
            }
        }

        throw new InvalidArgumentException();
    }

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $inputChars = new Vector(str_split($input[0]));

        $output->writeln((string)self::findUniquePart($inputChars, 4));
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $inputChars = new Vector(str_split($input[0]));

        $output->writeln((string)self::findUniquePart($inputChars, 14));
    }
}
