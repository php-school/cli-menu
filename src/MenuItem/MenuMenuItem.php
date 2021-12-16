<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\ItemStyle;
use PhpSchool\CliMenu\Style\SelectableStyle;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class MenuMenuItem implements MenuItemInterface, PropagatesStyles
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var CliMenu
     */
    private $subMenu;

    /**
     * @var bool
     */
    private $showItemExtra = false;

    /**
     * @var bool
     */
    private $disabled;

    /**
     * @var SelectableStyle
     */
    private $style;

    public function __construct(
        string $text,
        CliMenu $subMenu,
        bool $disabled = false
    ) {
        $this->text = $text;
        $this->subMenu = $subMenu;
        $this->disabled = $disabled;

        $this->style = new SelectableStyle();
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        return (new SelectableItemRenderer())->render($style, $this, $selected, $this->disabled);
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
     * Execute the items callable if required
     */
    public function getSelectAction() : ?callable
    {
        return function (CliMenu $menu) {
            $this->showSubMenu($menu);
        };
    }

    /**
     * Returns the sub menu
     */
    public function getSubMenu() : CliMenu
    {
        return $this->subMenu;
    }

    /**
     * Display the sub menu
     */
    public function showSubMenu(CliMenu $parentMenu) : void
    {
        $parentMenu->closeThis();
        $this->subMenu->open();
    }

    /**
     * Can the item be selected
     */
    public function canSelect() : bool
    {
        return !$this->disabled;
    }

    /**
     * Enable showing item extra
     */
    public function showItemExtra() : void
    {
        $this->showItemExtra = true;
    }

    /**
     * Whether or not we are showing item extra
     */
    public function showsItemExtra() : bool
    {
        return $this->showItemExtra;
    }

    /**
     * Disable showing item extra
     */
    public function hideItemExtra() : void
    {
        $this->showItemExtra = false;
    }

    /**
     * @return SelectableStyle
     */
    public function getStyle() : ItemStyle
    {
        return $this->style;
    }

    public function setStyle(SelectableStyle $style) : void
    {
        $this->style = $style;
    }

    /**
     * @inheritDoc
     */
    public function propagateStyles(CliMenu $parent): void
    {
        $this->getSubMenu()->importStyles($parent);
        $this->getSubMenu()->propagateStyles();
    }
}
