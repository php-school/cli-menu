<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\Style\SelectableStyle;

interface SelectableStyleInterface
{
    public function getStyle() : SelectableStyle;

    public function setStyle(SelectableStyle $style);
}
