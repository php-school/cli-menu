<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\DefaultStyle;
use PhpSchool\CliMenu\Style\ItemStyle;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class AsciiArtItem implements MenuItemInterface
{
    /**
     * Possible positions of the ascii art
     */
    const POSITION_CENTER = 'center';
    const POSITION_LEFT   = 'left';
    const POSITION_RIGHT  = 'right';

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $position;

    /**
     * @var string
     */
    private $alternateText;

    /**
     * @var int
     */
    private $artLength;

    /**
     * @var DefaultStyle
     */
    private $style;

    public function __construct(string $text, string $position = self::POSITION_CENTER, string $alt = '')
    {
        Assertion::inArray($position, [self::POSITION_CENTER, self::POSITION_RIGHT, self::POSITION_LEFT]);

        $this->setText($text);
        $this->position  = $position;
        $this->alternateText = $alt;

        $this->style = new DefaultStyle();
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        if ($this->artLength > $style->getContentWidth()) {
            $alternate = new StaticItem($this->alternateText);
            return $alternate->getRows($style, false);
        }

        $padding = $style->getContentWidth() - $this->artLength;

        return array_map(function ($row) use ($padding) {
            switch ($this->position) {
                case self::POSITION_LEFT:
                    break;
                case self::POSITION_RIGHT:
                    $row = sprintf('%s%s', str_repeat(' ', $padding), $row);
                    break;
                case self::POSITION_CENTER:
                default:
                    $left = (int) ceil($padding / 2);
                    $row = sprintf('%s%s', str_repeat(' ', $left), $row);
                    break;
            }

            return $row;
        }, explode("\n", $this->text));
    }

    /**
     * Can the item be selected
     */
    public function canSelect() : bool
    {
        return false;
    }

    /**
     * Execute the items callable if required
     */
    public function getSelectAction() : ?callable
    {
        return null;
    }

    /**
     * Return the raw string of text
     */
    public function getText() : string
    {
        return $this->text;
    }

    /**
     * Set the raw string of text
     */
    public function setText(string $text) : void
    {
        $this->text = implode("\n", array_map(function (string $line) {
            return rtrim($line, ' ');
        }, explode("\n", $text)));

        $this->calculateArtLength();
    }

    /**
     * Calculate the length of the art
     */
    private function calculateArtLength() : void
    {
        $this->artLength = (int) max(array_map('mb_strlen', explode("\n", $this->text)));
    }

    /**
     * Return the length of the art
     */
    public function getArtLength() : int
    {
        return $this->artLength;
    }

    public function getPosition() : string
    {
        return $this->position;
    }

    public function getAlternateText() : string
    {
        return $this->alternateText;
    }

    /**
     * Whether or not the menu item is showing the menustyle extra value
     */
    public function showsItemExtra() : bool
    {
        return false;
    }

    /**
     * Enable showing item extra
     */
    public function showItemExtra() : void
    {
        //noop
    }

    /**
     * Disable showing item extra
     */
    public function hideItemExtra() : void
    {
        //noop
    }

    /**
     * @return DefaultStyle
     */
    public function getStyle() : ItemStyle
    {
        return $this->style;
    }

    public function setStyle(DefaultStyle $style) : void
    {
        $this->style = $style;
    }
}
