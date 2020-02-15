<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;

interface PropagatesStyles
{
    /**
     * Push the parents styles to any
     * child items or menus.
     */
    public function propagateStyles(CliMenu $parent) : void;
}
