<?php

declare(strict_types=1);

namespace App\Command;

use App\Entities\Monkey;
use Ds\Vector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'aoc:11')]
class Day11 extends AocCommand
{
    /**
     * @param Vector<string> $input
     *
     * @return Vector<Monkey>
     */
    private function makeMonkeys(Vector $input, bool $partOne): Vector
    {
        Monkey::$modFactor = 1;
        Monkey::$partOne = $partOne;

        $monkeys = new Vector();

        while ($monkeys->count() < $input->count() / 7) {
            $monkeyText = $input->slice(7 * $monkeys->count(), 6);
            $monkeys->push(Monkey::createFromText($monkeyText));
        }

        return $monkeys;
    }

    /** @param Vector<Monkey> $monkeys */
    private function doMonkeyBusiness(Vector $monkeys, int $rounds, OutputInterface $output): int
    {
        foreach (range(1, $rounds) as $round) {
            if ($output->isVerbose()) {
                $output->writeln((string)$round);
            }

            foreach ($monkeys as $monkey) {
                while ($monkey->hasItems()) {
                    [$monkeyNo, $item] = $monkey->throwItem();
                    $monkeys->get($monkeyNo)->catchItem($item);
                }
            }
        }

        $inspectionCounts = $monkeys->map(static fn($monkey) => $monkey->getInspections())->sorted()->reversed();

        return $inspectionCounts->get(0) * $inspectionCounts->get(1);
    }

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $monkeys = $this->makeMonkeys($input, true);

        $output->writeln((string)$this->doMonkeyBusiness($monkeys, 20, $output));
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $monkeys = $this->makeMonkeys($input, false);

        $output->writeln((string)$this->doMonkeyBusiness($monkeys, 10_000, $output));
    }
}
