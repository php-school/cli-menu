<?php

namespace PhpSchool\CliMenu;

use PhpSchool\CliMenu\Action\ExitAction;
use PhpSchool\CliMenu\Action\GoBackAction;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\Terminal\TerminalFactory;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use Assert\Assertion;
use RuntimeException;

/**
 * Class CliMenuBuilder
 *
 * @package PhpSchool\CliMenu
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 * @author Aydin Hassan <aydin@hotmail.com>
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
     * @var bool
     */
    private $disabled = false;

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
     * Pull the constructor params into an array with default values
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
     * @param string $title
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
    public function addMenuItem(MenuItemInterface $item)
    {
        $this->menuItems[] = $item;

        return $this;
    }

    /**
     * @param string $text
     * @param callable $itemCallable
     * @param bool $showItemExtra
     * @param bool $disabled
     * @return $this
     */
    public function addItem($text, callable $itemCallable, $showItemExtra = false, $disabled = false)
    {
        Assertion::string($text);

        $this->addMenuItem(new SelectableItem($text, $itemCallable, $showItemExtra, $disabled));

        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function addItems(array $items)
    {
        foreach ($items as $item) {
            $this->addItem(...$item);
        }
        
        return $this;
    }

    /**
     * @param string $text
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
     * @param string $art
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
     * Disable a submenu
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function disableMenu()
    {
        if (!$this->parent) {
            throw new \InvalidArgumentException(
                'You can\'t disable the root menu'
            );
        }

        $this->disabled = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMenuDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param string $goBackButtonTest
     * @return $this
     */
    public function setGoBackButtonText($goBackButtonTest)
    {
        $this->goBackButtonText = $goBackButtonTest;
        
        return $this;
    }

    /**
     * @param string $exitButtonText
     * @return $this
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
        $this->style['terminal'] = $this->terminal;
        return $this;
    }

    /**
     * @return array
     */
    private function getDefaultItems()
    {
        $actions = [];
        if ($this->parent) {
            $actions[] = new SelectableItem($this->goBackButtonText, new GoBackAction);
        }
        
        $actions[] = new SelectableItem($this->exitButtonText, new ExitAction);
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
     * Recursively drop back to the parents menu style
     * when the current menu has a parent and has no changes
     *
     * @return MenuStyle
     */
    private function getMenuStyle()
    {
        $diff = array_udiff_assoc($this->style, $this->getStyleClassDefaults(), function ($current, $default) {
            if ($current instanceof TerminalInterface) {
                return 0;
            }

            return $current === $default ? 0 : 1;
        });

        if (!$diff && null !== $this->parent) {
            return $this->parent->getMenuStyle();
        }
        
        return new MenuStyle(...array_values($this->style));
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
            throw new RuntimeException('No parent builder to return to');
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
            throw new RuntimeException(sprintf('Menu: "%s" cannot be retrieved until menu has been built', $id));
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

            return new MenuMenuItem($item, $this->subMenus[$item], $menuBuilder->isMenuDisabled());
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
            $this->getMenuStyle()
        );
        
        foreach ($this->subMenus as $subMenu) {
            $subMenu->setParent($menu);
        }

        return $menu;
    }
}
