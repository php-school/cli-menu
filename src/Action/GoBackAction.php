<?php

namespace PhpSchool\CliMenu\Action;

use PhpSchool\CliMenu\CliMenu;

/**
 * Class GoBackAction
 * @package PhpSchool\CliMenu\Action
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
