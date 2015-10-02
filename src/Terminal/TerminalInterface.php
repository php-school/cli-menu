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
     * Toggle raw mode on TTY
     *
     * @param bool $useRaw
     */
    public function setRawMode($useRaw = true);

    /**
     * Check if TTY is in raw mode
     *
     * @return bool
     */
    public function isRaw();

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
     * Toggle cursor display
     *
     * @param bool $enableCursor
     */
    public function enableCursor($enableCursor = true);

    /**
     * @return string
     */
    public function getKeyedInput();
}