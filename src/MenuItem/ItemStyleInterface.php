<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\Style;

interface ItemStyleInterface
{
    public function getStyle() : Style\ItemStyleInterface;

    public function setStyle(Style\ItemStyleInterface $style);
}
