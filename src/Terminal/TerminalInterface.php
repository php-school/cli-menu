<?php

namespace MikeyMike\CliMenu\Terminal;

/**
 * Class TerminalInterface
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
interface TerminalInterface
{
    /**
     * Get terminal details
     *
     * @return string
     */
    public function getDetails();

    /**
     * Kill the application
     *
     * @return void
     */
    public function killProcess();

    /**
     * Get the available width of the terminal
     *
     * @return int
     */
    public function getWidth();

    /**
     * Get the available height of the terminal
     *
     * @return int
     */
    public function getHeight();

    /**
     * Toggle canonical mode on TTY
     *
     * @param bool $useCanonicalMode
     */
    public function setCanonicalMode($useCanonicalMode = true);

    /**
     * Check if TTY is in canonical mode
     *
     * @return bool
     */
    public function isCanonical();

    /**
     * Test whether terminal is valid TTY
     *
     * @return bool
     */
    public function isTTY();

    /**
     * Clear the terminal window
     *
     * @return void
     */
    public function clear();

    /**
     * Move the cursor to the top left of the window
     *
     * @return void
     */
    public function moveCursorToTop();

    /**
     * Enable cursor display
     */
    public function enableCursor();

    /**
     * Disable cursor display
     */
    public function disableCursor();

    /**
     * @return string
     */
    public function getKeyedInput();

    /**
     * Clear the current cursors line
     *
     * @return void
     */
    public function clearLine();
}