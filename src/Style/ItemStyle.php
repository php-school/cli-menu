<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Style;

use PhpSchool\CliMenu\MenuItem\MenuItemInterface;

interface ItemStyle
{
    public function hasChangedFromDefaults() : bool;

    public function getDisplaysExtra() : bool;

    public function getItemExtra() : string;

    public function getMarker(MenuItemInterface $menuItem, bool $isSelected) : string;
}
