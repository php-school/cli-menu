<?php

namespace PhpSchool\CliMenuTest\Exception;

use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use PHPUnit_Framework_TestCase;

/**
 * Class InvalidTerminalExceptionTest
 * @package PhpSchool\CliMenuTest\Exception
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InvalidTerminalExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $e = new InvalidTerminalException('error');
        $this->assertEquals('error', $e->getMessage());
    }
}
