<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Util\StringUtil;
use PhpSchool\CliMenu\Style\RadioStyle;

class RadioItem implements MenuItemInterface, ToggableItemInterface
{
    use ToggableTrait;

    /**
     * @var RadioStyle;
     */
    private $style;

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

        $this->style = new RadioStyle();
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $marker = sprintf("%s", $this->style->getMarker($this->checked));

        $itemExtra = $this->style->getItemExtra();

        $length = $this->style->getDisplaysExtra()
            ? $style->getContentWidth() - (mb_strlen($itemExtra) + 2)
            : $style->getContentWidth();

        $rows = explode(
            "\n",
            StringUtil::wordwrap(
                sprintf('%s%s', $marker, $this->text),
                $length,
                sprintf("\n%s", str_repeat(' ', mb_strlen($marker)))
            )
        );

        return array_map(function ($row, $key) use ($style, $length, $itemExtra) {
            $text = $this->disabled ? $style->getDisabledItemText($row) : $row;

            if ($key === 0) {
                return $this->showItemExtra
                    ? sprintf('%s%s  %s', $text, str_repeat(' ', $length - mb_strlen($row)), $itemExtra)
                    : $text;
            }

            return $text;
        }, $rows, array_keys($rows));
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

    public function getStyle() : RadioStyle
    {
        return $this->style;
    }

    public function setStyle(RadioStyle $style) : self
    {
        $this->style = $style;

        return $this;
    }
}
