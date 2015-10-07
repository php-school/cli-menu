<?php

namespace MikeyMike\CliMenu;

use MikeyMike\CliMenu\MenuItem\MenuItemInterface;
use MikeyMike\CliMenu\MenuItem\MenuMenuItem;
use MikeyMike\CliMenu\Terminal\TerminalInterface;

/**
 * Class CliMenuBuilder
 *
 * Provides a simple and fluent API for building a CliMenu
 *
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class CliMenuBuilder
{
    /**
     * @var CliMenu
     */
    private $menu;

    /**
     * Initilize an empty CliMenu
     * @param bool|string $title
     */
    public function __construct($title = false)
    {
        $this->menu = new CliMenu($title);
    }

    /**
     * @param MenuItemInterface $item
     * @return $this
     */
    public function addItem(MenuItemInterface $item)
    {
        $this->menu->addItem($item);

        return $this;
    }

    /**
     * @param MenuItemInterface $action
     * @return $this
     */
    public function addAction(MenuItemInterface $action)
    {
        $this->menu->addAction($action);

        return $this;
    }

    /**
     * @param $text
     * @param CliMenu $menu
     * @return $this
     */
    public function addSubMenuAsItem($text, CliMenu $menu)
    {
        $this->menu->addItem(
            new MenuMenuItem($text, $menu)
        );

        return $this;
    }

    /**
     * @param $text
     * @param CliMenu $menu
     * @return $this
     */
    public function addSubMenuAsAction($text, CliMenu $menu)
    {
        $this->menu->addAction(
            new MenuMenuItem($text, $menu)
        );

        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setItemCallback(callable $callback)
    {
        $this->menu->setItemCallback($callback);

        return $this;
    }

    /**
     * @param TerminalInterface $terminal
     * @return $this
     */
    public function setTerminal(TerminalInterface $terminal)
    {
        $this->menu->setTerminal($terminal);

        return $this;
    }

    /**
     * @param MenuStyle $style
     * @return $this
     */
    public function setMenuStyle(MenuStyle $style)
    {
        $this->menu->setMenuStyle($style);

        return $this;
    }

    /**
     * @return CliMenu
     */
    public function build()
    {
        return $this->menu;
    }
}
