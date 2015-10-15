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
use Assert\Assertion;

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
     * @var bool
     */
    private $isBuilt = false;

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
    private $addGoBackButton = true;

    /**
     * @var callable
     */
    private $itemCallable;

    /**
     * @var array
     */
    private $menuItems = [];

    /**
     * @var array
     */
    private $menuActions = [];

    /**
     * @var array
     */
    private $style = [];

    /**
     * @var TerminalInterface
     */
    private $terminal;

    /**
     * @var string
     */
    private $menuTitle;

    /**
     * @var bool
     */
    private $disableDefaultActions = false;

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
     * Pull the contructer params into an array with default values
     *
     * @return array
     */
    private function getStyleClassDefaults()
    {
        $styleClassParameters = (new \ReflectionClass(MenuStyle::class))->getConstructor()->getParameters();

        $defaults = [];
        foreach ($styleClassParameters as $parameter) {
            $defaults[$parameter->getName()] = $parameter->getDefaultValue();
        }

        return $defaults;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        Assertion::string($title);

        $this->menuTitle = $title;

        return $this;
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
     * @param MenuItemInterface $item
     * @return $this
     */
    private function addMenuItem(MenuItemInterface $item)
    {
        $this->menuItems[] = $item;

        return $this;
    }

    /**
     * @param $text
     * @param callable $action
     * @return $this
     */
    public function addAction($text, callable $action)
    {
        Assertion::string($text);

        $this->menuActions[] = new SelectableItem($text, $action);

        return $this;
    }

    /**
     * @param $text
     * @param bool|false $showItemExtra
     * @return $this
     */
    public function addItem($text, $showItemExtra = false)
    {
        Assertion::string($text);

        $this->addMenuItem(new MenuItem($text, $showItemExtra));

        return $this;
    }

    /**
     * @param $text
     * @return $this
     */
    public function addStaticItem($text)
    {
        Assertion::string($text);

        $this->addMenuItem(new StaticItem($text));

        return $this;
    }

    /**
     * @param string $breakChar
     * @param int $lines
     * @return $this
     */
    public function addLineBreak($breakChar = ' ', $lines = 1)
    {
        Assertion::string($breakChar);
        Assertion::integer($lines);

        $this->addMenuItem(new LineBreakItem($breakChar, $lines));

        return $this;
    }

    /**
     * @param $art
     * @param string $position
     * @return $this
     */
    public function addAsciiArt($art, $position = AsciiArtItem::POSITION_CENTER)
    {
        Assertion::string($art);
        Assertion::string($position);

        $this->addMenuItem(new AsciiArtItem($art, $position));

        return $this;
    }

    /**
     * @param string $id ID to reference and retrieve sub-menu
     *
     * @return CliMenuBuilder
     */
    public function addSubMenuAsAction($id)
    {
        Assertion::string($id);

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
        Assertion::string($id);

        $this->menuItems[]   = $id;
        $this->subMenus[$id] = new self($this);

        return $this->subMenus[$id];
    }

    /**
     * Add go back button to navigate to the parent menu
     */
    public function disableGoBackButton()
    {
        $this->addGoBackButton = false;

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
     * @param string $colour
     * @return $this
     */
    public function setBackgroundColour($colour)
    {
        Assertion::inArray($colour, MenuStyle::getAvailableColours());

        $this->style['bg'] = $colour;

        return $this;
    }

    /**
     * @param string $colour
     * @return $this
     */
    public function setForegroundColour($colour)
    {
        Assertion::inArray($colour, MenuStyle::getAvailableColours());

        $this->style['fg'] = $colour;

        return $this;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        Assertion::integer($width);

        $this->style['width'] = $width;

        return $this;
    }

    /**
     * @param int $padding
     * @return $this
     */
    public function setPadding($padding)
    {
        Assertion::integer($padding);

        $this->style['padding'] = $padding;

        return $this;
    }

    /**
     * @param int $margin
     * @return $this
     */
    public function setMargin($margin)
    {
        Assertion::integer($margin);

        $this->style['margin'] = $margin;

        return $this;
    }

    /**
     * @param srting $marker
     * @return $this
     */
    public function setUnselectedMarker($marker)
    {
        Assertion::string($marker);

        $this->style['unselectedMarker'] = $marker;

        return $this;
    }

    /**
     * @param string $marker
     * @return $this
     */
    public function setSelectedMarker($marker)
    {
        Assertion::string($marker);

        $this->style['selectedMarker'] = $marker;

        return $this;
    }

    /**
     * @param string $extra
     * @return $this
     */
    public function setItemExtra($extra)
    {
        Assertion::string($extra);

        $this->style['itemExtra'] = $extra;

        return $this;
    }

    /**
     * @param $displayExtra
     * @return $this
     */
    public function displayItemExtra($displayExtra)
    {
        Assertion::boolean($displayExtra);

        $this->style['displayExtra'] = $displayExtra;

        return $this;
    }

    /**
     * @param TerminalInterface $terminal
     * @return $this
     */
    public function setTerminal(TerminalInterface $terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }

    /**
     * @return array
     */
    private function getDefaultActions()
    {
        return [
            new SelectableItem('Exit', function (CliMenu $menu) {
                $menu->close();
            })
        ];
    }

    /**
     * @return $this
     */
    public function disableDefaultActions()
    {
        $this->disableDefaultActions = true;

        return $this;
    }

    /**
     * @return bool
     */
    private function itemsHaveExtra()
    {
        return !empty(array_filter($this->menuItems, function (MenuItemInterface $item) {
            return $item->showsItemExtra();
        }));
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

            $menuBuilder           = $this->subMenus[$item];
            $this->subMenus[$item] = $menuBuilder->build();

            return new MenuMenuItem($item, $this->subMenus[$item]);
        }, $items);
    }

    /**
     * @return CliMenu
     */
    public function build()
    {
        $this->isBuilt = true;

        $goBackButtons = [];
        foreach ($this->subMenus as $id => $menuBuilder) {
            $goBackButtons[$id] = $menuBuilder->hasGoBackButton();
        }

        $mergedActions = $this->disableDefaultActions
            ? $this->menuActions
            : array_merge($this->menuActions, $this->getDefaultActions());

        $menuActions = $this->buildSubMenus($mergedActions);
        $menuItems   = $this->buildSubMenus($this->menuItems);

        if ($this->itemsHaveExtra()) {
            $this->style['displaysExtra'] = true;
        }

        $menu = new CliMenu(
            $this->menuTitle ?: false,
            $menuItems,
            $this->itemCallable,
            $menuActions,
            $this->terminal,
            new MenuStyle(...array_values($this->style))
        );

        foreach (array_filter($goBackButtons) as $subMenu => $goBackButton) {
            $this->subMenus[$subMenu]->addAction(new SelectableItem('Go Back', function () use ($menu) {
                $menu->display();
            }));
        }

        return $menu;
    }
}
