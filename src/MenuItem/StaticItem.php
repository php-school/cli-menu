<?php

namespace PhpSchool\CliMenu\MenuItem;
use PhpSchool\CliMenu\Style;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Util\StringUtil;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class StaticItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var Style\ItemStyleInterface;
     */
    private $style;

    public function __construct(string $text)
    {
        $this->text = $text;

        $this->style = new Style\StaticStyle();
    }

    public function getStyle() : Style\ItemStyleInterface
    {
        return $this->style;
    }

    /**
     * @param Style\ItemStyleInterface|Style\SelectableStyle $style
     * @return $this
     */
    public function setStyle(Style\ItemStyleInterface $style) : self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        return explode("\n", StringUtil::wordwrap($this->text, $style->getContentWidth()));
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
        $this->text = $text;
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
}
