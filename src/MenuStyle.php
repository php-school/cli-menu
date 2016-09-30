<?php

namespace PhpSchool\CliMenu;

use PhpSchool\CliMenu\Exception\InvalidInstantiationException;
use PhpSchool\CliMenu\Terminal\TerminalFactory;
use PhpSchool\CliMenu\Terminal\TerminalInterface;

//TODO: B/W fallback

/**
 * Class MenuStyle
 *
 * @package PhpSchool\CliMenu
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
     * @var string
     */
    private $allowedConsumer = 'PhpSchool\CliMenu\CliMenuBuilder';

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
     *
     * @param string $bg
     * @param string $fg
     * @param int $width
     * @param int $padding
     * @param int $margin
     * @param string $unselectedMarker
     * @param string $selectedMarker
     * @param string $itemExtra
     * @param bool $displaysExtra
     * @param string $titleSeparator
     * @param TerminalInterface $terminal
     * @throws InvalidInstantiationException
     */
    public function __construct(
        $bg = 'blue',
        $fg = 'white',
        $width = 100,
        $padding = 2,
        $margin = 2,
        $unselectedMarker = '○',
        $selectedMarker = '●',
        $itemExtra = '✔',
        $displaysExtra = false,
        $titleSeparator = '=',
        TerminalInterface $terminal = null
    ) {
        $builder = debug_backtrace();
        if (count($builder) < 2 || !isset($builder[1]['class']) || $builder[1]['class'] !== $this->allowedConsumer) {
            throw new InvalidInstantiationException(
                sprintf('The MenuStyle must be instantiated by "%s"', $this->allowedConsumer)
            );
        }

        $this->terminal        = $terminal ?: TerminalFactory::fromSystem();
        $this->bg              = $bg;
        $this->fg              = $fg;
        $this->padding         = $padding;
        $this->margin          = $margin;
        $this->itemExtra       = $itemExtra;
        $this->displaysExtra   = $displaysExtra;
        $this->titleSeparator  = $titleSeparator;

        $this->setUnselectedMarker($unselectedMarker);
        $this->setSelectedMarker($selectedMarker);
        $this->setWidth($width);
        $this->calculateContentWidth();
    }

    /**
     * @return array
     */
    public static function getAvailableColours()
    {
        return array_keys(self::$availableBackgroundColors);
    }

    /**
     * @param string $text
     * @return string
     */
    public function getDisabledItemText($text)
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
     *
     * @return string
     */
    public function getSelectedSetCode()
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
     *
     * @return string
     */
    public function getSelectedUnsetCode()
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
     *
     * @return string
     */
    public function getUnselectedSetCode()
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
     *
     * @return string
     */
    public function getUnselectedUnsetCode()
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
            $width = $availableWidth;
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

    /**
     * @return string
     */
    public function getSelectedMarker()
    {
        return $this->selectedMarker;
    }

    /**
     * @param string $marker
     * @return $this
     */
    public function setSelectedMarker($marker)
    {
        $this->selectedMarker = mb_substr($marker, 0, 1);

        return $this;
    }

    /**
     * @return string
     */
    public function getUnselectedMarker()
    {
        return $this->unselectedMarker;
    }

    /**
     * @param string $marker
     * @return $this
     */
    public function setUnselectedMarker($marker)
    {
        $this->unselectedMarker = mb_substr($marker, 0, 1);

        return $this;
    }

    /**
     * Get the correct marker for the item
     *
     * @param bool $selected
     * @return string
     */
    public function getMarker($selected)
    {
        return $selected ? $this->selectedMarker : $this->unselectedMarker;
    }

    /**
     * @param string $itemExtra
     */
    public function setItemExtra($itemExtra)
    {
        $this->itemExtra = $itemExtra;
    }

    /**
     * @return string
     */
    public function getItemExtra()
    {
        return $this->itemExtra;
    }

    /**
     * @return bool
     */
    public function getDisplaysExtra()
    {
        return $this->displaysExtra;
    }

    /**
     * @param bool $displaysExtra
     * @return $this
     */
    public function setDisplaysExtra($displaysExtra)
    {
        $this->displaysExtra = $displaysExtra;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitleSeparator()
    {
        return $this->titleSeparator;
    }

    /**
     * @param string $actionSeparator
     * @return $this
     */
    public function setTitleSeparator($actionSeparator)
    {
        $this->titleSeparator = $actionSeparator;

        return $this;
    }
}
