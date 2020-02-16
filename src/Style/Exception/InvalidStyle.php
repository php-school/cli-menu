<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Style\Exception;

use PhpSchool\CliMenu\MenuItem\MenuItemInterface;

class InvalidStyle extends \RuntimeException
{
    public static function unregisteredStyle(string $styleClass) : self
    {
        return new self("Style class: '$styleClass' is not registered");
    }

    public static function notSubClassOf(string $styleClass) : self
    {
        return new self("Style instance must be a subclass of: '$styleClass'");
    }

    public static function unregisteredItem(string $itemClass) : self
    {
        return new self("Menu item: '$itemClass' does not have a registered style class");
    }

    public static function itemAlreadyRegistered(string $itemClass) : self
    {
        return new self("Menu item: '$itemClass' already has a registered style class");
    }
}
