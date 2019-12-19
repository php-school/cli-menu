<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Style;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class MenuMenuItem implements MenuItemInterface, SelectableInterface
{
    use SelectableTrait;
    
    /**
     * @var CliMenu|\Closure
     */
    private $subMenu;

    public function __construct(string $text, $subMenu, bool $disabled = false)
    {
        $this->text     = $text;
        $this->subMenu  = $subMenu;
        $this->disabled = $disabled;

        $this->style = new Style\SelectableStyle();
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
     * Returns the sub menu
     */
    public function getSubMenu() : CliMenu
    {
        if ($this->subMenu instanceof \Closure) {
            $this->subMenu = ($this->subMenu)();
        }

        return $this->subMenu;
    }

    /**
     * Display the sub menu
     */
    public function showSubMenu(CliMenu $parentMenu) : void
    {
        $parentMenu->closeThis();

        if ($this->subMenu instanceof \Closure) {
            $this->subMenu = ($this->subMenu)();
        }

        $this->subMenu->open();
    }
}
