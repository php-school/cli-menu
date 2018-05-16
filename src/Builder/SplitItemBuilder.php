<?php

namespace PhpSchool\CliMenu\Builder;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class SplitItemBuilder
{
    /**
     * @var CliMenu
     */
    private $menu;

    /**
     * @var SplitItem
     */
    private $splitItem;

    public function __construct(CliMenu $menu)
    {
        $this->menu = $menu;
        $this->splitItem = new SplitItem();
    }

    public function addItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $this->splitItem->addItem(new SelectableItem($text, $itemCallable, $showItemExtra, $disabled));

        return $this;
    }

    public function addStaticItem(string $text) : self
    {
        $this->splitItem->addItem(new StaticItem($text));

        return $this;
    }

    public function addLineBreak(string $breakChar = ' ', int $lines = 1) : self
    {
        $this->splitItem->addItem(new LineBreakItem($breakChar, $lines));

        return $this;
    }

    public function addSubMenu(string $text, \Closure $callback) : self
    {
        $builder = CliMenuBuilder::newSubMenu($this->menu->getTerminal());

        $callback = $callback->bindTo($builder);
        $callback($builder);

        $menu = $builder->build();
        $menu->setParent($this->menu);

        $this->splitItem->addItem(new MenuMenuItem(
            $text,
            $menu,
            $builder->isMenuDisabled()
        ));
        
        return $this;
    }

    public function setGutter(int $gutter) : self
    {
        $this->splitItem->setGutter($gutter);
        
        return $this;
    }
    
    public function build() : SplitItem
    {
        return $this->splitItem;
    }
}
