<?php

namespace PhpSchool\CliMenuTest\IO;

use PhpSchool\CliMenu\IO\BufferedOutput;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class BufferedOutputTest extends TestCase
{
    public function testFetch() : void
    {
        $output = new BufferedOutput;
        $output->write('one');

        static::assertEquals('one', $output->fetch());
    }

    public function testFetchWithMultipleWrites() : void
    {
        $output = new BufferedOutput;
        $output->write('one');
        $output->write('two');

        static::assertEquals('onetwo', $output->fetch());
    }

    public function testFetchCleansBufferByDefault() : void
    {
        $output = new BufferedOutput;
        $output->write('one');

        static::assertEquals('one', $output->fetch());
        static::assertEquals('', $output->fetch());
    }

    public function testFetchWithoutCleaning() : void
    {
        $output = new BufferedOutput;
        $output->write('one');

        static::assertEquals('one', $output->fetch(false));

        $output->write('two');

        static::assertEquals('onetwo', $output->fetch(false));
    }
}
