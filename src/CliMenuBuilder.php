<?php

namespace MikeyMike\CliMenu;

use MikeyMike\CliMenu\MenuItem\AsciiArtItem;
use MikeyMike\CliMenu\MenuItem\LineBreakItem;
use MikeyMike\CliMenu\MenuItem\MenuItem;
use MikeyMike\CliMenu\MenuItem\MenuItemInterface;
use MikeyMike\CliMenu\MenuItem\MenuMenuItem;
use MikeyMike\CliMenu\MenuItem\SelectableItem;
use MikeyMike\CliMenu\MenuItem\StaticItem;
use MikeyMike\CliMenu\Terminal\TerminalFactory;
use MikeyMike\CliMenu\Terminal\TerminalInterface;
use MikeyMike\CliMenu\MenuStyle;
use RuntimeException;

/**
 * Class CliMenuBuilder
 *
 * Provides a simple and fluent API for building a CliMenu
 *
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class CliMenuBuilder
{
    private $itemCallable;

    private $menuItems = [];

    private $menuActions = [];

    private $style = [];

    private $terminal;

    private $menuTitle;

    /**
     * @var null|self
     */
    private $parent;

    /**
     * @var self[]
     */
    private $subMenus = [];

    /**
     * @var bool
     */
    private $addGoBackButton = false;

    /**
     * @var bool
     */
    private $isBuilt = false;

    /**
     * @param CliMenuBuilder|null $parent
     */
    public function __construct(CliMenuBuilder $parent = null)
    {
        $this->parent            = $parent;
        $this->terminal          = TerminalFactory::fromSystem();
        $this->style             = $this->getStyleClassDefaults();
        $this->style['terminal'] = $this->terminal;
    }

    /**
     * @param callable $callable
     * @return self
     */
    public function addItemCallable(callable $callable)
    {
        $this->itemCallable = $callable;
        return $this;
    }

    /**
     * @param string $id ID to reference and retrieve sub-menu
     *
     * @return CliMenuBuilder
     */
    public function addSubMenuAsAction($id)
    {
        $this->menuActions[] = $id;
        $this->subMenus[$id] = new self($this);
        return $this->subMenus[$id];
    }

    /**
     * @param string $id ID to reference and retrieve sub-menu
     *
     * @return CliMenuBuilder
     */
    public function addSubMenuAsItem($id)
    {
        $this->menuItems[] = $id;
        $this->subMenus[$id] = new self($this);
        return $this->subMenus[$id];
    }

    /**
     * Add go back button to navigate to the parent menu
     */
    public function addGoBackAction()
    {
        $this->addGoBackButton = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasGoBackButton()
    {
        return $this->addGoBackButton;
    }

    /**
     * Return to parent builder
     *
     * @return CliMenuBuilder
     * @throws RuntimeException
     */
    public function end()
    {
        if (null === $this->parent) {
            throw new RuntimeException("No parent menu to return to");
        }

        return $this->parent;
    }

    /**
     * @param string $id
     * @return CliMenuBuilder
     * @throws RuntimeException
     */
    public function getSubMenu($id)
    {
        if (false === $this->isBuilt) {
            throw new RuntimeException(sprintf('Menu: "%s" cannot be retrieve until menu has been built', $id));
        }

        return $this->subMenus[$id];
    }

    private function getStyleClassDefaults()
    {
        $styleClassParameters = (new \ReflectionClass(MenuStyle::class))->getConstructor()->getParameters();

        $defaults = [];
        foreach ($styleClassParameters as $parameter) {
            $defaults[$parameter->getName()] = $parameter->getDefaultValue();
        }

        return $defaults;
    }

    public function setTitle($title)
    {
        $this->menuTitle = $title;
    }

    public function addMenuItem(MenuItemInterface $item)
    {
        $this->menuItems[] = $item;

        return $this;
    }

    public function addAction($text, callable $action)
    {
        $this->menuActions[] = new SelectableItem($text, $action);

        return $this;
    }

    public function addItem($text, $showItemExtra = false)
    {
        $this->addMenuItem(new MenuItem($text, $showItemExtra));

        return $this;
    }

    public function addStaticItem($text)
    {
        $this->addMenuItem(new StaticItem($text));

        return $this;
    }

    public function addLineBreak($breakChar = ' ', $lines = 1)
    {
        $this->addMenuItem(new LineBreakItem($breakChar, $lines));

        return $this;
    }

    public function addAsciiArt($art, $position = AsciiArtItem::POSITION_CENTER)
    {
        $this->addMenuItem(new AsciiArtItem($art, $position));

        return $this;
    }

    public function addSubMenu($text, CliMenu $subMenu)
    {
        $this->addMenuItem(new MenuMenuItem($text, $subMenu));

        return $this;
    }

    public function setAsSubMenu($backActionText = 'Go Back')
    {
        $this->addAction($backActionText, [MenuMenuItem::class, 'showParentMenu']);

        return $this;
    }

    public function setBackgroundColour($colour)
    {
        // TODO: Colour validation
        $this->style['bg'] = $colour;

        return $this;
    }

    public function setForegroundColour($colour)
    {
        // TODO: Colour validation
        $this->style['fg'] = $colour;

        return $this;
    }

    public function setWidth($width)
    {
        $this->style['width'] = $width;

        return $this;
    }

    public function setPadding($padding)
    {
        $this->style['padding'] = $padding;

        return $this;
    }

    public function setMargin($margin)
    {
        $this->style['margin'] = $margin;

        return $this;
    }

    public function setUnselectedMarker($marker)
    {
        $this->style['unselectedMarker'] = $marker;

        return $this;
    }

    public function setSelectedMarker($marker)
    {
        $this->style['selectedMarker'] = $marker;

        return $this;
    }

    public function setItemExtra($extra)
    {
        $this->style['itemExtra'] = $extra;

        return $this;
    }

    public function displayItemExtra($displayExtra)
    {
        $this->style['displayExtra'] = $displayExtra;

        return $this;
    }

    public function setTerminal(TerminalInterface $terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }

    /**
     * @param array $items
     * @return array
     */
    private function buildSubMenus(array $items)
    {
        return array_map(function ($item) {
            if (!is_string($item)) {
                return $item;
            }
            $menuBuilder = $this->subMenus[$item];
            $this->subMenus[$item] = $menuBuilder->build();
            return new MenuMenuItem($item, $this->subMenus[$item]);
        }, $items);
    }

    public function build()
    {
        $this->isBuilt = true;

        $goBackButtons = [];
        foreach ($this->subMenus as $id => $menuBuilder) {
            $goBackButtons[$id] = $menuBuilder->hasGoBackButton();
        }

        $menuItems      = $this->buildSubMenus($this->menuItems);
        $menuActions    = $this->buildSubMenus($this->menuActions);

        $menu =  new CliMenu(
            $this->menuTitle ?: false,
            $menuItems,
            $this->itemCallable,
            $menuActions,
            $this->terminal,
            new MenuStyle(...array_values($this->style))
        );

        foreach (array_filter($goBackButtons) as $subMenu => $goBackButtons) {
            $this->subMenus[$subMenu]->addAction(new SelectableItem('Go Back', function (CliMenu $subMenu) use ($menu) {
                $menu->display();
            }));
        }

        return $menu;
    }
}
