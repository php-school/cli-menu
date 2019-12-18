<?php

namespace PhpSchool\CliMenu\Builder;

use Closure;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\CheckableItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\RadioItem;
use PhpSchool\CliMenu\MenuItem\SelectableInterface;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\Style\CheckableStyle;
use PhpSchool\CliMenu\Style\RadioStyle;
use PhpSchool\CliMenu\Style\SelectableStyle;

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
     * @var CheckableStyle
     */
    private $checkableStyle;

    /**
     * @var RadioStyle
     */
    private $radioStyle;

    /**
     * @var SelectableStyle
     */
    private $selectableStyle;

    public function __construct(CliMenu $menu)
    {
        $this->menu = $menu;
        $this->splitItem = new SplitItem();

        $this->checkableStyle  = new CheckableStyle($menu->getTerminal());
        $this->radioStyle      = new RadioStyle($menu->getTerminal());
        $this->selectableStyle = new SelectableStyle($menu->getTerminal());
    }

    public function addItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $item = (new SelectableItem($text, $itemCallable, $showItemExtra, $disabled))
            ->setStyle($this->menu->getSelectableStyle());

        $this->splitItem->addItem($item);

        return $this;
    }

    public function addCheckableItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $item = (new CheckableItem($text, $itemCallable, $showItemExtra, $disabled))
            ->setStyle($this->menu->getCheckableStyle());

        $this->splitItem->addItem($item);

        return $this;
    }

    public function addRadioItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $item = (new RadioItem($text, $itemCallable, $showItemExtra, $disabled))
            ->setStyle($this->menu->getRadioStyle());

        $this->splitItem->addItem($item);

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

        $callback($builder);

        $menu = $this->createMenuClosure($builder);

        $this->splitItem->addItem(new MenuMenuItem(
            $text,
            $menu,
            $builder->isMenuDisabled()
        ));
        
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
    
    public function build() : SplitItem
    {
        return $this->splitItem;
    }

    public function setCheckableStyle(callable $itemCallable) : self
    {
        $this->menu->setCheckableStyle($itemCallable);

        return $this;
    }

    public function setRadioStyle(callable $itemCallable) : self
    {
        $this->menu->setRadioStyle($itemCallable);

        return $this;
    }

    public function setSelectableStyle(callable $itemCallable) : self
    {
        $this->menu->setSelectableStyle($itemCallable);

        return $this;
    }

    /**
     * Create the submenu as a closure which is then unpacked in MenuMenuItem::showSubMenu
     * This allows us to wait until all user-provided styles are parsed and apply them to nested items
     *
     * @param CliMenuBuilder $builder
     * @return Closure
     */
    protected function createMenuClosure(CliMenuBuilder $builder) : Closure
    {
        return function () use ($builder) {
            $menu = $builder->build();

            $menu->setParent($this->menu);

            // If user changed this style, persist to the menu so children CheckableItems may use it
            if ($this->menu->getCheckableStyle()->getIsCustom()) {
                $menu->setCheckableStyle(function (CheckableStyle $style) {
                    $style->fromArray($this->menu->getCheckableStyle()->toArray());
                });
            }

            // If user changed this style, persist to the menu so children RadioItems may use it
            if ($this->menu->getRadioStyle()->getIsCustom()) {
                $menu->setRadioStyle(function (RadioStyle $style) {
                    $style->fromArray($this->menu->getRadioStyle()->toArray());
                });
            }

            // If user changed this style, persist to the menu so children SelectableItems may use it
            if ($this->menu->getSelectableStyle()->getIsCustom()) {
                $menu->setSelectableStyle(function (SelectableStyle $style) {
                    $style->fromArray($this->menu->getSelectableStyle()->toArray());
                });
            }

            // This will be filled with user-provided items
            foreach ($menu->getItems() as $item) {
                if ($item instanceof SelectableInterface && !$item->getStyle()->getIsCustom()) {
                    $item->setStyle(clone $menu->getSelectableStyle());
                }
            }

            return $menu;
        };
    }
}
