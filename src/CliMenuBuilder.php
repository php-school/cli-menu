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
use PhpSchool\CliMenu\Util\ColourUtil;
use Assert\Assertion;
use PhpSchool\Terminal\Terminal;
use RuntimeException;

/**
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
     * @var self[]
     */
    private $subMenuBuilders = [];

    /**
     * @var CliMenu[]
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
    private $style;

    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @var string
     */
    private $menuTitle = null;

    /**
     * @var bool
     */
    private $disableDefaultItems = false;

    /**
     * @var bool
     */
    private $disabled = false;

    public function __construct(CliMenuBuilder $parent = null)
    {
        $this->parent   = $parent;
        $this->terminal = $this->parent !== null
            ? $this->parent->getTerminal()
            : TerminalFactory::fromSystem();
        $this->style    = MenuStyle::getDefaultStyleValues();
    }

    public function setTitle(string $title) : self
    {
        $this->menuTitle = $title;

        return $this;
    }

    public function addMenuItem(MenuItemInterface $item) : self
    {
        $this->menuItems[] = $item;

        return $this;
    }

    public function addItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $this->addMenuItem(new SelectableItem($text, $itemCallable, $showItemExtra, $disabled));

        return $this;
    }

    public function addItems(array $items) : self
    {
        foreach ($items as $item) {
            $this->addItem(...$item);
        }
        
        return $this;
    }

    public function addStaticItem(string $text) : self
    {
        $this->addMenuItem(new StaticItem($text));

        return $this;
    }

    public function addLineBreak(string $breakChar = ' ', int $lines = 1) : self
    {
        $this->addMenuItem(new LineBreakItem($breakChar, $lines));

        return $this;
    }

    public function addAsciiArt(string $art, string $position = AsciiArtItem::POSITION_CENTER, string $alt = '') : self
    {
        $this->addMenuItem(new AsciiArtItem($art, $position, $alt));

        return $this;
    }

    /**
     * Add a submenu with a string identifier
     */
    public function addSubMenu(string $id, CliMenuBuilder $subMenuBuilder = null) : CliMenuBuilder
    {
        $this->menuItems[]  = $id;
        
        if (null === $subMenuBuilder) {
            $this->subMenuBuilders[$id] = new static($this);
            return $this->subMenuBuilders[$id];
        }
        
        $this->subMenuBuilders[$id] = $subMenuBuilder;
        return $this;
    }

    /**
     * Disable a submenu
     *
     * @throws \InvalidArgumentException
     */
    public function disableMenu() : self
    {
        if (!$this->parent) {
            throw new \InvalidArgumentException(
                'You can\'t disable the root menu'
            );
        }

        $this->disabled = true;

        return $this;
    }

    public function isMenuDisabled() : bool
    {
        return $this->disabled;
    }

    public function setGoBackButtonText(string $goBackButtonTest) : self
    {
        $this->goBackButtonText = $goBackButtonTest;
        
        return $this;
    }

    public function setExitButtonText(string $exitButtonText) : self
    {
        $this->exitButtonText = $exitButtonText;
        
        return $this;
    }

    public function setBackgroundColour($colour, string $fallback = null) : self
    {
        $this->style['bg'] = ColourUtil::validateColour(
            $this->terminal,
            $colour,
            $fallback
        );

        return $this;
    }

    public function setForegroundColour($colour, string $fallback = null) : self
    {
        $this->style['fg'] = ColourUtil::validateColour(
            $this->terminal,
            $colour,
            $fallback
        );

        return $this;
    }

    public function setWidth(int $width) : self
    {
        $this->style['width'] = $width;

        return $this;
    }

    public function setPadding(int $padding) : self
    {
        $this->style['padding'] = $padding;

        return $this;
    }

    public function setMargin(int $margin) : self
    {
        $this->style['margin'] = $margin;

        return $this;
    }

    public function setUnselectedMarker(string $marker) : self
    {
        $this->style['unselectedMarker'] = $marker;

        return $this;
    }

    public function setSelectedMarker(string $marker) : self
    {
        $this->style['selectedMarker'] = $marker;

        return $this;
    }

    public function setItemExtra(string $extra) : self
    {
        $this->style['itemExtra'] = $extra;

        return $this;
    }

    public function setTitleSeparator(string $separator) : self
    {
        $this->style['titleSeparator'] = $separator;

        return $this;
    }

    public function setTerminal(Terminal $terminal) : self
    {
        $this->terminal = $terminal;
        return $this;
    }

    public function getTerminal() : Terminal
    {
        return $this->terminal;
    }

    private function getDefaultItems() : array
    {
        $actions = [];
        if ($this->parent) {
            $actions[] = new SelectableItem($this->goBackButtonText, new GoBackAction);
        }
        
        $actions[] = new SelectableItem($this->exitButtonText, new ExitAction);
        return $actions;
    }

    public function disableDefaultItems() : self
    {
        $this->disableDefaultItems = true;

        return $this;
    }

    private function itemsHaveExtra(array $items) : bool
    {
        return !empty(array_filter($items, function (MenuItemInterface $item) {
            return $item->showsItemExtra();
        }));
    }

    /**
     * Recursively drop back to the parents menu style
     * when the current menu has a parent and has no changes
     */
    private function getMenuStyle() : MenuStyle
    {
        if (null === $this->parent) {
            return $this->buildStyle();
        }

        if ($this->style !== MenuStyle::getDefaultStyleValues()) {
            return $this->buildStyle();
        }

        return $this->parent->getMenuStyle();
    }

    private function buildStyle() : MenuStyle
    {
        return (new MenuStyle($this->terminal))
            ->setFg($this->style['fg'])
            ->setBg($this->style['bg'])
            ->setWidth($this->style['width'])
            ->setPadding($this->style['padding'])
            ->setMargin($this->style['margin'])
            ->setSelectedMarker($this->style['selectedMarker'])
            ->setUnselectedMarker($this->style['unselectedMarker'])
            ->setItemExtra($this->style['itemExtra'])
            ->setDisplaysExtra($this->style['displaysExtra'])
            ->setTitleSeparator($this->style['titleSeparator']);
    }

    /**
     * Return to parent builder
     *
     * @throws RuntimeException
     */
    public function end() : CliMenuBuilder
    {
        if (null === $this->parent) {
            throw new RuntimeException('No parent builder to return to');
        }

        return $this->parent;
    }

    /**
     * @throws RuntimeException
     */
    public function getSubMenu(string $id) : CliMenu
    {
        if (false === $this->isBuilt) {
            throw new RuntimeException(sprintf('Menu: "%s" cannot be retrieved until menu has been built', $id));
        }

        return $this->subMenus[$id];
    }

    private function buildSubMenus(array $items) : array
    {
        return array_map(function ($item) {
            if (!is_string($item)) {
                return $item;
            }

            $menuBuilder           = $this->subMenuBuilders[$item];
            $this->subMenus[$item] = $menuBuilder->build();

            return new MenuMenuItem($item, $this->subMenus[$item], $menuBuilder->isMenuDisabled());
        }, $items);
    }

    public function build() : CliMenu
    {
        $this->isBuilt = true;

        $mergedItems = $this->disableDefaultItems
            ? $this->menuItems
            : array_merge($this->menuItems, $this->getDefaultItems());

        $menuItems = $this->buildSubMenus($mergedItems);

        $this->style['displaysExtra'] = $this->itemsHaveExtra($menuItems);

        $menu = new CliMenu(
            $this->menuTitle,
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
