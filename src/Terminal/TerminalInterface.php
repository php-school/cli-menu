<?php

namespace PhpSchool\CliMenu\Terminal;

/**
 * Interface TerminalInterface
 *
 * @package PhpSchool\CliMenu\Terminal
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
     * Test whether terminal supports colour output
     *
     * @return bool
     */
    public function supportsColour();

    /**
     * Clear the terminal window
     *
     * @return void
     */
    public function clear();

    /**
     * Clear the current cursors line
     *
     * @return void
     */
    public function clearLine();

    /**
     * Move the cursor to the top left of the window
     *
     * @return void
     */
    public function moveCursorToTop();

    /**
     * Move the cursor to the start of a specific row
     *
     * @param int $rowNumber
     */
    public function moveCursorToRow($rowNumber);

    /**
     * Clean the whole console without jumping the window
     *
     * @return void
     */
    public function clean();

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
}
