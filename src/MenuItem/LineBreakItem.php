<?php

namespace MikeyMike\CliMenu\MenuItem;

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\MenuStyle;

/**
 * Class LineBreakItem
 * @author Michael Woodward <michael@wearejh.com>
 */
class LineBreakItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $breakChar;

    /**
     * Initialise text item
     *
     * @param string $text
     */
    public function __construct($breakChar = ' ')
    {
        $this->breakChar = $breakChar;
    }

    /**
     * The output text for the item
     *
     * @param MenuStyle $style
     * @return string
     */
    public function getText(MenuStyle $style)
    {
        return str_repeat($this->breakChar, $style->getContentWidth());
    }

    /**
     * Can the item be selected
     *
     * @return bool
     */
    public function canSelect()
    {
        return false;
    }

    /**
     * Execute the items callable if required
     *
     * @return callable
     */
    public function execute(CliMenu $menu)
    {
        return;
    }
}
