<?php

namespace PhpSchool\CliMenu;

use PhpSchool\Terminal\Terminal;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
interface Builder
{
    public function getTerminal() : Terminal;
    
    public function end() : ?Builder;

    public function getMenuStyle() : MenuStyle;
}
