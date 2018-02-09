<?php

namespace PhpSchool\CliMenuTest\Exception;

use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InvalidTerminalExceptionTest extends TestCase
{
    public function testException()
    {
        $e = new InvalidTerminalException('error');
        $this->assertEquals('error', $e->getMessage());
    }
}
