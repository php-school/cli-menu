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
        $this->addItems($items);
        $this->setDefaultSelectedItem();
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

        if (!$selected) {
            $this->setDefaultSelectedItem();
        }

        $length = $style->getDisplaysExtra()
            ? floor(($style->getContentWidth() - mb_strlen($style->getItemExtra()) + 2) / $numberOfItems)
            : floor($style->getContentWidth() / $numberOfItems);

        $length -= $this->margin;

        $missingLength = $style->getContentWidth() % $numberOfItems;

        if ($missingLength < 1) {
            $missingLength = 0;
        }
        
        return $this->buildRows(
            array_map(function ($index, $item) use ($selected, $length, $style) {
                $isSelected = $selected && $index === $this->selectedItemIndex;
                $marker = $item->canSelect()
                    ? sprintf('%s ', $style->getMarker($isSelected))
                    : '';

                return $this->buildCell(
                    explode("\n", StringUtil::wordwrap(sprintf('%s%s', $marker, $item->getText()), $length)),
                    $length,
                    $style,
                    $isSelected
                );
            }, array_keys($this->items), $this->items),
            $missingLength,
            $length
        );
    }

    private function buildRows(array $cells, int $missingLength, int $length) : array
    {
        return array_map(
            function ($i) use ($cells, $length, $missingLength) {
                return $this->buildRow($cells, $i, $length, $missingLength);
            },
            range(0, max(array_map('count', $cells)) - 1)
        );
    }

    private function buildRow(array $cells, int $index, int $length, int $missingLength) : string
    {
        return sprintf(
            '%s%s',
            implode(
                '',
                array_map(
                    function ($cell) use ($index, $length) {
                        return $cell[$index] ?? str_repeat(' ', $length);
                    },
                    $cells
                )
            ),
            str_repeat(' ', $missingLength)
        );
    }

    private function buildCell(array $content, int $length, MenuStyle $style, bool $isSelected) : array
    {
        return array_map(function ($row) use ($length, $style, $isSelected) {
            $invertedColoursSetCode = $isSelected
                ? $style->getInvertedColoursSetCode()
                : '';
            $invertedColoursUnsetCode = $isSelected
                ? $style->getInvertedColoursUnsetCode()
                : '';

            return sprintf(
                '%s%s%s%s%s',
                $invertedColoursSetCode,
                $row,
                str_repeat(' ', $length - mb_strlen($row)),
                $invertedColoursUnsetCode,
                str_repeat(' ', $this->margin)
            );
        }, $content);
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
        throw new \BadMethodCallException(sprintf('Not supported on: %s', SplitItem::class));
    }
}
