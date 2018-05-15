<?php

namespace PhpSchool\CliMenu\Builder;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
interface Builder
{
    public function getTerminal() : Terminal;
    
    public function getMenuStyle() : MenuStyle;
}
