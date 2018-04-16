<?php

namespace PhpSchool\CliMenu\Terminal;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class UnixTerminal implements TerminalInterface
{
    /**
     * @var bool
     */
    private $isTTY;

    /**
     * @var bool
     */
    private $isCanonical = false;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var string
     */
    private $details;

    /**
     * @var string
     */
    private $originalConfiguration;

    /**
     * Initialise the terminal from resource
     *
     */
    public function __construct()
    {
        $this->getOriginalConfiguration();
    }

    /**
     * Get the available width of the terminal
     */
    public function getWidth() : int
    {
        return $this->width ?: $this->width = (int) exec('tput cols');
    }

    /**
     * Get the available height of the terminal
     */
    public function getHeight() : int
    {
        return $this->height ?: $this->height = (int) exec('tput lines');
    }

    /**
     * Get terminal details
     */
    public function getDetails() : string
    {
        if (!$this->details) {
            $this->details = function_exists('posix_ttyname')
                ? @posix_ttyname(STDOUT)
                : "Can't retrieve terminal details";
        }

        return $this->details;
    }

    /**
     * Get the original terminal configuration / mode
     */
    private function getOriginalConfiguration() : string
    {
        return $this->originalConfiguration ?: $this->originalConfiguration = exec('stty -g');
    }

    /**
     * Toggle canonical mode on TTY
     */
    public function setCanonicalMode(bool $useCanonicalMode = true) : void
    {
        if ($useCanonicalMode) {
            exec('stty -icanon');
            $this->isCanonical = true;
        } else {
            exec('stty ' . $this->getOriginalConfiguration());
            $this->isCanonical = false;
        }
    }

    /**
     * Check if TTY is in canonical mode
     * Assumes the terminal was never in canonical mode
     */
    public function isCanonical() : bool
    {
        return $this->isCanonical;
    }

    /**
     * Test whether terminal is valid TTY
     */
    public function isTTY() : bool
    {
        return $this->isTTY ?: $this->isTTY = function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }

    /**
     * Test whether terminal supports colour output
     *
     * @link https://github.com/symfony/Console/blob/master/Output/StreamOutput.php#L95-L102
     */
    public function supportsColour() : bool
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI') || 'xterm' === getenv('TERM');
        }

        return $this->isTTY();
    }

    public function getKeyedInput() : string
    {
        // TODO: Move to class var?
        // TODO: up, down, enter etc in Abstract CONSTs
        $map = [
            "\033[A" => 'up',
            "k"      => 'up',
            ""      => 'up', // emacs ^P
            "\033[B" => 'down',
            "j"      => 'down',
            ""      => 'down', //emacs ^N
            "\n"     => 'enter',
            "\r"     => 'enter',
            " "      => 'enter',
        ];

        $input = fread(STDIN, 4);
        $this->clearLine();

        return array_key_exists($input, $map)
            ? $map[$input]
            : $input;
    }

    /**
     * Clear the terminal window
     */
    public function clear() : void
    {
        echo "\033[2J";
    }

    /**
     * Enable cursor
     */
    public function enableCursor() : void
    {
        echo "\033[?25h";
    }

    /**
     * Disable cursor
     */
    public function disableCursor() : void
    {
        echo "\033[?25l";
    }

    /**
     * Move the cursor to the top left of the window
     *
     * @return void
     */
    public function moveCursorToTop() : void
    {
        echo "\033[H";
    }

    /**
     * Move the cursor to the start of a specific row
     */
    public function moveCursorToRow(int $rowNumber) : void
    {
        echo sprintf("\033[%d;0H", $rowNumber);
    }

    /**
     * Move the cursor to the start of a specific column
     */
    public function moveCursorToColumn(int $column) : void
    {
        echo sprintf("\033[%dC", $column);
    }

    /**
     * Clear the current cursors line
     */
    public function clearLine() : void
    {
        echo sprintf("\033[%dD\033[K", $this->getWidth());
    }

    /**
     * Clean the whole console without jumping the window
     */
    public function clean() : void
    {
        foreach (range(0, $this->getHeight()) as $rowNum) {
            $this->moveCursorToRow($rowNum);
            $this->clearLine();
        }
    }
}
