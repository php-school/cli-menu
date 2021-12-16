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
class LineBreakItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $breakChar;

    /**
     * @var int
     */
    private $lines;

    /**
     * @var DefaultStyle
     */
    private $style;

    public function __construct(string $breakChar = ' ', int $lines = 1)
    {
        $this->breakChar = $breakChar;
        $this->lines = $lines;

        $this->style = new DefaultStyle();
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        return explode(
            "\n",
            rtrim(str_repeat(sprintf(
                "%s\n",
                mb_substr(str_repeat($this->breakChar, $style->getContentWidth()), 0, $style->getContentWidth())
            ), $this->lines))
        );
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
        return $this->breakChar;
    }

    /**
     * Set the raw string of text
     */
    public function setText(string $text) : void
    {
        $this->breakChar = $text;
    }

    /**
     * Whether or not the menu item is showing the menustyle extra value
     */
    public function showsItemExtra() : bool
    {
        return false;
    }

    public function getLines() : int
    {
        return $this->lines;
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
