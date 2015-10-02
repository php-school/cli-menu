<?php

namespace MikeyMike\CliMenu\Terminal;

/**
 * Class TerminalFactory
 *
 * @author Michael Woodward <michael@wearejh.com>
 */
class TerminalFactory
{
    /**
     * @return TerminalInterface
     */
    public static function fromSystem()
    {
        if (DIRECTORY_SEPARATOR == '\\') {
            return new WindowsTerminal();
        }

        return new UnixTerminal();
    }
}

