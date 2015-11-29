<?php

namespace PhpSchool\CliMenu\Action;

use PhpSchool\CliMenu\CliMenu;

/**
 * Class ExitAction
 * @package PhpSchool\CliMenu\Action
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ExitAction
{
    /**
     * @param CliMenu $menu
     */
    public function __invoke(CliMenu $menu)
    {
        $menu->close();
    }
}
