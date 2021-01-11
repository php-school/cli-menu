<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Exception;

use PhpSchool\CliMenu\Exception\CannotShrinkMenuException;
use PHPUnit\Framework\TestCase;

class CannotShrinkMenuExceptionTest extends TestCase
{
    public function testException() : void
    {
        $e = CannotShrinkMenuException::fromMarginAndTerminalWidth(10, 15);
        $this->assertEquals(
            'Cannot shrink menu. Margin: 10 * 2 with terminal width: 15 leaves no space for menu',
            $e->getMessage()
        );
    }
}
