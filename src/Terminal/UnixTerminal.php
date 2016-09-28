<?php

namespace PhpSchool\CliMenu\Terminal;

/**
 * Class UnixTerminal
 *
 * @package PhpSchool\CliMenu\Terminal
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
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width ?: $this->width = (int) exec('tput cols');
    }

    /**
     * Get the available height of the terminal
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height ?: $this->height = (int) exec('tput lines');
    }

    /**
     * Get terminal details
     *
     * @return string
     */
    public function getDetails()
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
     *
     * @return string
     */
    private function getOriginalConfiguration()
    {
        return $this->originalConfiguration ?: $this->originalConfiguration = exec('stty -g');
    }

    /**
     * Toggle canonical mode on TTY
     *
     * @param bool $useCanonicalMode
     */
    public function setCanonicalMode($useCanonicalMode = true)
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
     *
     * @return bool
     */
    public function isCanonical()
    {
        return $this->isCanonical;
    }

    /**
     * Test whether terminal is valid TTY
     *
     * @return bool
     */
    public function isTTY()
    {
        return $this->isTTY ?: $this->isTTY = function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }

    /**
     * Test whether terminal supports colour output
     *
     * @return bool
     *
     * @link https://github.com/symfony/Console/blob/master/Output/StreamOutput.php#L95-L102
     */
    public function supportsColour()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI') || 'xterm' === getenv('TERM');
        }

        return $this->isTTY();
    }

    /**
     * @return string
     */
    public function getKeyedInput()
    {
        // TODO: Move to class var?
        // TODO: up, down, enter etc in Abstract CONSTs
        $map = [
            "\033[A" => 'up',
            "k"      => 'up',
            "\033[B" => 'down',
            "j"      => 'down',
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
    public function clear()
    {
        echo "\033[2J";
    }

    /**
     * Enable cursor
     */
    public function enableCursor()
    {
        echo "\033[?25h";
    }

    /**
     * Disable cursor
     */
    public function disableCursor()
    {
        echo "\033[?25l";
    }

    /**
     * Move the cursor to the top left of the window
     *
     * @return void
     */
    public function moveCursorToTop()
    {
        echo "\033[H";
    }

    /**
     * Move the cursor to the start of a specific row
     *
     * @param int $rowNumber
     */
    public function moveCursorToRow($rowNumber)
    {
        echo sprintf("\033[%d;0H", $rowNumber);
    }

    /**
     * Clear the current cursors line
     *
     * @return void
     */
    public function clearLine()
    {
        echo sprintf("\033[%dD\033[K", $this->getWidth());
    }

    /**
     * Clean the whole console without jumping the window
     */
    public function clean()
    {
        foreach (range(0, $this->getHeight()) as $rowNum) {
            $this->moveCursorToRow($rowNum);
            $this->clearLine();
        }
    }
}
