<?php

namespace PhpSchool\CliMenu\Terminal;

use PhpSchool\CliMenu\IO\OutputStream;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
interface TerminalInterface
{
    /**
     * Get terminal details
     */
    public function getDetails() : string ;

    /**
     * Get the available width of the terminal
     */
    public function getWidth() : int;

    /**
     * Get the available height of the terminal
     */
    public function getHeight() : int;

    /**
     * Toggle canonical mode on TTY
     */
    public function setCanonicalMode(bool $useCanonicalMode = true) : void;

    /**
     * Check if TTY is in canonical mode
     */
    public function isCanonical() : bool;

    /**
     * Test whether terminal is valid TTY
     */
    public function isTTY() : bool;

    /**
     * Test whether terminal supports colour output
     */
    public function supportsColour() : bool;

    /**
     * Clear the terminal window
     */
    public function clear() : void;

    /**
     * Clear the current cursors line
     */
    public function clearLine() : void;

    /**
     * Move the cursor to the top left of the window
     */
    public function moveCursorToTop() : void;

    /**
     * Move the cursor to the start of a specific row
     */
    public function moveCursorToRow(int $rowNumber) : void;

    /**
     * Move the cursor to a specific column
     */
    public function moveCursorToColumn(int $columnNumber) : void;

    /**
     * Clean the whole console without jumping the window
     */
    public function clean() : void;

    /**
     * Enable cursor display
     */
    public function enableCursor() : void;

    /**
     * Disable cursor display
     */
    public function disableCursor() : void;

    /**
     * @return string
     */
    public function getKeyedInput(array $map = []) : ?string;

    /**
     * Get the output stream
     */
    public function getOutput() : OutputStream;
}
