<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\ItemStyle;
use PhpSchool\CliMenu\Util\StringUtil;
use PhpSchool\CliMenu\Style\SelectableStyle;
use function PhpSchool\CliMenu\Util\mapWithKeys;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class SelectableItem implements MenuItemInterface
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
     * @var SelectableStyle
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

        $this->style = new SelectableStyle();
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
        return $this->selectAction;
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
     * @return SelectableStyle
     */
    public function getStyle() : ItemStyle
    {
        return $this->style;
    }

    public function setStyle(SelectableStyle $style) : void
    {
        $this->style = $style;
    }
}
