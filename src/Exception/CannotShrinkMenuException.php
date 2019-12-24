<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Exception;

use InvalidArgumentException;

class CannotShrinkMenuException extends InvalidArgumentException
{
    public static function fromMarginAndTerminalWidth(int $margin, int $terminalWidth) : self
    {
        return new self(
            sprintf(
                'Cannot shrink menu. Margin: %s * 2 with terminal width: %s leaves no space for menu',
                $margin,
                $terminalWidth
            )
        );
    }
}
