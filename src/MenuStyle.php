<?php

namespace PhpSchool\CliMenu;

use PhpSchool\CliMenu\Exception\InvalidInstantiationException;
use PhpSchool\CliMenu\Terminal\TerminalFactory;
use PhpSchool\CliMenu\Util\ColourUtil;
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
    private $invertedColoursUnsetCode = "\033[27m";

    /**
     * @var string
     */
    private $coloursResetCode = "\033[0m";

    /**
     * @var int
     */
    private $borderTopWidth;

    /**
     * @var int
     */
    private $borderRightWidth;

    /**
     * @var int
     */
    private $borderBottomWidth;

    /**
     * @var int
     */
    private $borderLeftWidth;

    /**
     * @var string
     */
    private $borderColour = 'white';

    /**
     * @var array
     */
    private $borderTopRows = [];

    /**
     * @var array
     */
    private $borderBottomRows = [];

    /**
     * @var bool
     */
    private $marginAuto = false;

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
        'borderTopWidth' => 0,
        'borderRightWidth' => 0,
        'borderBottomWidth' => 0,
        'borderLeftWidth' => 0,
        'borderColour' => 'white',
        'marginAuto' => false,
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

        $this->fg = static::$defaultStyleValues['fg'];
        $this->bg = static::$defaultStyleValues['bg'];
        
        $this->generateColoursSetCode();
        
        $this->setWidth(static::$defaultStyleValues['width']);
        $this->setPadding(static::$defaultStyleValues['padding']);
        $this->setMargin(static::$defaultStyleValues['margin']);
        $this->setSelectedMarker(static::$defaultStyleValues['selectedMarker']);
        $this->setUnselectedMarker(static::$defaultStyleValues['unselectedMarker']);
        $this->setItemExtra(static::$defaultStyleValues['itemExtra']);
        $this->setDisplaysExtra(static::$defaultStyleValues['displaysExtra']);
        $this->setTitleSeparator(static::$defaultStyleValues['titleSeparator']);
        $this->setBorderTopWidth(static::$defaultStyleValues['borderTopWidth']);
        $this->setBorderRightWidth(static::$defaultStyleValues['borderRightWidth']);
        $this->setBorderBottomWidth(static::$defaultStyleValues['borderBottomWidth']);
        $this->setBorderLeftWidth(static::$defaultStyleValues['borderLeftWidth']);
        $this->setBorderColour(static::$defaultStyleValues['borderColour']);
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
        if (!ctype_digit($this->fg)) {
            $fgCode = self::$availableForegroundColors[$this->fg];
        } else {
            $fgCode = sprintf("38;5;%s", $this->fg);
        }

        if (!ctype_digit($this->bg)) {
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
     * Get the inverted escape sequence (used for selected elements)
     */
    public function getInvertedColoursUnsetCode() : string
    {
        return $this->invertedColoursUnsetCode;
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
        $this->contentWidth = $this->width
            - ($this->padding * 2)
            - ($this->borderRightWidth + $this->borderLeftWidth);
    }

    public function getFg()
    {
        return $this->fg;
    }

    public function setFg(string $fg, string $fallback = null) : self
    {
        $this->fg = ColourUtil::validateColour(
            $this->terminal,
            $fg,
            $fallback
        );
        $this->generateColoursSetCode();

        return $this;
    }

    public function getBg()
    {
        return $this->bg;
    }

    public function setBg(string $bg, string $fallback = null) : self
    {
        $this->bg = ColourUtil::validateColour(
            $this->terminal,
            $bg,
            $fallback
        );
        $this->generateColoursSetCode();

        return $this;
    }

    public function getWidth() : int
    {
        return $this->width;
    }

    public function setWidth(int $width) : self
    {
        if ($width >= $this->terminal->getWidth()) {
            $width = $this->terminal->getWidth();
        }

        $this->width = $width;
        if ($this->marginAuto) {
            $this->setMarginAuto();
        }

        $this->calculateContentWidth();
        $this->generateBorderRows();

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

    public function setMarginAuto() : self
    {
        $this->marginAuto = true;
        $this->margin = floor(($this->terminal->getWidth() - $this->width) / 2);

        $this->generateBorderRows();

        return $this;
    }

    public function setMargin(int $margin) : self
    {
        $this->marginAuto = false;
        $this->margin = $margin;

        $this->generateBorderRows();

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

    private function generateBorderRows() : void
    {
        $borderRow = sprintf(
            "%s%s%s%s%s\n",
            str_repeat(' ', $this->margin),
            $this->getBorderColourCode(),
            str_repeat(' ', $this->width),
            $this->coloursResetCode,
            str_repeat(' ', $this->margin)
        );

        $this->borderTopRows = array_fill(0, $this->borderTopWidth, $borderRow);
        $this->borderBottomRows = array_fill(0, $this->borderBottomWidth, $borderRow);
    }

    public function getBorderTopRows() : array
    {
        return $this->borderTopRows;
    }

    public function getBorderBottomRows() : array
    {
        return $this->borderBottomRows;
    }

    /**
     * Shorthand function to set all borders values at once
     */
    public function setBorder(
        int $topWidth,
        $rightWidth = null,
        $bottomWidth = null,
        $leftWidth = null,
        string $colour = null
    ) : self {
        if (!is_int($rightWidth)) {
            $colour = $rightWidth;
            $rightWidth = $bottomWidth = $leftWidth = $topWidth;
        } elseif (!is_int($bottomWidth)) {
            $colour = $bottomWidth;
            $bottomWidth = $topWidth;
            $leftWidth = $rightWidth;
        } elseif (!is_int($leftWidth)) {
            $colour = $leftWidth;
            $leftWidth = $rightWidth;
        }

        $this->borderTopWidth = $topWidth;
        $this->borderRightWidth = $rightWidth;
        $this->borderBottomWidth = $bottomWidth;
        $this->borderLeftWidth = $leftWidth;

        if (is_string($colour)) {
            $this->setBorderColour($colour);
        } elseif ($colour !== null) {
            throw new \InvalidArgumentException('Invalid colour');
        }

        $this->calculateContentWidth();
        $this->generateBorderRows();

        return $this;
    }

    public function setBorderTopWidth(int $width) : self
    {
        $this->borderTopWidth = $width;

        $this->generateBorderRows();

        return $this;
    }

    public function setBorderRightWidth(int $width) : self
    {
        $this->borderRightWidth = $width;
        $this->calculateContentWidth();

        return $this;
    }

    public function setBorderBottomWidth(int $width) : self
    {
        $this->borderBottomWidth = $width;

        $this->generateBorderRows();

        return $this;
    }

    public function setBorderLeftWidth(int $width) : self
    {
        $this->borderLeftWidth = $width;
        $this->calculateContentWidth();

        return $this;
    }

    public function setBorderColour(string $colour, $fallback = null) : self
    {
        $this->borderColour = ColourUtil::validateColour(
            $this->terminal,
            $colour,
            $fallback
        );

        $this->generateBorderRows();

        return $this;
    }

    public function getBorderTopWidth() : int
    {
        return $this->borderTopWidth;
    }

    public function getBorderRightWidth() : int
    {
        return $this->borderRightWidth;
    }

    public function getBorderBottomWidth() : int
    {
        return $this->borderBottomWidth;
    }

    public function getBorderLeftWidth() : int
    {
        return $this->borderLeftWidth;
    }

    public function getBorderColour() : string
    {
        return $this->borderColour;
    }

    public function getBorderColourCode() : string
    {
        if (!ctype_digit($this->borderColour)) {
            $borderColourCode = self::$availableBackgroundColors[$this->borderColour];
        } else {
            $borderColourCode = sprintf("48;5;%s", $this->borderColour);
        }

        return sprintf("\033[%sm", $borderColourCode);
    }
}
