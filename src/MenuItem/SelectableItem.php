<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\Style;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class SelectableItem implements MenuItemInterface, SelectableInterface
{
    use SelectableTrait;

    /**
     * @var callable
     */
    private $selectAction;

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

        $this->style = new Style\SelectableStyle();
    }

    /**
     * Execute the items callable if required
     */
    public function getSelectAction() : ?callable
    {
        return $this->selectAction;
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
}
