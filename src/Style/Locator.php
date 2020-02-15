<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Style;

use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\CheckboxItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\RadioItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\Style\Exception\InvalidStyle;
use function PhpSchool\CliMenu\Util\mapWithKeys;

class Locator
{
    /**
     * @var array
     */
    private $itemStyleMap = [
        /** Static non selectable items */
        StaticItem::class => DefaultStyle::class,
        AsciiArtItem::class => DefaultStyle::class,
        LineBreakItem::class => DefaultStyle::class,
        /** Split item */
        SplitItem::class => DefaultStyle::class,
        /** Normal selectable items */
        SelectableItem::class => SelectableStyle::class,
        MenuMenuItem::class => SelectableStyle::class,
        /** Toggle items */
        CheckboxItem::class => CheckboxStyle::class,
        RadioItem::class => RadioStyle::class,
    ];

    /**
     * @var array
     */
    private $styles;

    public function __construct()
    {
        $this->styles = [
            DefaultStyle::class => new DefaultStyle(),
            SelectableStyle::class => new SelectableStyle(),
            CheckboxStyle::class => new CheckboxStyle(),
            RadioStyle::class => new RadioStyle()
        ];
    }

    /**
     * For each of our unmodified item styles, we replace ours with the versions
     * from the given style locator.
     *
     * @param Locator $other
     */
    public function importFrom(self $other) : void
    {
        $this->styles = mapWithKeys(
            $this->styles,
            function ($styleClass, ItemStyle $instance) use ($other) {
                return $instance instanceof Customisable && !$instance->hasChangedFromDefaults()
                    ? $other->getStyle($styleClass)
                    : $instance;
            }
        );
    }

    public function getStyle(string $styleClass) : ItemStyle
    {
        if (!isset($this->styles[$styleClass])) {
            throw InvalidStyle::unregisteredStyle($styleClass);
        }

        return $this->styles[$styleClass];
    }

    /**
     * TODO: Don't accept $styleClass and figure this ourselves?
     *
     * @param ItemStyle $itemStyle
     * @param string $styleClass
     */
    public function setStyle(ItemStyle $itemStyle, string $styleClass) : void
    {
        if (!isset($this->styles[$styleClass])) {
            throw InvalidStyle::unregisteredStyle($styleClass);
        }

        if (!$itemStyle instanceof $styleClass) {
            //throw
        }

        $this->styles[$styleClass] = $itemStyle;
    }

    public function getStyleForMenuItem(MenuItemInterface $item) : ItemStyle
    {
        if (!isset($this->itemStyleMap[get_class($item)])) {
            //unregistered menu item
        }

        $styleClass = $this->itemStyleMap[get_class($item)];

        return $this->getStyle($styleClass);
    }
}
