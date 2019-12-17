<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Util\StringUtil;

class CheckableItem implements MenuItemInterface
{
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

    /**
     * @var bool
     */
    private $checked = false;

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
     * The output text for the item
     *
     * @param MenuStyle $style
     * @param bool $selected Currently unused in this class
     * @return array
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $marker = sprintf("%s", $this->checked ? $style->getCheckedMarker() : $style->getUncheckedMarker());

        $length = $style->getDisplaysExtra()
            ? $style->getContentWidth() - (mb_strlen($style->getItemExtra()) + 2)
            : $style->getContentWidth();

        $rows = explode(
            "\n",
            StringUtil::wordwrap(
                sprintf('%s%s', $marker, $this->text),
                $length,
                sprintf("\n%s", str_repeat(' ', mb_strlen($marker)))
            )
        );

        return array_map(function ($row, $key) use ($style, $length) {
            $text = $this->disabled ? $style->getDisabledItemText($row) : $row;

            if ($key === 0) {
                return $this->showItemExtra
                    ? sprintf('%s%s  %s', $text, str_repeat(' ', $length - mb_strlen($row)), $style->getItemExtra())
                    : $text;
            }

            return $text;
        }, $rows, array_keys($rows));
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

    /**
     * Toggles checked state
     */
    public function toggle()
    {
        $this->checked = !$this->checked;
    }

    /**
     * Sets checked state to true
     */
    public function setChecked()
    {
        $this->checked = true;
    }

    /**
     * Sets checked state to false
     */
    public function setUnchecked()
    {
        $this->checked = false;
    }

    public function getChecked(): bool
    {
        return $this->checked;
    }
}
