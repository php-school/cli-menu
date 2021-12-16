<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Exception;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InvalidShortcutException extends \RuntimeException
{
    public static function fromShortcut(string $shortcut) : self
    {
        return new self(sprintf('Shortcut key must be only one character. Got: "%s"', $shortcut));
    }
}
