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

/**
 * Class CliMenuBuilder
 *
 * Provides a simple and fluent API for building a CliMenu
 *
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class CliMenuBuilder
{
    private $itemCallable = [];

    private $menuItems = [];

    private $menuActions = [];

    private $isSubMenu = false;

    private $style = [];

    private $terminal;

    private $menuTitle;

    public function __construct(callable $itemAction)
    {
        $this->itemCallable      = $itemAction;
        $this->terminal          = TerminalFactory::fromSystem();
        $this->style             = $this->getStyleClassDefaults();
        $this->style['terminal'] = $this->terminal;
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

    private function getActionItems()
    {
        
    }

    public function build()
    {
        return new CliMenu(
            $this->menuTitle ?: false,
            $this->menuItems,
            $this->itemCallable,
            $this->menuActions,
            $this->terminal,
            new MenuStyle(...array_values($this->style))
        );
    }
}
