<?php

namespace PhpSchool\CliMenuTest\IO;

use PhpSchool\CliMenu\IO\ResourceInputStream;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ResourceInputStreamTest extends TestCase
{
    public function testNonStream() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a valid stream');
        new ResourceInputStream(42);
    }

    public function testNotReadable() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a readable stream');
        new ResourceInputStream(\STDOUT);
    }

    public function testRead() : void
    {
        $stream = fopen('php://memory','r+');
        fwrite($stream, '1234');
        rewind($stream);

        $inputStream = new ResourceInputStream($stream);

        $input = '';
        $inputStream->read(4, function ($buffer) use (&$input) {
            $input .= $buffer;
        });

        static::assertSame('1234', $input);

        fclose($stream);
    }
}
