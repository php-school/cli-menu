<?php

namespace MikeyMike\CliMenu\MenuItem;

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\MenuStyle;

/**
 * Class TextItem
 * @author Michael Woodward <michael@wearejh.com>
 */
class TextItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * Initialise text item
     *
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * The output text for the item
     *
     * @param MenuStyle $menu
     * @return string
     */
    public function getText(MenuStyle $menu)
    {
        return $this->text;
    }

    /**
     * Can the item be selected
     *
     * @return bool
     */
    public function canSelect()
    {
        return true;
    }

    /**
     * @param CliMenu $menu
     * @return void
     */
    public function execute(CliMenu $menu)
    {
        return;
    }
}
