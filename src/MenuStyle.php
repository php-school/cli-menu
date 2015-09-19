<?php

namespace MikeyMike\CliMenu;

use MikeyMike\CliMenu\Terminal\TerminalInterface;
use MikeyMike\CliMenu\Terminal\UnixTerminal;

/**
 * Class MenuStyle
 * @author Michael Woodward <michael@wearejh.com>
 */
class MenuStyle
{
    /**
     * @var TerminalInterface
     */
    protected $terminal;

    /**
     * @var string
     */
    protected $fg;

    /**
     * @var string
     */
    protected $bg;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $padding;

    /**
     * @var int
     */
    protected $margin;

    /**
     * @var int
     */
    protected $contentWidth;

    /**
     * @var array
     */
    private $availableForegroundColors = array(
        'black' => array('set' => 30, 'unset' => 39),
        'red' => array('set' => 31, 'unset' => 39),
        'green' => array('set' => 32, 'unset' => 39),
        'yellow' => array('set' => 33, 'unset' => 39),
        'blue' => array('set' => 34, 'unset' => 39),
        'magenta' => array('set' => 35, 'unset' => 39),
        'cyan' => array('set' => 36, 'unset' => 39),
        'white' => array('set' => 37, 'unset' => 39),
        'default' => array('set' => 39, 'unset' => 39),
    );

    /**
     * @var array
     */
    private $availableBackgroundColors = array(
        'black' => array('set' => 40, 'unset' => 49),
        'red' => array('set' => 41, 'unset' => 49),
        'green' => array('set' => 42, 'unset' => 49),
        'yellow' => array('set' => 43, 'unset' => 49),
        'blue' => array('set' => 44, 'unset' => 49),
        'magenta' => array('set' => 45, 'unset' => 49),
        'cyan' => array('set' => 46, 'unset' => 49),
        'white' => array('set' => 47, 'unset' => 49),
        'default' => array('set' => 49, 'unset' => 49),
    );

    /**
     * @var array
     */
    private $availableOptions = array(
        'bold' => array('set' => 1, 'unset' => 22),
        'underscore' => array('set' => 4, 'unset' => 24),
        'blink' => array('set' => 5, 'unset' => 25),
        'reverse' => array('set' => 7, 'unset' => 27),
        'conceal' => array('set' => 8, 'unset' => 28),
    );

    /**
     * Initialise style
     *
     * @param TerminalInterface $terminal
     * @param string $bg
     * @param string $fg
     * @param int $width
     * @param int $padding
     * @param int $margin
     */
    public function __construct(
        TerminalInterface $terminal = null,
        $bg = 'blue',
        $fg = 'white',
        $width = 100,
        $padding = 2,
        $margin = 2
    ) {
        if (!array_key_exists($bg, $this->availableBackgroundColors)) {
            throw new \InvalidArgumentException(sprintf('Invalid foreground colour "%s"', $fg));
        }

        if (!array_key_exists($fg, $this->availableForegroundColors)) {
            throw new \InvalidArgumentException(sprintf('Invalid foreground colour "%s"', $fg));
        }

        $this->terminal     = $terminal ?: new UnixTerminal();
        $this->bg           = $bg;
        $this->fg           = $fg;
        $this->padding      = $padding;
        $this->margin       = $margin;

        $this->setWidth($width);
        $this->calculateContentWidth();
    }

    /**
     * Get the colour code set for Bg and Fg
     *
     * @return string
     */
    public function getSelectedSetCode()
    {
        return sprintf(
            "\033[%sm",
            implode(';', [
                $this->availableBackgroundColors[$this->getFg()]['set'],
                $this->availableForegroundColors[$this->getBg()]['set'],
            ])
        );
    }

    /**
     * Get the colour unset code for Bg and Fg
     *
     * @return string
     */
    public function getSelectedUnsetCode()
    {
        return sprintf(
            "\033[%sm",
            implode(';', [
                $this->availableBackgroundColors[$this->getFg()]['unset'],
                $this->availableForegroundColors[$this->getBg()]['unset'],
            ])
        );
    }

    /**
     * Get the inverted colour code
     *
     * @return string
     */
    public function getUnselectedSetCode()
    {
        return sprintf(
            "\033[%sm",
            implode(';', [
                $this->availableBackgroundColors[$this->getBg()]['set'],
                $this->availableForegroundColors[$this->getFg()]['set'],
            ])
        );
    }

    /**
     * Get the inverted colour unset code
     *
     * @return string
     */
    public function getUnselectedUnsetCode()
    {
        return sprintf(
            "\033[%sm",
            implode(';', [
                $this->availableBackgroundColors[$this->getBg()]['unset'],
                $this->availableForegroundColors[$this->getFg()]['unset'],
            ])
        );
    }

    /**
     * Calculate the contents width
     */
    protected function calculateContentWidth()
    {
        $this->contentWidth = $this->width - ($this->padding*2) - ($this->margin*2);
    }

    /**
     * @return string
     */
    public function getFg()
    {
        return $this->fg;
    }

    /**
     * @param string $fg
     * @return MenuStyle
     */
    public function setFg($fg)
    {
        $this->fg = $fg;

        return $this;
    }

    /**
     * @return string
     */
    public function getBg()
    {
        return $this->bg;
    }

    /**
     * @param string $bg
     * @return MenuStyle
     */
    public function setBg($bg)
    {
        $this->bg = $bg;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return MenuStyle
     */
    public function setWidth($width)
    {
        $availableWidth = $this->terminal->getWidth() - ($this->margin * 2) - ($this->padding * 2);

        if ($width >= $availableWidth) {
            $width = $availableWidth-1;
        }

        $this->width = $width;
        $this->calculateContentWidth();

        return $this;
    }

    /**
     * @return int
     */
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * @param int $padding
     * @return MenuStyle
     */
    public function setPadding($padding)
    {
        $this->padding = $padding;

        $this->calculateContentWidth();

        return $this;
    }

    /**
     * @return int
     */
    public function getMargin()
    {
        return $this->margin;
    }

    /**
     * @param int $margin
     * @return MenuStyle
     */
    public function setMargin($margin)
    {
        $this->margin = $margin;

        $this->calculateContentWidth();

        return $this;
    }

    /**
     * @return int
     */
    public function getContentWidth()
    {
        return $this->contentWidth;
    }

    /**
     * Get padding for right had side of content
     *
     * @param $contentLength
     * @return int
     */
    public function getRightHandPadding($contentLength)
    {
        return $this->getContentWidth() - $contentLength + $this->getPadding();
    }
}
