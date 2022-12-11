<?php

namespace App\Entities;

use Ds\Queue;
use Ds\Vector;

final class Monkey
{
    public static int $modFactor = 1;
    private int $inspections = 0;
    public static bool $partOne = true;


    public function __construct(
        public Queue $items,
        public string $operation,
        private readonly int $test,
        private readonly int $ifTrue,
        private readonly int $ifFalse,
    ) {
    }

    public static function createFromText(Vector $text): self
    {
        $startingItems = new Queue(
            array_map(static fn($in) => (int)$in, explode(', ', explode(': ', $text[1])[1]))
        );

        $operation = str_replace(['new', 'old'], ["\$new", "\$old"], explode(': ', $text[2])[1]) . ';';
        $test = (int)explode(' ', $text[3])[5];
        $ifTrue = (int)(explode(' ', $text[4])[9]);
        $ifFalse = (int)(explode(' ', $text[5])[9]);

        self::$modFactor *= $test;

        return new self(
            $startingItems,
            $operation,
            $test,
            $ifTrue,
            $ifFalse,
        );
    }

    public function hasItems(): bool
    {
        return !$this->items->isEmpty();
    }

    private function testWorryLevelAndChooseNextMonkey(int $item): int
    {
        return $item % $this->test === 0 ? $this->ifTrue : $this->ifFalse;
    }

    /** @return int[] */
    public function throwItem(): array
    {
        $item = $this->items->pop();
        $this->inspectAndGetBored($item);
        $nextMonkey = $this->testWorryLevelAndChooseNextMonkey($item);

        return [$nextMonkey, $item];
    }

    public function catchItem(int $item): void
    {
        $this->items->push($item);
    }

    private function inspectAndGetBored(int &$old): void
    {
        $this->inspections++;
        $new = 0;
        eval($this->operation);
        $old = self::$partOne ? intdiv($new, 3) : ($new % self::$modFactor);
    }

    public function getInspections(): int
    {
        return $this->inspections;
    }
}
