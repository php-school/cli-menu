<?php

namespace PhpSchool\CliMenu;

use PhpSchool\CliMenu\Exception\InvalidInstantiationException;
use PhpSchool\CliMenu\Terminal\TerminalFactory;
use PhpSchool\Terminal\Terminal;

//TODO: B/W fallback

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class MenuStyle
{
    /**
     * @var Terminal
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
     * @var string
     */
    private $coloursSetCode;

    /**
     * @var string
     */
    private $invertedColoursSetCode = "\033[7m";

    /**
     * @var string
     */
    private $coloursResetCode = "\033[0m";

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
        'black'   => 30,
        'red'     => 31,
        'green'   => 32,
        'yellow'  => 33,
        'blue'    => 34,
        'magenta' => 35,
        'cyan'    => 36,
        'white'   => 37,
        'default' => 39,
    );

    /**
     * @var array
     */
    private static $availableBackgroundColors = array(
        'black'   => 40,
        'red'     => 41,
        'green'   => 42,
        'yellow'  => 43,
        'blue'    => 44,
        'magenta' => 45,
        'cyan'    => 46,
        'white'   => 47,
        'default' => 49,
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
    public function __construct(Terminal $terminal = null)
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
     * Generates the ansi escape sequence to set the colours
     */
    private function generateColoursSetCode() : void
    {
        if (is_string($this->fg)) {
            $fgCode = self::$availableForegroundColors[$this->fg];
        } else {
            $fgCode = sprintf("38;5;%s", $this->fg);
        }

        if (is_string($this->bg)) {
            $bgCode = self::$availableBackgroundColors[$this->bg];
        } else {
            $bgCode = sprintf("48;5;%s", $this->bg);
        }

        $this->coloursSetCode = sprintf("\033[%s;%sm", $fgCode, $bgCode);
    }

    /**
     * Get the colour code for Bg and Fg
     */
    public function getColoursSetCode() : string
    {
        return $this->coloursSetCode;
    }

    /**
     * Get the inverted escape sequence (used for selected elements)
     */
    public function getInvertedColoursSetCode() : string
    {
        return $this->invertedColoursSetCode;
    }

    /**
     * Get the escape sequence used to reset colours to default
     */
    public function getColoursResetCode() : string
    {
        return $this->coloursResetCode;
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

    public function setFg($fg) : self
    {
        if (is_int($fg)) {
            if ($this->terminal->getColourSupport() < 256) {
                // Need to map to 8 colors
                return $this;
            } elseif ($fg < 0 || $fg > 255) {
                throw new Exception("Invalid colour code");
            }
        }

        $this->fg = $fg;
        $this->generateColoursSetCode();

        return $this;
    }

    public function getBg() : string
    {
        return $this->bg;
    }

    public function setBg($bg) : self
    {
        if (is_int($bg)) {
            if ($this->terminal->getColourSupport() < 256) {
                // Need to map to 8 colors
                return $this;
            }
            if ($bg < 0 || $bg > 255) {
                throw new Exception("Invalid colour code");
            }
        }
        $this->bg = $bg;
        $this->generateColoursSetCode();

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
