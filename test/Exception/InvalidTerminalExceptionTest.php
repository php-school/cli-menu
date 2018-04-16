<?php

namespace PhpSchool\CliMenuTest\Exception;

use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidTerminalExceptionTest
 * @package PhpSchool\CliMenuTest\Exception
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
