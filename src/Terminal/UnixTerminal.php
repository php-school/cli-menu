<?php

namespace MikeyMike\CliMenu\Terminal;

/**
 * Class UnixTerminal
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class UnixTerminal implements TerminalInterface
{
    /**
     * @var string TODO: Check is this really a string ??
     */
    protected $resource;

    /**
     * @var bool
     */
    protected $isTTY;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var string
     */
    protected $details;

    /**
     * Initialise the terminal from resource
     *
     * @param string $resource
     */
    public function __construct($resource = STDOUT)
    {
        $this->resource = $resource;
    }

    /**
     * Kill the application
     *
     * @return void
     */
    public function killProcess()
    {
        $this->setRawMode(false);

        posix_kill(posix_getpid(), SIGKILL);
    }

    /**
     * Get the available width of the width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width ?: $this->width = exec('tput cols');
    }

    /**
     * Get terminal details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details ?: $this->details = posix_ttyname($this->resource);
    }

    /**
     * Toggle raw mode on TTY
     *
     * @param bool $useRaw
     */
    public function setRawMode($useRaw = true)
    {
        $useRaw ? system('stty raw') : system('stty sane');
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
        return $this->isTTY ?: $this->isTTY = posix_isatty($this->resource);
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
        echo $enableCursor
            ? exec('tput cnorm')
            : exec('tput civis');
    }
}