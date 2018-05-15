<?php

namespace PhpSchool\CliMenu\Builder;

use PhpSchool\CliMenu\Action\ExitAction;
use PhpSchool\CliMenu\Action\GoBackAction;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Terminal\TerminalFactory;
use PhpSchool\Terminal\Terminal;
use RuntimeException;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 * @author Aydin Hassan <aydin@hotmail.com>
 */
class CliMenuBuilder implements Builder
{
    use BuilderUtils;

    /**
     * @var null|Builder
     */
    private $parent;
    
    /**
     * @var bool
     */
    private $isBuilt = false;

    /**
     * @var SplitItemBuilder[]
     */
    private $splitItemBuilders = [];

    /**
     * @var SplitItem[]
     */
    private $splitItems = [];

    /**
     * @var string
     */
    private $goBackButtonText = 'Go Back';

    /**
     * @var string
     */
    private $exitButtonText = 'Exit';

    /**
     * @var MenuStyle
     */
    private $style;

    /**
     * @var Terminal
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

    public function __construct(Terminal $terminal = null, Builder $parent = null)
    {
        $this->terminal = $terminal ?? TerminalFactory::fromSystem();
        $this->parent   = $parent;
        $this->style = new MenuStyle($this->terminal);
    }
    
    public static function newFromParent(Builder $parent) : self
    {
        return new self($parent->getTerminal(), $parent);
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

    public function addItems(array $items) : self
    {
        foreach ($items as $item) {
            $this->addItem(...$item);
        }

        return $this;
    }

    public function addAsciiArt(string $art, string $position = AsciiArtItem::POSITION_CENTER, string $alt = '') : self
    {
        $this->addMenuItem(new AsciiArtItem($art, $position, $alt));

        return $this;
    }

    /**
     * Add a split item
     */
    public function addSplitItem() : SplitItemBuilder
    {
        $this->menuItems[] = [
            'type' => 'splititem-placeholder',
            'id'   => $id = uniqid('', true),
        ];
                
        $this->splitItemBuilders[$id] = new SplitItemBuilder($this);
        return $this->splitItemBuilders[$id];
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

    public function setBackgroundColour(string $colour, string $fallback = null) : self
    {
        $this->style->setBg($colour, $fallback);

        return $this;
    }

    public function setForegroundColour(string $colour, string $fallback = null) : self
    {
        $this->style->setFg($colour, $fallback);

        return $this;
    }

    public function setWidth(int $width) : self
    {
        $this->style->setWidth($width);

        return $this;
    }

    public function setPadding(int $topBottom, int $leftRight = null) : self
    {
        $this->style->setPadding($topBottom, $leftRight);

        return $this;
    }

    public function setPaddingTopBottom(int $topBottom) : self
    {
        $this->style->setPaddingTopBottom($topBottom);

        return $this;
    }

    public function setPaddingLeftRight(int $leftRight) : self
    {
        $this->style->setPaddingLeftRight($leftRight);

        return $this;
    }

    public function setMarginAuto() : self
    {
        $this->style->setMarginAuto();

        return $this;
    }

    public function setMargin(int $margin) : self
    {
        $this->style->setMargin($margin);

        return $this;
    }

    public function setUnselectedMarker(string $marker) : self
    {
        $this->style->setUnselectedMarker($marker);

        return $this;
    }

    public function setSelectedMarker(string $marker) : self
    {
        $this->style->setSelectedMarker($marker);

        return $this;
    }

    public function setItemExtra(string $extra) : self
    {
        $this->style->setItemExtra($extra);

        return $this;
    }

    public function setTitleSeparator(string $separator) : self
    {
        $this->style->setTitleSeparator($separator);

        return $this;
    }

    public function setBorder(int $top, $right = null, $bottom = null, $left = null, string $colour = null) : self
    {
        $this->style->setBorder($top, $right, $bottom, $left, $colour);

        return $this;
    }

    public function setBorderTopWidth(int $width) : self
    {
        $this->style->setBorderTopWidth($width);
        
        return $this;
    }

    public function setBorderRightWidth(int $width) : self
    {
        $this->style->setBorderRightWidth($width);

        return $this;
    }

    public function setBorderBottomWidth(int $width) : self
    {
        $this->style->setBorderBottomWidth($width);

        return $this;
    }

    public function setBorderLeftWidth(int $width) : self
    {
        $this->style->setBorderLeftWidth($width);

        return $this;
    }

    public function setBorderColour(string $colour, $fallback = null) : self
    {
        $this->style->setBorderColour($colour, $fallback);

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
    public function getMenuStyle() : MenuStyle
    {
        if (null === $this->parent) {
            return $this->style;
        }

        if ($this->style->hasChangedFromDefaults()) {
            return $this->style;
        }

        return $this->parent->getMenuStyle();
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
    
    private function buildSplitItems(array $items) : array
    {
        return array_map(function ($item) {
            if (!is_array($item) || $item['type'] !== 'splititem-placeholder') {
                return $item;
            }

            $splitItemBuilder        = $this->splitItemBuilders[$item['id']];
            $this->splitItems[$item['id']] = $splitItemBuilder->build();

            return $this->splitItems[$item['id']];
        }, $items);
    }

    public function build() : CliMenu
    {
        $this->isBuilt = true;

        $mergedItems = $this->disableDefaultItems
            ? $this->menuItems
            : array_merge($this->menuItems, $this->getDefaultItems());

        
        $menuItems = $this->buildSplitItems($mergedItems);
        $menuItems = $this->buildSubMenus($menuItems);

        $this->style->setDisplaysExtra($this->itemsHaveExtra($menuItems));

        $menu = new CliMenu(
            $this->menuTitle,
            $menuItems,
            $this->terminal,
            $this->getMenuStyle()
        );

        foreach ($this->subMenus as $subMenu) {
            $subMenu->setParent($menu);
        }
        
        foreach ($this->splitItemBuilders as $splitItemBuilder) {
            $splitItemBuilder->setSubMenuParents($menu);
        }

        return $menu;
    }

    /**
     * Return to parent builder
     *
     * @return CliMenuBuilder|SplitItemBuilder
     */
    public function end() : ?Builder
    {
        if (null === $this->parent) {
            throw new RuntimeException('No parent builder to return to');
        }

        return $this->parent;
    }
}
