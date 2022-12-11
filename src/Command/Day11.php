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
    /** @return Vector<Monkey> */
    private function makeMonkeys(Vector $input, bool $partOne): Vector
    {
        $monkeys = new Vector();

        while ($monkeys->count() < $input->count() / 7) {
            $monkeyText = $input->slice(7 * $monkeys->count(), 6);
            $monkeys->push(Monkey::createFromText($monkeyText, $partOne));
        }

        return $monkeys;
    }

    /** @param Vector<string> $input */
    protected function partOne(Vector $input, OutputInterface $output): void
    {
        $monkeys = $this->makeMonkeys($input, true);

        foreach (range(1, 20) as $round) {
            if ($output->isVerbose()) {
                $output->writeln((string)$round);
            }
            /** @var Monkey[] $monkeys */
            foreach ($monkeys as $monkey) {
                while ($monkey->hasItems()) {
                    [$monkeyNo, $item] = $monkey->throwItem();
                    $monkeys[$monkeyNo]->catchItem($item);
                }
            }
        }

        /** @var Monkey $monkey */
        $inspectionCounts = $monkeys->map(static fn($monkey) => $monkey->getInspections())->sorted()->reversed();

        $monkeyBusiness = $inspectionCounts[0] * $inspectionCounts[1];

        $output->writeln((string)$monkeyBusiness);
    }

    /** @param Vector<string> $input */
    protected function partTwo(Vector $input, OutputInterface $output): void
    {
        $monkeys = $this->makeMonkeys($input, false);

        foreach (range(1, 10_000) as $round) {
            if ($output->isVerbose()) {
                $output->writeln((string)$round);
            }

            /** @var Monkey[] $monkeys */
            foreach ($monkeys as $monkey) {
                while ($monkey->hasItems()) {
                    [$monkeyNo, $item] = $monkey->throwItem();
                    $monkeys[$monkeyNo]->catchItem($item);
                }
            }
        }

        /** @var Monkey $monkey */
        $inspectionCounts = $monkeys->map(static fn($monkey) => $monkey->getInspections())->sorted()->reversed();


        $monkeyBusiness = $inspectionCounts[0] * $inspectionCounts[1];


        $output->writeln((string)$monkeyBusiness);
    }
}
