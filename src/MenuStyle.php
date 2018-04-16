<?php

namespace PhpSchool\CliMenu;

use PhpSchool\CliMenu\Exception\InvalidInstantiationException;
use PhpSchool\CliMenu\Terminal\TerminalFactory;
use PhpSchool\CliMenu\Terminal\TerminalInterface;

//TODO: B/W fallback

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
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
     * @var string
     */
    private $selectedMarker;

    /**
     * @var string
     */
    private $unselectedMarker;

    /**
     * @var string
     */
    private $itemExtra;

    /**
     * @var bool
     */
    private $displaysExtra;

    /**
     * @var string
     */
    private $titleSeparator;

    /**
     * Default Values
     *
     * @var array
     */
    private static $defaultStyleValues = [
        'fg' => 'white',
        'bg' => 'blue',
        'width' => 100,
        'padding' => 2,
        'margin' => 2,
        'selectedMarker' => '●',
        'unselectedMarker' => '○',
        'itemExtra' => '✔',
        'displaysExtra' => false,
        'titleSeparator' => '=',
    ];

    public static function getDefaultStyleValues() : array
    {
        return static::$defaultStyleValues;
    }

    /**
     * @var array
     */
    private static $availableForegroundColors = array(
        'black'   => array('set' => 30, 'unset' => 39),
        'red'     => array('set' => 31, 'unset' => 39),
        'green'   => array('set' => 32, 'unset' => 39),
        'yellow'  => array('set' => 33, 'unset' => 39),
        'blue'    => array('set' => 34, 'unset' => 39),
        'magenta' => array('set' => 35, 'unset' => 39),
        'cyan'    => array('set' => 36, 'unset' => 39),
        'white'   => array('set' => 37, 'unset' => 39),
        'default' => array('set' => 39, 'unset' => 39),
    );

    /**
     * @var array
     */
    private static $availableBackgroundColors = array(
        'black'   => array('set' => 40, 'unset' => 49),
        'red'     => array('set' => 41, 'unset' => 49),
        'green'   => array('set' => 42, 'unset' => 49),
        'yellow'  => array('set' => 43, 'unset' => 49),
        'blue'    => array('set' => 44, 'unset' => 49),
        'magenta' => array('set' => 45, 'unset' => 49),
        'cyan'    => array('set' => 46, 'unset' => 49),
        'white'   => array('set' => 47, 'unset' => 49),
        'default' => array('set' => 49, 'unset' => 49),
    );

    /**
     * @var array
     */
    private static $availableOptions = array(
        'bold'       => array('set' => 1, 'unset' => 22),
        'dim'        => array('set' => 2, 'unset' => 22),
        'underscore' => array('set' => 4, 'unset' => 24),
        'blink'      => array('set' => 5, 'unset' => 25),
        'reverse'    => array('set' => 7, 'unset' => 27),
        'conceal'    => array('set' => 8, 'unset' => 28)
    );

    /**
     * Initialise style
     */
    public function __construct(TerminalInterface $terminal = null)
    {
        $this->terminal = $terminal ?: TerminalFactory::fromSystem();

        $this->setFg(static::$defaultStyleValues['fg']);
        $this->setBg(static::$defaultStyleValues['bg']);
        $this->setWidth(static::$defaultStyleValues['width']);
        $this->setPadding(static::$defaultStyleValues['padding']);
        $this->setMargin(static::$defaultStyleValues['margin']);
        $this->setSelectedMarker(static::$defaultStyleValues['selectedMarker']);
        $this->setUnselectedMarker(static::$defaultStyleValues['unselectedMarker']);
        $this->setItemExtra(static::$defaultStyleValues['itemExtra']);
        $this->setDisplaysExtra(static::$defaultStyleValues['displaysExtra']);
        $this->setTitleSeparator(static::$defaultStyleValues['titleSeparator']);
    }

    public static function getAvailableColours() : array
    {
        return array_keys(self::$availableBackgroundColors);
    }

    public function getDisabledItemText(string $text) : string
    {
        return sprintf(
            "\033[%sm%s\033[%sm",
            self::$availableOptions['dim']['set'],
            $text,
            self::$availableOptions['dim']['unset']
        );
    }
    
    /**
     * Get the colour code set for Bg and Fg
     */
    public function getSelectedSetCode() : string
    {
        return sprintf(
            "\033[%sm",
            implode(';', [
                self::$availableBackgroundColors[$this->getFg()]['set'],
                self::$availableForegroundColors[$this->getBg()]['set'],
            ])
        );
    }

    /**
     * Get the colour unset code for Bg and Fg
     */
    public function getSelectedUnsetCode() : string
    {
        return sprintf(
            "\033[%sm",
            implode(';', [
                self::$availableBackgroundColors[$this->getBg()]['unset'],
                self::$availableForegroundColors[$this->getFg()]['unset'],
            ])
        );
    }

    /**
     * Get the inverted colour code
     */
    public function getUnselectedSetCode() : string
    {
        return sprintf(
            "\033[%sm",
            implode(';', [
                self::$availableBackgroundColors[$this->getBg()]['set'],
                self::$availableForegroundColors[$this->getFg()]['set'],
            ])
        );
    }

    /**
     * Get the inverted colour unset code
     */
    public function getUnselectedUnsetCode() : string
    {
        return sprintf(
            "\033[%sm",
            implode(';', [
                self::$availableBackgroundColors[$this->getBg()]['unset'],
                self::$availableForegroundColors[$this->getFg()]['unset'],
            ])
        );
    }

    /**
     * Calculate the contents width
     */
    protected function calculateContentWidth() : void
    {
        $this->contentWidth = $this->width - ($this->padding*2) - ($this->margin*2);
    }

    public function getFg() : string
    {
        return $this->fg;
    }

    public function setFg(string $fg) : self
    {
        $this->fg = $fg;

        return $this;
    }

    public function getBg() : string
    {
        return $this->bg;
    }

    public function setBg(string $bg) : self
    {
        $this->bg = $bg;

        return $this;
    }

    public function getWidth() : int
    {
        return $this->width;
    }

    public function setWidth(int $width) : self
    {
        $availableWidth = $this->terminal->getWidth() - ($this->margin * 2) - ($this->padding * 2);

        if ($width >= $availableWidth) {
            $width = $availableWidth;
        }

        $this->width = $width;
        $this->calculateContentWidth();

        return $this;
    }

    public function getPadding() : int
    {
        return $this->padding;
    }

    public function setPadding(int $padding) : self
    {
        $this->padding = $padding;

        $this->calculateContentWidth();

        return $this;
    }

    public function getMargin() : int
    {
        return $this->margin;
    }

    public function setMargin(int $margin) : self
    {
        $this->margin = $margin;

        $this->calculateContentWidth();

        return $this;
    }

    public function getContentWidth() : int
    {
        return $this->contentWidth;
    }

    /**
     * Get padding for right had side of content
     */
    public function getRightHandPadding(int $contentLength) : int
    {
        return $this->getContentWidth() - $contentLength + $this->getPadding();
    }

    public function getSelectedMarker() : string
    {
        return $this->selectedMarker;
    }

    public function setSelectedMarker(string $marker) : self
    {
        $this->selectedMarker = mb_substr($marker, 0, 1);

        return $this;
    }

    public function getUnselectedMarker() : string
    {
        return $this->unselectedMarker;
    }

    public function setUnselectedMarker(string $marker) : self
    {
        $this->unselectedMarker = mb_substr($marker, 0, 1);

        return $this;
    }

    /**
     * Get the correct marker for the item
     */
    public function getMarker(bool $selected) : string
    {
        return $selected ? $this->selectedMarker : $this->unselectedMarker;
    }

    public function setItemExtra(string $itemExtra) : self
    {
        $this->itemExtra = $itemExtra;

        return $this;
    }

    public function getItemExtra() : string
    {
        return $this->itemExtra;
    }

    public function getDisplaysExtra() : bool
    {
        return $this->displaysExtra;
    }

    public function setDisplaysExtra(bool $displaysExtra) : self
    {
        $this->displaysExtra = $displaysExtra;

        return $this;
    }

    public function getTitleSeparator() : string
    {
        return $this->titleSeparator;
    }

    public function setTitleSeparator(string $actionSeparator) : self
    {
        $this->titleSeparator = $actionSeparator;

        return $this;
    }
}
