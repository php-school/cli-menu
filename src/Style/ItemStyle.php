<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Style;

interface ItemStyle
{
    public function hasChangedFromDefaults() : bool;

    public function getDisplaysExtra() : bool;

    public function getItemExtra() : string;
}
