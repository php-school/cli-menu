<?php

namespace PhpSchool\CliMenu\Terminal;

/**
 * Class WindowsTerminal
 *
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class WindowsTerminal implements TerminalInterface
{

    /**
     * Get terminal details
     *
     * @return string
     */
    public function getDetails()
    {
        // TODO: Implement getDetails() method.
    }

    /**
     * Kill the application
     *
     * @return void
     */
    public function killProcess()
    {
        // TODO: Implement killProcess() method.
    }

    /**
     * Get the available width of the width
     *
     * @return int
     */
    public function getWidth()
    {
        // TODO: Implement getWidth() method.
    }

    /**
     * Toggle raw mode on TTY
     *
     * @param bool $useRaw
     */
    public function setRawMode($useRaw = true)
    {
        // TODO: Implement setRawMode() method.
    }

    /**
     * Check if TTY is in raw mode
     *
     * @return bool
     */
    public function isRaw()
    {
        // TODO: Implement isRaw() method.
    }

    /**
     * Test whether terminal is valid TTY
     *
     * @return bool
     */
    public function isTTY()
    {
        // TODO: Implement isTTY() method.
    }

    /**
     * Clear the terminal window
     */
    public function clear()
    {
        // TODO: Implement clear() method.
    }

    /**
     * Toggle cursor display
     *
     * @param bool $enableCursor
     */
    public function enableCursor($enableCursor = true)
    {
        // TODO: Implement enableCursor() method.
    }

    /**
     * @return string
     */
    public function getKeyedInput()
    {
        // TODO: Implement getKeyedInput() method.
    }
}
