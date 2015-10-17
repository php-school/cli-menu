<?php

namespace MikeyMike\CliMenu;

use MikeyMike\CliMenu\MenuItem\AsciiArtItem;
use MikeyMike\CliMenu\MenuItem\LineBreakItem;
use MikeyMike\CliMenu\MenuItem\MenuItemInterface;
use MikeyMike\CliMenu\MenuItem\MenuMenuItem;
use MikeyMike\CliMenu\MenuItem\SelectableItem;
use MikeyMike\CliMenu\MenuItem\StaticItem;
use MikeyMike\CliMenu\Terminal\TerminalFactory;
use MikeyMike\CliMenu\Terminal\TerminalInterface;
use Assert\Assertion;
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
    /**
     * @var bool
     */
    private $isBuilt = false;

    /**
     * @var null|self
     */
    private $parent;
    /**
     * @var self[]|CliMenu[]
     */
    private $subMenus = [];

    /**
     * @var string
     */
    private $goBackButtonText = 'Go Back';
    

    /**
     * @var string
     */
    private $exitButtonText = 'Exit';

    /**
     * @var array
     */
    private $menuItems = [];

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
    private $disableDefaultItems = false;

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
     * @param callable $itemCallable
     * @param bool|false $showItemExtra
     * @return $this
     */
    public function addItem($text, callable $itemCallable, $showItemExtra = false)
    {
        Assertion::string($text);

        $this->addMenuItem(new SelectableItem($text, $itemCallable, $showItemExtra));

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
    public function addSubMenu($id)
    {
        Assertion::string($id);

        $this->menuItems[]   = $id;
        $this->subMenus[$id] = new self($this);

        return $this->subMenus[$id];
    }

    /**
     * @param string $goBackButtonTest
     * @return self
     */
    public function setGoBackButtonText($goBackButtonTest)
    {
        $this->goBackButtonText = $goBackButtonTest;
        
        return $this;
    }

    /**
     * @param string $exitButtonText
     * @return self
     */
    public function setExitButtonText($exitButtonText)
    {
        $this->exitButtonText = $exitButtonText;
        
        return $this;
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
     * @param string $marker
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
     * @param string $separator
     * @return $this
     */
    public function setTitleSeparator($separator)
    {
        Assertion::string($separator);

        $this->style['titleSeparator'] = $separator;

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
    private function getDefaultItems()
    {
        $actions = [];
        if ($this->parent) {
            $actions[] = new SelectableItem($this->goBackButtonText, function (CliMenu $child) {
                if ($parent = $child->getParent()) {
                    $parent->display();
                    $child->closeThis();
                }
            });
        }
        
        $actions[] = new SelectableItem($this->exitButtonText, function (CliMenu $menu) {
            $menu->close();
        });
        return $actions;
    }

    /**
     * @return $this
     */
    public function disableDefaultItems()
    {
        $this->disableDefaultItems = true;

        return $this;
    }

    /**
     * @param array $items
     * @return bool
     */
    private function itemsHaveExtra(array $items)
    {
        return !empty(array_filter($items, function (MenuItemInterface $item) {
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

        $mergedItems = $this->disableDefaultItems
            ? $this->menuItems
            : array_merge($this->menuItems, $this->getDefaultItems());

        $menuItems = $this->buildSubMenus($mergedItems);

        $this->style['displaysExtra'] = $this->itemsHaveExtra($menuItems);

        $menu = new CliMenu(
            $this->menuTitle ?: false,
            $menuItems,
            $this->terminal,
            new MenuStyle(...array_values($this->style))
        );
        
        foreach ($this->subMenus as $subMenu) {
            $subMenu->setParent($menu);
        }

        return $menu;
    }
}
