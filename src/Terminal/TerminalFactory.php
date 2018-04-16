<?php

namespace PhpSchool\CliMenu\Terminal;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class TerminalFactory
{
    public static function fromSystem() : TerminalInterface
    {
        return new UnixTerminal();
    }
}
