<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Style;

class CheckableItem implements MenuItemInterface, ToggableItemInterface, CheckableInterface
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

        $this->style = new Style\CheckableStyle();
    }

    public function getStyle() : Style\ItemStyleInterface
    {
        return $this->style;
    }

    /**
     * @param Style\CheckableStyle|Style\ItemStyleInterface $style
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
            $this->toggle();
            $cliMenu->redraw();

            return ($this->selectAction)($cliMenu);
        };
    }
}
