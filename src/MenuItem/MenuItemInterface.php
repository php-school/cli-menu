<?php

namespace MikeyMike\CliMenu\MenuItem;

/**
 * Class MenuItemInterface
 * @author Michael Woodward <michael@wearejh.com>
 */
interface MenuItemInterface
{
    /**
     * The output text for the item
     *
     * @param int $menuWidth
     * @return array
     */
    public function getRows($menuWidth);

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
