<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\MenuStyle;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
interface MenuItemInterface
{
    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array;

    /**
     * Return the raw string of text
     */
    public function getText() : string;

    /**
     * Can the item be selected
     */
    public function canSelect() : bool;

    /**
     * Execute the items callable if required
     */
    public function getSelectAction() : ?callable;

    /**
     * Whether or not the menu item is showing the menustyle extra value
     */
    public function showsItemExtra() : bool;

    /**
     * Enable showing item extra
     */
    public function showItemExtra() : void;

    /**
     * Disable showing item extra
     */
    public function hideItemExtra() : void;
}
