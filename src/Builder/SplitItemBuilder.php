<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Builder;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\CheckboxItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\RadioItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\Style\ItemStyle;
use function \PhpSchool\CliMenu\Util\each;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class SplitItemBuilder
{
    /**
     * @var CliMenu
     */
    private $menu;

    /**
     * @var SplitItem
     */
    private $splitItem;

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

    public function __construct(CliMenu $menu)
    {
        $this->menu = $menu;
        $this->splitItem = new SplitItem();
    }

    public function addItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $this->splitItem->addItem(new SelectableItem($text, $itemCallable, $showItemExtra, $disabled));

        return $this;
    }

    public function addCheckboxItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $this->splitItem->addItem(new CheckboxItem($text, $itemCallable, $showItemExtra, $disabled));

        return $this;
    }

    public function addRadioItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $this->splitItem->addItem(new RadioItem($text, $itemCallable, $showItemExtra, $disabled));

        return $this;
    }

    public function addStaticItem(string $text) : self
    {
        $this->splitItem->addItem(new StaticItem($text));

        return $this;
    }

    public function addLineBreak(string $breakChar = ' ', int $lines = 1) : self
    {
        $this->splitItem->addItem(new LineBreakItem($breakChar, $lines));

        return $this;
    }

    public function addSubMenu(string $text, \Closure $callback) : self
    {
        $builder = CliMenuBuilder::newSubMenu($this->menu->getTerminal());

        if ($this->autoShortcuts) {
            $builder->enableAutoShortcuts($this->autoShortcutsRegex);
        }

        each($this->extraItemStyles, function (int $i, array $extraItemStyle) use ($builder) {
            $builder->registerItemStyle($extraItemStyle['class'], $extraItemStyle['style']);
        });

        $callback($builder);

        $menu = $builder->build();
        $menu->setParent($this->menu);

        $this->splitItem->addItem(new MenuMenuItem(
            $text,
            $menu,
            $builder->isMenuDisabled()
        ));

        return $this;
    }

    public function addMenuItem(MenuItemInterface $item) : self
    {
        $this->splitItem->addItem($item);

        return $this;
    }

    public function setGutter(int $gutter) : self
    {
        $this->splitItem->setGutter($gutter);

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

    public function registerItemStyle(string $itemClass, ItemStyle $itemStyle) : self
    {
        $this->extraItemStyles[] = ['class' => $itemClass, 'style' => $itemStyle];

        return $this;
    }

    public function build() : SplitItem
    {
        return $this->splitItem;
    }
}
