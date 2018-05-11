<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Util\StringUtil;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class SplitItem implements MenuItemInterface
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var CliMenuBuilder
     */
    private $parentBuilder;

    /**
     * @var int
     */
    private $selectedItemIndex;

    /**
     * @var bool
     */
    private $canBeSelected = true;

    /**
     * @var int
     */
    private $margin = 2;


    public function __construct(CliMenuBuilder $builder, array $items = [])
    {
        $this->parentBuilder = $builder;
        $this->items         = $items;

        $this->setDefaultSelectedItem();
    }

    /**
     * Select default item
     */
    private function setDefaultSelectedItem()
    {
        foreach ($this->items as $index => $item) {
            if ($item->canSelect()) {
                $this->canBeSelected = true;
                $this->selectedItemIndex = $index;
                return;
            }
        }

        $this->canBeSelected = false;
        $this->selectedItemIndex = null;
    }

    public function addItem(
        string $text,
        callable $itemCallable,
        bool $showItemExtra = false,
        bool $disabled = false
    ) : self {
        $this->items[] = new SelectableItem($text, $itemCallable, $showItemExtra, $disabled);
        $this->setDefaultSelectedItem();

        return $this;
    }

    public function addStaticItem(string $text) : self
    {
        $this->items[] = new StaticItem($text);
        $this->setDefaultSelectedItem();

        return $this;
    }

    public function end() : CliMenuBuilder
    {
        return $this->parentBuilder;
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $numberOfItems = count($this->items);

        if (!$selected) {
            $this->setDefaultSelectedItem();
        }

        $length = $style->getDisplaysExtra()
            ? floor(($style->getContentWidth() - mb_strlen($style->getItemExtra()) + 2) / $numberOfItems) - $this->margin
            : floor($style->getContentWidth() / $numberOfItems) - $this->margin;
        $missingLength = $style->getContentWidth() % $numberOfItems;

        $lines = 0;
        $cells = [];
        foreach ($this->items as $index => $item) {
            $isSelected = $selected && $index === $this->selectedItemIndex;
            $marker = sprintf("%s ", $style->getMarker($isSelected));
            $content = StringUtil::wordwrap(
                sprintf('%s%s', $marker, $item->getText()),
                $length
            );
            $cell = array_map(function ($row) use ($index, $length, $style, $isSelected) {
                $invertedColoursSetCode = $isSelected
                    ? $style->getInvertedColoursSetCode()
                    : '';
                $invertedColoursUnsetCode = $isSelected
                    ? $style->getInvertedColoursUnsetCode()
                    : '';

                return sprintf(
                    "%s%s%s%s%s",
                    $invertedColoursSetCode,
                    $row,
                    str_repeat(' ', $length - mb_strlen($row)),
                    $invertedColoursUnsetCode,
                    str_repeat(' ', $this->margin)
                );
            }, explode("\n", $content));
            $lineCount = count($cell);
            if ($lineCount > $lines) {
                $lines = $lineCount;
            }
            $cells[] = $cell;
        }

        $rows = [];
        for ($i = 0; $i < $lines; $i++) {
            $row = "";
            if ($i > 0) {
                $row .= str_repeat(' ', 2);
            }
            foreach ($cells as $cell) {
                if (isset($cell[$i])) {
                    $row .= $cell[$i];
                } else {
                    $row .= str_repeat(' ', $length);
                }
            }
            if ($missingLength) {
                $row .= str_repeat(' ', $missingLength);
            }
            $rows[] = $row;
        }

        return $rows;
    }

    public function setSelectedItemIndex(int $index) : void
    {
        $this->selectedItemIndex = $index;
    }

    public function getSelectedItemIndex() : int
    {
        if ($this->selectedItemIndex === null) {
            return 0;
        }
        return $this->selectedItemIndex;
    }

    public function getSelectedItem() : MenuItem
    {
        return $this->items[$this->selectedItemIndex];
    }

    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * Can the item be selected
     * Not really in this case but that's the trick
     */
    public function canSelect() : bool
    {
        return $this->canBeSelected;
    }

    /**
     * Execute the items callable if required
     */
    public function getSelectAction() : ?callable
    {
        return null;
    }

    /**
     * Whether or not the menu item is showing the menustyle extra value
     */
    public function showsItemExtra() : bool
    {
        return false;
    }

    /**
     * Enable showing item extra
     */
    public function showItemExtra() : void
    {
        //noop
    }

    /**
     * Disable showing item extra
     */
    public function hideItemExtra() : void
    {
        //noop
    }

    /**
     * Return the raw string of text
     */
    public function getText() : string
    {
        $text = [];
        foreach ($this->items as $item) {
            $text[] = $item->getText();
        }
        return explode(' - ', $text);
    }
}
