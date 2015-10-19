<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\MenuStyle;

/**
 * Interface MenuItemInterface
 *
 * @package PhpSchool\CliMenu\MenuItem
 * @author Michael Woodward <mikeymike.mw@gmail.com>
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

    /**
     * Whether or not the menu item is showing the menustyle extra value
     *
     * @return bool
     */
    public function showsItemExtra();
}
