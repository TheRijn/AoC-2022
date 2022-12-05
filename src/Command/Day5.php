<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

use function array_key_exists;
use function array_pop;
use function array_reverse;
use function array_slice;
use function preg_match;
use function preg_match_all;
use function str_split;

#[AsCommand(name: 'aoc:5')]
class Day5 extends AocCommand
{
    private const RE1 = '/((?P<letter>\[[A-Z]\]|   )(?> |$))+/U';
    private const RE2 = '/move (?P<amount>\d+) from (?P<from>\d+) to (?P<to>\d+)/';

    private const STACKS = 3; //3 for example 8 for realsies

    /**
     * @param string[] $input
     *
     * @return array<string[]>
     */
    private function readStacks(array $input): array
    {
        $stacks = [];

        foreach (array_reverse(array_slice($input, 0, self::STACKS)) as $line) {
            preg_match_all(self::RE1, $line, $matches);
            $letters = $matches['letter'];

            foreach ($letters as $key => $value) {
                if ($value === '   ') {
                    continue;
                }

                if (! array_key_exists($key, $stacks)) {
                    $stacks[] = [];
                }

                $stacks[$key][] = str_split($value)[1];
            }
        }

        return $stacks;
    }

    /** @param string[] $input */
    protected function partOne(array $input, OutputInterface $output): void
    {
        $stacks = $this->readStacks($input);

        foreach (array_slice($input, self::STACKS + 2) as $line) {
            preg_match(self::RE2, $line, $matches);

            $amount = (int) $matches['amount'];
            $from   = (int) $matches['from'] - 1;
            $to     = (int) $matches['to'] - 1;

            for ($i = 0; $i < $amount; $i++) {
                $stacks[$to][] = array_pop($stacks[$from]);
            }
        }

        foreach ($stacks as $stack) {
            $output->write(array_pop($stack));
        }

        $output->writeln('');
    }

    /** @param string[] $input */
    protected function partTwo(array $input, OutputInterface $output): void
    {
        $stacks = $this->readStacks($input);

        foreach (array_slice($input, self::STACKS + 2) as $line) {
            preg_match(self::RE2, $line, $matches);

            $amount = (int) $matches['amount'];
            $from   = (int) $matches['from'] - 1;
            $to     = (int) $matches['to'] - 1;

            $temp = [];

            for ($i = 0; $i < $amount; $i++) {
                $temp[] = array_pop($stacks[$from]);
            }

            for ($i = 0; $i < $amount; $i++) {
                $stacks[$to][] = array_pop($temp);
            }
        }

        foreach ($stacks as $stack) {
            $output->write(array_pop($stack));
        }

        $output->writeln('');
    }
}
