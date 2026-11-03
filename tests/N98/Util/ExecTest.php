<?php

declare(strict_types=1);

namespace N98\Util;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class ExecTest
 *
 * @package N98\Util
 */
final class ExecTest extends TestCase
{
    public function testCommandOnly()
    {
        Exec::run('echo test', $output, $actual);

        $this->assertSame(0, $actual);
    }

    public function testFullParameters()
    {
        Exec::run('echo test', $commandOutput, $returnCode);

        $this->assertSame(Exec::CODE_CLEAN_EXIT, $returnCode);
        $this->assertStringStartsWith('test', $commandOutput);
    }

    public function testException()
    {
        $this->expectException(RuntimeException::class);
        Exec::run('foobar');
    }

    public function testExceptionMessageMasksPassword()
    {
        $command = 'false -h ' . escapeshellarg('localhost') . ' -u' . escapeshellarg('root')
            . ' -p' . escapeshellarg("se'cr et") . ' ' . escapeshellarg('magento');

        try {
            Exec::run($command);
            $this->fail('Expected RuntimeException was not thrown');
        } catch (RuntimeException $runtimeException) {
            $message = $runtimeException->getMessage();
            $this->assertStringNotContainsString('cr et', $message);
            $this->assertStringContainsString("-u'root' -p'***' 'magento'", $message);
        }
    }
}
