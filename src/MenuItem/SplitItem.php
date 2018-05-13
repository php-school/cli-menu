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
     * @var int|null
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

    /**
     * @var array
     */
    private static $blacklistedItems = [
        \PhpSchool\CliMenu\MenuItem\AsciiArtItem::class,
        \PhpSchool\CliMenu\MenuItem\LineBreakItem::class,
        \PhpSchool\CliMenu\MenuItem\SplitItem::class,
    ];

    public function __construct(array $items = [])
    {
        $this->items = $items;

        $this->setDefaultSelectedItem();
    }

    public function addMenuItem(MenuItemInterface $item) : self
    {
        foreach (self::$blacklistedItems as $bl) {
            if ($item instanceof $bl) {
                throw new \InvalidArgumentException("Cannot add a $bl to a SplitItem");
            }
        }
        $this->items[] = $item;
        $this->setDefaultSelectedItem();
        return $this;
    }

    public function addMenuItems(array $items) : self
    {
        foreach ($items as $item) {
            $this->addMenuItem($item);
        }
        return $this;
    }
    
    public function setItems(array $items) : self
    {
        $this->items = [];
        $this->addMenuItems($items);
        return $this;
    }

    /**
     * Select default item
     */
    private function setDefaultSelectedItem() : void
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
            ? floor(($style->getContentWidth() - mb_strlen($style->getItemExtra()) + 2) / $numberOfItems)
            : floor($style->getContentWidth() / $numberOfItems);
            
        $length -= $this->margin;
        
        $missingLength = $style->getContentWidth() % $numberOfItems;

        $cells = array_map(function ($index, $item) use ($selected, $length, $style) {
            $isSelected = $selected && $index === $this->selectedItemIndex;
            $marker = $item->canSelect()
                ? sprintf("%s ", $style->getMarker($isSelected))
                : sprintf("%s ", str_repeat(' ', mb_strlen($style->getMarker(false))));
            $content = StringUtil::wordwrap(
                sprintf('%s%s', $marker, $item->getText()),
                $length
            );
            return array_map(function ($row) use ($length, $style, $isSelected) {
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
        }, array_keys($this->items), $this->items);

        $lines = max(array_map('count', $cells));

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

    public function getSelectedItemIndex() : ?int
    {
        return $this->selectedItemIndex;
    }

    public function getSelectedItem() : MenuItemInterface
    {
        return $this->selectedItemIndex !== null
            ? $this->items[$this->selectedItemIndex]
            : $this;
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
