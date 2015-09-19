<?php

namespace MikeyMike\CliMenu\MenuItem;

use MikeyMike\CliMenu\CliMenu;
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
     * @param MenuStyle $menu
     * @return string
     */
    public function getText(MenuStyle $menu);

    /**
     * Can the item be selected
     *
     * @return bool
     */
    public function canSelect();

    /**
     * Execute the items callable if required
     *
     * @return void
     */
    public function execute(CliMenu $menu);
}
