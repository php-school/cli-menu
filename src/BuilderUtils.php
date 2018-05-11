<?php

namespace PhpSchool\CliMenu;

use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use RuntimeException;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
trait BuilderUtils
{
    /**
     * @var null|Builder
     */
    private $parent;

    /**
     * @var self[]
     */
    private $subMenuBuilders = [];

    /**
     * @var CliMenu[]
     */
    private $subMenus = [];

    /**
     * @var array
     */
    private $menuItems = [];

    public function addItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $this->menuItems[] = new SelectableItem($text, $itemCallable, $showItemExtra, $disabled);

        return $this;
    }

    public function addStaticItem(string $text) : self
    {
        $this->menuItems[] = new StaticItem($text);

        return $this;
    }

    public function addLineBreak(string $breakChar = ' ', int $lines = 1) : self
    {
        $this->menuItems[] = new LineBreakItem($breakChar, $lines);

        return $this;
    }
    
    /**
     * Add a submenu with a name. The name will be displayed as the item text
     * in the parent menu.
     */
    public function addSubMenu(string $name, CliMenuBuilder $subMenuBuilder = null) : Builder
    {
        $this->menuItems[]  = $id = 'submenu-placeholder-' . $name;

        if (null === $subMenuBuilder) {
            $this->subMenuBuilders[$id] = new CliMenuBuilder($this);
            return $this->subMenuBuilders[$id];
        }

        $this->subMenuBuilders[$id] = $subMenuBuilder;
        return $this;
    }

    private function buildSubMenus(array $items) : array
    {
        return array_map(function ($item) {
            if (!is_string($item) || 0 !== strpos($item, 'submenu-placeholder-')) {
                return $item;
            }

            $menuBuilder           = $this->subMenuBuilders[$item];
            $this->subMenus[$item] = $menuBuilder->build();

            return new MenuMenuItem(
                substr($item, \strlen('submenu-placeholder-')),
                $this->subMenus[$item],
                $menuBuilder->isMenuDisabled()
            );
        }, $items);
    }

    /**
     * Return to parent builder
     *
     * @throws RuntimeException
     */
    public function end() : ?Builder
    {
        if (null === $this->parent) {
            throw new RuntimeException('No parent builder to return to');
        }

        return $this->parent;
    }
}
