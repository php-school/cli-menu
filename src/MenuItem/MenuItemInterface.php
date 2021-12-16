<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\ItemStyle;

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

    /**
     * Get the items style object. Can and
     * should be subclassed to provide bespoke
     * behaviour.
     */
    public function getStyle() : ItemStyle;
}
