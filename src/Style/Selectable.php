<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Style;

interface Selectable extends ItemStyle
{
    public function getMarker(bool $selected) : string;
}
