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

    /**
     * Get the available height of the terminal
     *
     * @return int
     */
    public function getHeight()
    {
        // TODO: Implement getHeight() method.
    }

    /**
     * Toggle canonical mode on TTY
     *
     * @param bool $useCanonicalMode
     */
    public function setCanonicalMode($useCanonicalMode = true)
    {
        // TODO: Implement setCanonicalMode() method.
    }

    /**
     * Check if TTY is in canonical mode
     *
     * @return bool
     */
    public function isCanonical()
    {
        // TODO: Implement isCanonical() method.
    }

    /**
     * Test whether terminal supports colour output
     *
     * @return bool
     */
    public function supportsColour()
    {
        // TODO: Implement supportsColour() method.
    }

    /**
     * Clear the current cursors line
     *
     * @return void
     */
    public function clearLine()
    {
        // TODO: Implement clearLine() method.
    }

    /**
     * Move the cursor to the top left of the window
     *
     * @return void
     */
    public function moveCursorToTop()
    {
        // TODO: Implement moveCursorToTop() method.
    }

    /**
     * Move the cursor to the start of a specific row
     *
     * @param int $rowNumber
     */
    public function moveCursorToRow($rowNumber)
    {
        // TODO: Implement moveCursorToRow() method.
    }

    /**
     * Clean the whole console without jumping the window
     *
     * @return void
     */
    public function clean()
    {
        // TODO: Implement clean() method.
    }

    /**
     * Disable cursor display
     */
    public function disableCursor()
    {
        // TODO: Implement disableCursor() method.
    }
}
