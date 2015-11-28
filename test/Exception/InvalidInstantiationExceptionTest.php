<?php

namespace PhpSchool\CliMenuTest\Exception;

use PhpSchool\CliMenu\Exception\InvalidInstantiationException;
use PHPUnit_Framework_TestCase;

/**
 * Class InvalidInstantiationExceptionTest
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InvalidInstantiationExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $e = new InvalidInstantiationException('error');
        $this->assertEquals('error', $e->getMessage());
    }
}
