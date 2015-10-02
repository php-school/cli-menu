<?php
declare(ticks=1);
namespace MikeyMike\CliMenu\Terminal;

/**
 * Class UnixTerminal
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class UnixTerminal implements TerminalInterface
{
    /**
     * @var bool
     */
    private $isTTY;

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
     * Kill the application
     *
     * @return void
     */
    public function killProcess()
    {
        $this->setRawMode(false);

//        posix_kill(posix_getpid(), SIGKILL);
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
        return 'some fucking shell init bro';
//        return $this->details ?: $this->details = posix_ttyname(STDOUT);
    }

    /**
     * Get the original terminal configuration / mode
     *
     * @return string
     */
    private function getOriginalConfiguration()
    {
        return $this->originalConfiguration ?: $this->originalConfiguration = system('stty -g');
    }

    /**
     * Toggle raw mode on TTY
     *
     * @param bool $useCanonicalMode
     */
    public function setCanonicalMode($useCanonicalMode = true)
    {
        $useCanonicalMode ? system('stty -icanon') : system('stty ' . $this->getOriginalConfiguration());
    }

    /**
     * Check if TTY is in canonical mode
     *
     * @return bool
     */
    public function isCanonical()
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
        return true;
//        return $this->isTTY ?: $this->isTTY = posix_isatty(STDOUT);
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
     * @return string
     */
    public function getKeyedInput()
    {
        // TODO: Move to class var?
        // TODO: up, down, enter etc in Abstract CONSTs
        $map = [
            "\033[A" => 'up',
            "\033[B" => 'down',
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
     * Move the cursor to the top left of the window
     *
     * @return void
     */
    public function moveCursorToTop()
    {
        echo "\033[H";
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
}
