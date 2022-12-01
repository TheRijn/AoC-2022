<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

use function explode;
use function file_get_contents;
use function sprintf;

abstract class AocBase extends KernelTestCase
{
    protected const DAY = null;
    protected CommandTester $commandTester;

    protected function setUp(): void
    {
        $kernel      = self::bootKernel();
        $application = new Application($kernel);

        $command             = $application->find(sprintf('aoc:%d', static::DAY));
        $this->commandTester = new CommandTester($command);

        $fileContent = file_get_contents(sprintf('input/%d/example.input', static::DAY));
        $this->commandTester->setInputs(explode("\n", $fileContent));
    }

    public function testPartOne(): void
    {
        $this->commandTester->execute(['--one' => true]);
        $this->commandTester->assertCommandIsSuccessful();

        $expected = file_get_contents(sprintf('input/%d/one.output', static::DAY));

        $this->assertEquals($expected, $this->commandTester->getDisplay());
    }

    public function testPartTwo(): void
    {
        $this->commandTester->execute(['--two' => true]);
        $this->commandTester->assertCommandIsSuccessful();

        $expected = file_get_contents(sprintf('input/%d/two.output', static::DAY));

        $this->assertEquals($expected, $this->commandTester->getDisplay());
    }
}
