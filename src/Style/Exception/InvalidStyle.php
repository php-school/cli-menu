<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Style\Exception;

class InvalidStyle extends \RuntimeException
{
    public static function unregisteredStyle(string $styleClass) : self
    {
        return new self("Style class: '$styleClass' is not registered");
    }
}
