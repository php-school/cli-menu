<?php

namespace PhpSchool\CliMenu\Builder;

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
     * Add a submenu with a unique ID and the text. The text will be displayed as the item text
     * in the parent menu.
     */
    public function addSubMenu(string $id, string $text) : CliMenuBuilder
    {
        if (isset($this->subMenuBuilders[$id])) {
            throw new \InvalidArgumentException(
                sprintf('SubMenu with id: "%s" already exists. $id must be unique', $id)
            );
        }
        
        $this->menuItems[] = [
            'type' => 'submenu-placeholder',
            'text' => $text,
            'id'   => $id
        ];
        
        $this->subMenuBuilders[$id] = new CliMenuBuilder($this);
        return $this->subMenuBuilders[$id];
    }

    /**
     * Add a submenu from an existing builder. Required a unique ID and the text. The text will be displayed as the
     * item text in the parent menu.
     */
    public function addSubMenuFromExistingBuilder(string $id, string $text, CliMenuBuilder $subMenuBuilder) : self
    {
        if (isset($this->subMenuBuilders[$id])) {
            throw new \InvalidArgumentException(
                sprintf('SubMenu with id: "%s" already exists. $id must be unique', $id)
            );
        }

        $this->menuItems[] = [
            'type' => 'submenu-placeholder',
            'text' => $text,
            'id'   => $id
        ];

        $this->subMenuBuilders[$id] = $subMenuBuilder;
        return $this;
    }

    private function buildSubMenus(array $items) : array
    {
        return array_map(function ($item) {
            if (!is_array($item) || $item['type'] !== 'submenu-placeholder') {
                return $item;
            }

            $menuBuilder                 = $this->subMenuBuilders[$item['id']];
            $this->subMenus[$item['id']] = $menuBuilder->build();

            return new MenuMenuItem(
                $item['text'],
                $this->subMenus[$item['id']],
                $menuBuilder->isMenuDisabled()
            );
        }, $items);
    }
}
