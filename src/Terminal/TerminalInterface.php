<?php

namespace MikeyMike\CliMenu\Terminal;

/**
 * Class TerminalInterface
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
interface TerminalInterface
{
    /**
     * Initialise the terminal from resource
     *
     * @param string $resource
     */
    public function __construct($resource = STDOUT);

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
     * Get the available width of the width
     *
     * @return int
     */
    public function getWidth();

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
     */
    public function clear();

    /**
     * Toggle cursor display
     *
     * @param bool $enableCursor
     */
    public function enableCursor($enableCursor = true);
}