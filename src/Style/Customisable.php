<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Style;

interface Customisable
{
    public function hasChangedFromDefaults() : bool;
}
