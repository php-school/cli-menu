<?php

namespace MikeyMike\CliMenu\MenuItem;

use MikeyMike\CliMenu\MenuStyle;

/**
 * Class MenuItemInterface
 * @author Michael Woodward <michael@wearejh.com>
 */
interface MenuItemInterface
{
    /**
     * The output text for the item
     *
     * @param MenuStyle $style
     * @param bool $selected
     * @return array
     */
    public function getRows(MenuStyle $style, $selected = false);

    /**
     * Return the raw string of text
     *
     * @return string
     */
    public function getText();

    /**
     * Can the item be selected
     *
     * @return bool
     */
    public function canSelect();

    /**
     * Execute the items callable if required
     *
     * @return callable|void
     */
    public function getSelectAction();
}
