<?php

declare(strict_types=1);

namespace App\Command;

use Ds\Stack;
use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

use function preg_match;
use function preg_match_all;
use function str_split;

#[AsCommand(name: 'aoc:5')]
class Day5 extends AocCommand
{
    private const RE1 = '/((?P<letter>\[[A-Z]\]|   )(?> |$))+/U';
    private const RE2 = '/move (?P<amount>\d+) from (?P<from>\d+) to (?P<to>\d+)/';

    private int $stackLines = 3;

    /**
     * @param string[] $input
     *
     * @return Vector<Stack<string>>
     */
    private function readStacks(Vector $input): Vector
    {
        if ($input->count() > 10) {
            $this->stackLines = 8;
        }

        $stacks = new Vector();

        foreach ($input->slice(0, $this->stackLines)->reversed() as $line) {
            preg_match_all(self::RE1, $line, $matches);
            $letters = $matches['letter'];

            foreach ($letters as $key => $value) {
                if ($value === '   ') {
                    continue;
                }

                if ($stacks->count() <= $key) {
                    $stacks[] = new Stack();
                }

                $stacks[$key][] = str_split($value)[1];
            }
        }

        return $stacks;
    }

    /** @param string[] $input */
    protected function partOne(array $input, OutputInterface $output): void
    {
        $inputVector = new Vector($input);
        $stacks      = $this->readStacks($inputVector);

        foreach ($inputVector->slice($this->stackLines + 2) as $line) {
            preg_match(self::RE2, $line, $matches);

            $amount = (int) $matches['amount'];
            $from   = (int) $matches['from'] - 1;
            $to     = (int) $matches['to'] - 1;

            for ($i = 0; $i < $amount; $i++) {
                $stacks[$to][] = $stacks[$from]->pop();
            }
        }

        /** @var Stack<string> $stack */
        foreach ($stacks as $stack) {
            $output->write($stack->peek());
        }

        $output->writeln('');
    }

    /** @param string[] $input */
    protected function partTwo(array $input, OutputInterface $output): void
    {
        $inputVector = new Vector($input);
        $stacks      = $this->readStacks($inputVector);

        foreach ($inputVector->slice($this->stackLines + 2) as $line) {
            preg_match(self::RE2, $line, $matches);

            $amount = (int) $matches['amount'];
            $from   = (int) $matches['from'] - 1;
            $to     = (int) $matches['to'] - 1;

            $temp = new Stack();

            for ($i = 0; $i < $amount; $i++) {
                $temp[] = $stacks[$from]->pop();
            }

            for ($i = 0; $i < $amount; $i++) {
                $stacks[$to][] = $temp->pop();
            }
        }

        /** @var Stack<string> $stack */
        foreach ($stacks as $stack) {
            $output->write($stack->peek());
        }

        $output->writeln('');
    }
}
