<?php

declare(strict_types=1);

namespace N98\Magento\Command\Database;

use Carbon\Carbon;
use N98\Magento\Command\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Tester\CommandTester;

final class StatusCommandTest extends TestCase
{
    /**
     * @return CommandTester
     * @param array<string, string> $options
     */
    private function getCommand(array $options)
    {
        $application = $this->getApplication();
        $application->add(new StatusCommand());

        $command = $this->getApplication()->find('db:status');

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array_merge(['command' => $command->getName()], $options),
        );
        return $commandTester;
    }

    public function testExecute()
    {
        $commandTester = $this->getCommand(['--format' => 'csv']);
        $display = $commandTester->getDisplay();

        $this->assertStringContainsString('Threads_connected', $display);
        $this->assertStringContainsString('Innodb_buffer_pool_wait_free', $display);
        $this->assertStringContainsString('InnoDB Buffer Pool hit', $display);
        $this->assertStringContainsString('Full table scans', $display);
    }

    public function testSearch()
    {
        $commandTester = $this->getCommand(['--format' => 'csv', 'search'   => 'Innodb%']);

        $display = $commandTester->getDisplay();

        $this->assertStringContainsString('Innodb_buffer_pool_read_ahead_rnd', $display);
        $this->assertStringContainsString('Innodb_buffer_pool_wait_free', $display);
        $this->assertStringContainsString('InnoDB Buffer Pool hit', $display);
        $this->assertStringContainsString('Innodb_dblwr_pages_written', $display);
        $this->assertStringContainsString('Innodb_os_log_written', $display);
    }

    public function testRounding()
    {
        $commandTester = $this->getCommand(['--format'   => 'csv', '--rounding' => '2', 'search'     => '%size%']);
        $this->assertMatchesRegularExpression('~Innodb_page_size,[0-9\.]+K,~', $commandTester->getDisplay());
    }

    /**
     * @dataProvider provideUptimes
     */
    public function testTimeElapsedString(int $seconds, bool $full, string $expected)
    {
        Carbon::setTestNow('2026-11-03 12:00:00');

        try {
            $method = new ReflectionMethod(StatusCommand::class, 'timeElapsedString');
            $this->assertSame($expected, $method->invoke(new StatusCommand(), $seconds, $full));
        } finally {
            Carbon::setTestNow();
        }
    }

    /**
     * @return \Iterator<string, array{int, bool, string}>
     */
    public static function provideUptimes(): \Iterator
    {
        $day = 86400;
        yield 'just now' => [0, false, 'just now'];
        yield 'seconds' => [45, false, '45 seconds ago'];
        yield 'days before hours' => [3 * $day + 2 * 3600, false, '3 days ago'];
        yield 'weeks before days' => [10 * $day, false, '1 week ago'];
        yield 'full' => [10 * $day + 3600 + 1, true, '1 week, 3 days, 1 hour, 1 second ago'];
        yield 'singular and plural' => [2 * 7 * $day + $day, true, '2 weeks, 1 day ago'];
    }
}
