<?php

namespace PhpSchool\CliMenuTest\Exception;

use PhpSchool\CliMenu\Exception\MenuNotOpenException;
use PHPUnit_Framework_TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MenuNotOpenExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $e = new MenuNotOpenException('error');
        $this->assertEquals('error', $e->getMessage());
    }
}
