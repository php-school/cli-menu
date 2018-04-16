<?php

namespace PhpSchool\CliMenu\Action;

use PhpSchool\CliMenu\CliMenu;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class GoBackAction
{
    /**
     * @param CliMenu $menu
     */
    public function __invoke(CliMenu $menu)
    {
        if ($parent = $menu->getParent()) {
            $menu->close();
            $parent->open();
        }
    }
}
