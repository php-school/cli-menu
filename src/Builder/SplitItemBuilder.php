<?php

namespace PhpSchool\CliMenu\Builder;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class SplitItemBuilder implements Builder
{
    use BuilderUtils;

    /**
     * @var int
     */
    private $gutter = 2;

    /**
     * @var CliMenuBuilder
     */
    private $parent;

    public function __construct(CliMenuBuilder $parent)
    {
        $this->parent = $parent;
    }

    public function build() : SplitItem
    {
        $items = $this->buildSubMenus($this->menuItems);
        
        $splitItem = new SplitItem($items);
        $splitItem->setGutter($this->gutter);

        return $splitItem;
    }

    public function setSubMenuParents(CliMenu $menu) : void
    {
        foreach ($this->subMenus as $subMenu) {
            $subMenu->setParent($menu);
        }
    }

    public function getTerminal() : Terminal
    {
        return $this->parent->getTerminal();
    }

    public function getMenuStyle() : MenuStyle
    {
        return $this->parent->getMenuStyle();
    }

    public function end() : CliMenuBuilder
    {
        return $this->parent;
    }

    public function setGutter(int $gutter) : SplitItemBuilder
    {
        $this->gutter = $gutter;
    }
}
