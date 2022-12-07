<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Stack;
use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:7')]
class Day7 extends AocCommand
{
    private static function readTree(Vector $input): array
    {
        $root = [];
        $stack = [];
        $current = &$root;

        foreach ($input as $command) {
            if ($command === '$ cd /') {
                $stack = [];
                $current = $root;

            } elseif ($command === '$ cd ..') {
                if (count($stack) === 0) {
                    $current = $root;
                    continue;
                }

                $current = array_pop($stack);

            } elseif (str_starts_with($command, '$ cd')) {
                preg_match('/\$ cd (?<name>[a-z]+)/', $command, $matches);
                dump($current, $matches['name']);
                $current = $current[$matches['name']];
                $stack[] = $current;

            } elseif (str_starts_with($command, 'dir')) {
                preg_match('/dir (?<name>[a-z]+)/', $command, $matches);
                $current[$matches['name']] = [];

            } elseif ($command === '$ ls') {
                continue;

            } else {
                preg_match('/(?<size>\d+) (?<name>[a-z.]+)/', $command, $matches);
                $current[$matches['name']] = (int) $matches['size'];

            }
        }

        return $root;
    }

    /** @param Vector $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $tree = self::readTree($input);
        dump($tree);
    }

    /** @param Vector $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
    }
}
