<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Util\StringUtil;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class SplitItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $items;

    /**
     * @var int
     * -1 means no item selected
     */
    private $selectedItemIndex = -1;

    /**
     * @var int
     */
    private $margin = 2;


    public function __construct(string $text, array $items)
    {
        $this->text     = $text;
        $this->items    = $items;
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $numberOfItems = count($this->items);

        if (!$selected) {
            $this->selectedItemIndex = -1;
        } else {
            if ($this->selectedItemIndex === -1) {
                $this->selectedItemIndex = 0;
            }
        }

        $length = $style->getDisplaysExtra()
            ? floor(($style->getContentWidth() - mb_strlen($style->getItemExtra()) + 2) / $numberOfItems) - $this->margin
            : floor($style->getContentWidth() / $numberOfItems) - $this->margin;
        $missingLength = $style->getContentWidth() % $numberOfItems;

        $lines = 0;
        $cells = [];
        foreach ($this->items as $index => $item) {
            $marker = sprintf("%s ", $style->getMarker($index === $this->selectedItemIndex));
            $content = StringUtil::wordwrap(
                sprintf('%s%s', $marker, $item->getText()),
                $length
            );
            $cell = array_map(function ($row) use ($index, $length, $style) {
                $row = $row . str_repeat(' ', $length - strlen($row));
                if ($index === $this->selectedItemIndex) {
                    $row = $style->getSelectedSetCode() . $row . $style->getSelectedUnsetCode();
                } else {
                    $row = $style->getUnselectedSetCode() . $row . $style->getUnselectedUnsetCode();
                }
                $row .= $style->getUnselectedSetCode() . str_repeat(' ', $this->margin);
                return $row;
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

    /**
     *
     */
    public function setSelectedItemIndex(int $index) : void
    {
        $this->selectedItemIndex = $index;
    }

    /**
     *
     */
    public function getSelectedItemIndex() : int
    {
        if ($this->selectedItemIndex === -1) {
            return 0;
        }
        return $this->selectedItemIndex;
    }
    
    /**
     *
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * Can the item be selected
     */
    public function canSelect() : bool
    {
        return true;
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
        return $this->text;
    }
}
