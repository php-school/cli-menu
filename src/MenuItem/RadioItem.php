<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\ItemStyle;
use PhpSchool\CliMenu\Style\RadioStyle;

class RadioItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var callable
     */
    private $selectAction;

    /**
     * @var bool
     */
    private $showItemExtra;

    /**
     * @var bool
     */
    private $disabled;

    /**
     * The current checkbox state
     *
     * @var bool
     */
    private $checked = false;

    /**
     * @var RadioStyle
     */
    private $style;

    public function __construct(
        string $text,
        callable $selectAction,
        bool $showItemExtra = false,
        bool $disabled = false
    ) {
        $this->text = $text;
        $this->selectAction = $selectAction;
        $this->showItemExtra = $showItemExtra;
        $this->disabled = $disabled;

        $this->style = new RadioStyle();
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        return (new SelectableItemRenderer())->render($style, $this, $selected, $this->disabled);
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
                function (RadioItem $item) {
                    $item->setUnchecked();
                }
            );

            $this->setChecked();
            $cliMenu->redraw();

            return ($this->selectAction)($cliMenu);
        };
    }

    /**
     * Can the item be selected
     */
    public function canSelect() : bool
    {
        return !$this->disabled;
    }

    /**
     * Whether or not we are showing item extra
     */
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

    /**
     * Whether or not the item is checked
     */
    public function getChecked() : bool
    {
        return $this->checked;
    }

    /**
     * Sets checked state to true
     */
    public function setChecked() : void
    {
        $this->checked = true;
    }

    /**
     * Sets checked state to false
     */
    public function setUnchecked() : void
    {
        $this->checked = false;
    }

    /**
     * Toggles checked state
     */
    public function toggle() : void
    {
        $this->checked = !$this->checked;
    }

    /**
     * @return RadioStyle
     */
    public function getStyle() : ItemStyle
    {
        return $this->style;
    }

    public function setStyle(RadioStyle $style) : void
    {
        $this->style = $style;
    }
}
