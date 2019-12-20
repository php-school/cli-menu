<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
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
    private $items = [];

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
    private $gutter = 2;

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
        $this->addItems($items);
        $this->setDefaultSelectedItem();
    }

    public function setGutter(int $gutter) : void
    {
        Assertion::greaterOrEqualThan($gutter, 0);
        $this->gutter = $gutter;
    }

    public function getGutter() : int
    {
        return $this->gutter;
    }

    public function addItem(MenuItemInterface $item) : self
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

    public function addItems(array $items) : self
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
            
        return $this;
    }

    public function setItems(array $items) : self
    {
        $this->items = [];
        $this->addItems($items);
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

        if ($numberOfItems === 0) {
            throw new \RuntimeException(sprintf('There should be at least one item added to: %s', __CLASS__));
        }
        
        if (!$selected) {
            $this->setDefaultSelectedItem();
        }

        $length = $style->getDisplaysExtra()
            ? floor($style->getContentWidth() / $numberOfItems) - (mb_strlen($style->getItemExtra()) + 2)
            : floor($style->getContentWidth() / $numberOfItems);
        
        $length -= $this->gutter;
        $length = (int) $length;
        
        $missingLength = $style->getContentWidth() % $numberOfItems;
        
        return $this->buildRows(
            array_map(function ($index, $item) use ($selected, $length, $style) {
                $isSelected = $selected && $index === $this->selectedItemIndex;

                if ($item instanceof CheckboxItem || $item instanceof RadioItem) {
                    $markerType    = $item->getStyle()->getMarker($item->getChecked());
                    $displaysExtra = $item->getStyle()->getDisplaysExtra();
                    $itemExtraVal  = $item->getStyle()->getItemExtra();
                } else {
                    $markerType    = $style->getMarker($isSelected);
                    $displaysExtra = $style->getDisplaysExtra();
                    $itemExtraVal  = $style->getItemExtra();
                }

                $marker = $item->canSelect()
                    ? sprintf('%s', $markerType)
                    : '';

                $itemExtra = '';
                if ($displaysExtra) {
                    $itemExtra = $item->showsItemExtra()
                        ? sprintf('  %s', $itemExtraVal)
                        : sprintf('  %s', str_repeat(' ', mb_strlen($itemExtraVal)));
                }

                return $this->buildCell(
                    explode(
                        "\n",
                        StringUtil::wordwrap(
                            sprintf('%s%s', $marker, $item->getText()),
                            $length,
                            sprintf("\n%s", str_repeat(' ', mb_strlen($marker)))
                        )
                    ),
                    $length,
                    $style,
                    $isSelected,
                    $itemExtra
                );
            }, array_keys($this->items), $this->items),
            $style,
            $missingLength,
            $length
        );
    }

    private function buildRows(array $cells, MenuStyle $style, int $missingLength, int $length) : array
    {
        $extraPadLength = $style->getDisplaysExtra() ? 2 + mb_strlen($style->getItemExtra()) : 0;
        
        return array_map(
            function ($i) use ($cells, $length, $missingLength, $extraPadLength) {
                return $this->buildRow($cells, $i, $length, $missingLength, $extraPadLength);
            },
            range(0, max(array_map('count', $cells)) - 1)
        );
    }

    private function buildRow(array $cells, int $index, int $length, int $missingLength, int $extraPadLength) : string
    {
        return sprintf(
            '%s%s',
            implode(
                '',
                array_map(
                    function ($cell) use ($index, $length, $extraPadLength) {
                        return $cell[$index] ?? str_repeat(' ', $length + $this->gutter + $extraPadLength);
                    },
                    $cells
                )
            ),
            str_repeat(' ', $missingLength)
        );
    }

    private function buildCell(
        array $content,
        int $length,
        MenuStyle $style,
        bool $isSelected,
        string $itemExtra
    ) : array {
        return array_map(function ($row, $index) use ($length, $style, $isSelected, $itemExtra) {
            $invertedColoursSetCode = $isSelected
                ? $style->getInvertedColoursSetCode()
                : '';
            $invertedColoursUnsetCode = $isSelected
                ? $style->getInvertedColoursUnsetCode()
                : '';

            return sprintf(
                '%s%s%s%s%s%s',
                $invertedColoursSetCode,
                $row,
                str_repeat(' ', $length - mb_strlen($row)),
                $index === 0 ? $itemExtra : str_repeat(' ', mb_strlen($itemExtra)),
                $invertedColoursUnsetCode,
                str_repeat(' ', $this->gutter)
            );
        }, $content, array_keys($content));
    }

    /**
     * Is there an item with this index and can it be
     * selected?
     */
    public function canSelectIndex(int $index) : bool
    {
        return isset($this->items[$index]) && $this->items[$index]->canSelect();
    }

    /**
     * Set the item index which should be selected. If the item does
     * not exist then throw an exception.
     */
    public function setSelectedItemIndex(int $index) : void
    {
        if (!isset($this->items[$index])) {
            throw new \InvalidArgumentException(sprintf('Index: "%s" does not exist', $index));
        }
        
        $this->selectedItemIndex = $index;
    }

    /**
     * Get the currently select item index.
     * May be null in case of no selectable item.
     */
    public function getSelectedItemIndex() : ?int
    {
        return $this->selectedItemIndex;
    }

    /**
     * Get the currently selected item - if no items are selectable
     * then throw an exception.
     */
    public function getSelectedItem() : MenuItemInterface
    {
        if (null === $this->selectedItemIndex) {
            throw new \RuntimeException('No item is selected');
        }
        
        return $this->items[$this->selectedItemIndex];
    }

    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * Can the item be selected
     * In this case, it indicates if at least 1 item inside the SplitItem can be selected
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
     * Nothing to return with SplitItem
     */
    public function getText() : string
    {
        throw new \BadMethodCallException(sprintf('Not supported on: %s', __CLASS__));
    }
}
