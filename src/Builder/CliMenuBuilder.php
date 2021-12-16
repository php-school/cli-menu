<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Builder;

use PhpSchool\CliMenu\Action\ExitAction;
use PhpSchool\CliMenu\Action\GoBackAction;
use PhpSchool\CliMenu\Exception\InvalidShortcutException;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\CheckboxItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\RadioItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\CheckboxStyle;
use PhpSchool\CliMenu\Style\DefaultStyle;
use PhpSchool\CliMenu\Style\ItemStyle;
use PhpSchool\CliMenu\Style\RadioStyle;
use PhpSchool\CliMenu\Style\SelectableStyle;
use PhpSchool\CliMenu\Terminal\TerminalFactory;
use PhpSchool\Terminal\Terminal;
use function PhpSchool\CliMenu\Util\each;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 * @author Aydin Hassan <aydin@hotmail.com>
 */
class CliMenuBuilder
{
    /**
     * @var CliMenu
     */
    private $menu;

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
     * @var bool
     */
    private $disableDefaultItems = false;

    /**
     * @var bool
     */
    private $disabled = false;

    /**
     * Whether or not to auto create keyboard shortcuts for items
     * when they contain square brackets. Eg: [M]y item
     *
     * @var bool
     */
    private $autoShortcuts = false;

    /**
     * Regex to auto match for shortcuts defaults to looking
     * for a single character encased in square brackets
     *
     * @var string
     */
    private $autoShortcutsRegex = '/\[(.)\]/';

    /**
     * @var array
     */
    private $extraItemStyles = [];

    /**
     * @var bool
     */
    private $subMenu = false;

    public function __construct(Terminal $terminal = null)
    {
        $this->terminal = $terminal ?? TerminalFactory::fromSystem();
        $this->style    = new MenuStyle($this->terminal);
        $this->menu     = new CliMenu(null, [], $this->terminal, $this->style);
    }

    public static function newSubMenu(Terminal $terminal) : self
    {
        $instance = new self($terminal);
        $instance->subMenu = true;

        return $instance;
    }

    public function setTitle(string $title) : self
    {
        $this->menu->setTitle($title);

        return $this;
    }

    public function addMenuItem(MenuItemInterface $item) : self
    {
        $this->menu->addItem($item);

        $this->processItemShortcut($item);

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

    public function addCheckboxItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $this->addMenuItem(new CheckboxItem($text, $itemCallable, $showItemExtra, $disabled));

        return $this;
    }

    public function addCheckboxItems(array $items): self
    {
        foreach ($items as $item) {
            $this->addCheckboxItem(...$item);
        }

        return $this;
    }

    public function addRadioItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $this->addMenuItem(new RadioItem($text, $itemCallable, $showItemExtra, $disabled));

        return $this;
    }

    public function addRadioItems(array $items): self
    {
        foreach ($items as $item) {
            $this->addRadioItem(...$item);
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

    public function addSubMenu(string $text, \Closure $callback) : self
    {
        $builder = self::newSubMenu($this->terminal);

        if ($this->autoShortcuts) {
            $builder->enableAutoShortcuts($this->autoShortcutsRegex);
        }

        each($this->extraItemStyles, function (int $i, array $extraItemStyle) use ($builder) {
            $builder->registerItemStyle($extraItemStyle['class'], $extraItemStyle['style']);
        });

        $callback($builder);

        $menu = $builder->build();
        $menu->setParent($this->menu);

        $this->menu->addItem($item = new MenuMenuItem(
            $text,
            $menu,
            $builder->isMenuDisabled()
        ));

        $this->processItemShortcut($item);

        return $this;
    }

    public function addSubMenuFromBuilder(string $text, CliMenuBuilder $builder) : self
    {
        $menu = $builder->build();
        $menu->setParent($this->menu);

        $this->menu->addItem($item = new MenuMenuItem(
            $text,
            $menu,
            $builder->isMenuDisabled()
        ));

        $this->processItemShortcut($item);

        return $this;
    }

    public function enableAutoShortcuts(string $regex = null) : self
    {
        $this->autoShortcuts = true;

        if (null !== $regex) {
            $this->autoShortcutsRegex = $regex;
        }

        return $this;
    }

    private function extractShortcut(string $title) : ?string
    {
        preg_match($this->autoShortcutsRegex, $title, $match);

        if (!isset($match[1])) {
            return null;
        }

        if (mb_strlen($match[1]) > 1) {
            throw InvalidShortcutException::fromShortcut($match[1]);
        }

        return isset($match[1]) ? strtolower($match[1]) : null;
    }

    private function processItemShortcut(MenuItemInterface $item) : void
    {
        $this->processIndividualShortcut($item, function (CliMenu $menu) use ($item) {
            $menu->executeAsSelected($item);
        });
    }

    private function processSplitItemShortcuts(SplitItem $splitItem) : void
    {
        foreach ($splitItem->getItems() as $item) {
            $this->processIndividualShortcut($item, function (CliMenu $menu) use ($splitItem, $item) {
                $current = $splitItem->getSelectedItemIndex();

                $splitItem->setSelectedItemIndex(
                    (int) array_search($item, $splitItem->getItems(), true)
                );

                $menu->executeAsSelected($splitItem);

                if ($current !== null) {
                    $splitItem->setSelectedItemIndex($current);
                }
            });
        }
    }

    private function processIndividualShortcut(MenuItemInterface $item, callable $callback) : void
    {
        if (!$this->autoShortcuts) {
            return;
        }

        if ($shortcut = $this->extractShortcut($item->getText())) {
            $this->menu->addCustomControlMapping(
                $shortcut,
                $callback
            );
        }
    }

    public function addSplitItem(\Closure $callback) : self
    {
        $builder = new SplitItemBuilder($this->menu);

        if ($this->autoShortcuts) {
            $builder->enableAutoShortcuts($this->autoShortcutsRegex);
        }

        each($this->extraItemStyles, function (int $i, array $extraItemStyle) use ($builder) {
            $builder->registerItemStyle($extraItemStyle['class'], $extraItemStyle['style']);
        });

        $callback($builder);

        $this->menu->addItem($splitItem = $builder->build());

        $this->processSplitItemShortcuts($splitItem);

        return $this;
    }

    /**
     * Disable a submenu
     *
     * @throws \InvalidArgumentException
     */
    public function disableMenu() : self
    {
        if (!$this->subMenu) {
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

    public function setItemExtra(string $extra) : self
    {
        $this->style->setItemExtra($extra);
        $this->getSelectableStyle()->setItemExtra($extra);

        // if we customise item extra, it means we most likely want to display it
        $this->displayExtra();

        return $this;
    }

    public function setTitleSeparator(string $separator) : self
    {
        $this->style->setTitleSeparator($separator);

        return $this;
    }

    /**
     * @param int|string|null $right
     * @param int|string|null $bottom
     * @param int|string|null $left
     */
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

    public function setBorderColour(string $colour, string $fallback = null) : self
    {
        $this->style->setBorderColour($colour, $fallback);

        return $this;
    }

    public function getStyle() : MenuStyle
    {
        return $this->style;
    }

    public function getTerminal() : Terminal
    {
        return $this->terminal;
    }

    private function getDefaultItems() : array
    {
        $actions = [];
        if ($this->subMenu) {
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

    public function displayExtra() : self
    {
        $this->style->setDisplaysExtra(true);
        $this->getSelectableStyle()->setDisplaysExtra(true);
        $this->getCheckboxStyle()->setDisplaysExtra(true);
        $this->getRadioStyle()->setDisplaysExtra(true);

        return $this;
    }

    private function itemsHaveExtra(array $items) : bool
    {
        return !empty(array_filter($items, function (MenuItemInterface $item) {
            return $item->showsItemExtra();
        }));
    }

    public function build() : CliMenu
    {
        if (!$this->disableDefaultItems) {
            $this->menu->addItems($this->getDefaultItems());
        }

        if (!$this->style->getDisplaysExtra()) {
            $this->style->setDisplaysExtra($this->itemsHaveExtra($this->menu->getItems()));
        }

        if (!$this->subMenu) {
            $this->menu->propagateStyles();
        }

        return $this->menu;
    }

    public function getDefaultStyle() : DefaultStyle
    {
        $style = $this->menu->getItemStyle(DefaultStyle::class);
        assert($style instanceof DefaultStyle);
        return $style;
    }

    public function setDefaultStyle(DefaultStyle $style) : self
    {
        $this->menu->setItemStyle($style, DefaultStyle::class);

        return $this;
    }

    public function modifyDefaultStyle(callable $itemCallable) : self
    {
        $itemCallable($this->getDefaultStyle());

        return $this;
    }

    public function getSelectableStyle() : SelectableStyle
    {
        $style = $this->menu->getItemStyle(SelectableStyle::class);
        assert($style instanceof SelectableStyle);
        return $style;
    }

    public function setSelectableStyle(SelectableStyle $style) : self
    {
        $this->menu->setItemStyle($style, SelectableStyle::class);

        return $this;
    }

    public function modifySelectableStyle(callable $itemCallable) : self
    {
        $itemCallable($this->getSelectableStyle());

        return $this;
    }

    public function getCheckboxStyle() : CheckboxStyle
    {
        $style = $this->menu->getItemStyle(CheckboxStyle::class);
        assert($style instanceof CheckboxStyle);
        return $style;
    }

    public function setCheckboxStyle(CheckboxStyle $style) : self
    {
        $this->menu->setItemStyle($style, CheckboxStyle::class);

        return $this;
    }

    public function modifyCheckboxStyle(callable $itemCallable) : self
    {
        $itemCallable($this->getCheckboxStyle());

        return $this;
    }

    public function getRadioStyle() : RadioStyle
    {
        $style = $this->menu->getItemStyle(RadioStyle::class);
        assert($style instanceof RadioStyle);
        return $style;
    }

    public function setRadioStyle(RadioStyle $style) : self
    {
        $this->menu->setItemStyle($style, RadioItem::class);

        return $this;
    }

    public function modifyRadioStyle(callable $itemCallable) : self
    {
        $itemCallable($this->getRadioStyle());

        return $this;
    }

    public function modifyStyle(string $styleClass, callable $itemCallable) : self
    {
        $itemCallable($this->menu->getItemStyle($styleClass));

        return $this;
    }

    public function registerItemStyle(string $itemClass, ItemStyle $itemStyle) : self
    {
        $this->menu->getStyleLocator()
            ->registerItemStyle($itemClass, $itemStyle);

        $this->extraItemStyles[] = ['class' => $itemClass, 'style' => $itemStyle];

        return $this;
    }
}
