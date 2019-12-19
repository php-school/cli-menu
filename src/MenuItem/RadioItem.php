<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Style;

class RadioItem implements MenuItemInterface, ToggableItemInterface, RadioInterface
{
    use ToggableTrait;

    public function __construct(
        string $text,
        callable $selectAction,
        bool $showItemExtra = false,
        bool $disabled = false
    ) {
        $this->text          = $text;
        $this->selectAction  = $selectAction;
        $this->showItemExtra = $showItemExtra;
        $this->disabled      = $disabled;

        $this->style = new Style\RadioStyle();
    }

    public function getStyle() : Style\ItemStyleInterface
    {
        return $this->style;
    }

    /**
     * @param Style\RadioStyle|Style\ItemStyleInterface $style
     * @return $this
     */
    public function setStyle(Style\ItemStyleInterface $style) : self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Execute the items callable if required
     */
    public function getSelectAction() : ?callable
    {
        return function (CliMenu $cliMenu) {
            $parentItem = $cliMenu->getItemByIndex($cliMenu->getSelectedItemIndex());

            $siblings = $parentItem instanceof SplitItem
                ? $parentItem->getItems()
                : $cliMenu->getItems();

            $filtered = array_filter(
                $siblings,
                function (MenuItemInterface $item) {
                    return $item instanceof self;
                }
            );

            array_walk(
                $filtered,
                function (RadioItem $checkableItem) {
                    $checkableItem->setUnchecked();
                }
            );

            $this->setChecked();
            $cliMenu->redraw();

            return ($this->selectAction)($cliMenu);
        };
    }
}
