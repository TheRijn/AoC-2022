<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:7')]
class Day7 extends AocCommand
{
    /**
     * @param Vector<string> $input
     * @return array<string, mixed>
     */
    private static function readTree(Vector $input): array
    {
        $root = [];
        $stack = [];
        $current = &$root;

        foreach ($input as $command) {
            if ($command === '$ cd /') {
                $stack = [];
                $current = &$root;
            } elseif ($command === '$ cd ..') {
                if (count($stack) === 0) {
                    $current = &$root;
                    continue;
                }

                $current = array_pop($stack);
            } elseif (str_starts_with($command, '$ cd')) {
                \Safe\preg_match('/\$ cd (?<name>[a-z]+)/', $command, $matches);
                $stack[] = &$current[$matches['name']];
                $current = &$current[$matches['name']];
            } elseif (str_starts_with($command, 'dir')) {
                \Safe\preg_match('/dir (?<name>[a-z]+)/', $command, $matches);
                $current[$matches['name']] = [];
            } elseif ($command === '$ ls') {
                continue;
            } else {
                \Safe\preg_match('/(?<size>\d+) (?<name>[a-z.]+)/', $command, $matches);
                $current[$matches['name']] = (int)$matches['size'];
            }
        }

        return $root;
    }

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $tree = self::readTree($input);
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
    }
}
