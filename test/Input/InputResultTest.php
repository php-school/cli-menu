<?php
declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Input;

use PhpSchool\CliMenu\Input\InputResult;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InputResultTest extends TestCase
{
    public function testFetch() : void
    {
        static::assertEquals('my-password', (new InputResult('my-password'))->fetch());
    }
}
