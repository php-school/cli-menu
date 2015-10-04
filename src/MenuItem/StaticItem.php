<?php

namespace MikeyMike\CliMenu\MenuItem;

use MikeyMike\CliMenu\MenuStyle;

/**
 * Class StaticItem
 *
 * @author Michael Woodward <michael@wearejh.com>
 */
class StaticItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * The output text for the item
     *
     * @param MenuStyle $style
     * @return array
     */
    public function getRows(MenuStyle $style)
    {
        return explode("\n", wordwrap($this->text, $style->getContentWidth()));
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
     * @return void
     */
    public function getSelectAction()
    {
        return;
    }

    /**
     * Return the raw string of text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
