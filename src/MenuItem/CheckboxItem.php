<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;

class CheckboxItem implements MenuItemInterface, ToggableItemInterface
{
    use ToggableTrait;

    /**
     * @var callable
     */
    private $selectAction;

    /**
     * @var string
     */
    private $text = '';

    /**
     * @var bool
     */
    private $showItemExtra = false;

    /**
     * @var bool
     */
    private $disabled = false;

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

    /**
     * Return the raw string of text
     */
    public function getText() : string
    {
        return $this->text;
    }

    /**
     * Set the raw string of text
     */
    public function setText(string $text) : void
    {
        $this->text = $text;
    }

    /**
     * Can the item be selected
     */
    public function canSelect() : bool
    {
        return !$this->disabled;
    }

    public function showsItemExtra() : bool
    {
        return $this->showItemExtra;
    }

    /**
     * Enable showing item extra
     */
    public function showItemExtra() : void
    {
        $this->showItemExtra = true;
    }

    /**
     * Disable showing item extra
     */
    public function hideItemExtra() : void
    {
        $this->showItemExtra = false;
    }
}
