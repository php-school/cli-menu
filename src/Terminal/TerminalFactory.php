<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Terminal;

use PhpSchool\Terminal\IO\ResourceInputStream;
use PhpSchool\Terminal\IO\ResourceOutputStream;
use PhpSchool\Terminal\Terminal;
use PhpSchool\Terminal\UnixTerminal;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class TerminalFactory
{
    public static function fromSystem() : Terminal
    {
        return new UnixTerminal(new ResourceInputStream, new ResourceOutputStream);
    }
}
