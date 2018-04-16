<?php

namespace PhpSchool\CliMenu\Terminal;

use PhpSchool\CliMenu\IO\ResourceInputStream;
use PhpSchool\CliMenu\IO\ResourceOutputStream;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class TerminalFactory
{
    public static function fromSystem() : TerminalInterface
    {
        return new UnixTerminal(new ResourceInputStream, new ResourceOutputStream);
    }
}
