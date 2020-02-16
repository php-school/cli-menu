<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\ItemStyle;
use PhpSchool\CliMenu\Style\Selectable;
use PhpSchool\CliMenu\Util\StringUtil as s;
use function PhpSchool\CliMenu\Util\mapWithKeys;

class SelectableItemRenderer
{
    public function render(MenuStyle $menuStyle, MenuItemInterface $item, bool $selected, bool $disabled) : array
    {
        $itemStyle = $item->getStyle();
        $marker = $itemStyle->getMarker($item, $selected);
        $availableTextWidth = $this->getAvailableTextWidth($menuStyle, $itemStyle);

        return mapWithKeys(
            $this->wrapAndIndentText($marker, $item->getText(), $availableTextWidth),
            function (int $key, string $row) use ($menuStyle, $item, $availableTextWidth, $disabled) {
                $text = $disabled ? $menuStyle->getDisabledItemText($row) : $row;

                return $key === 0 && $item->showsItemExtra()
                    ? $this->lineWithExtra($text, $availableTextWidth, $item->getStyle())
                    : $text;
            }
        );
    }

    public function wrapAndIndentText(string $marker, string $text, int $availableWidth) : array
    {
        return explode(
            "\n",
            s::wordwrap(
                "{$marker}{$text}",
                $availableWidth,
                sprintf("\n%s", $this->emptyString(mb_strlen($marker)))
            )
        );
    }

    public function lineWithExtra(string $text, int $availableWidth, ItemStyle $itemStyle) : string
    {
        return sprintf(
            '%s%s  %s',
            $text,
            $this->emptyString($availableWidth - s::length($text)),
            $itemStyle->getItemExtra()
        );
    }

    public function emptyString(int $numCharacters) : string
    {
        return str_repeat(' ', $numCharacters);
    }

    public function getAvailableTextWidth(MenuStyle $menuStyle, ItemStyle $itemStyle) : int
    {
        return $itemStyle->getDisplaysExtra()
            ? $menuStyle->getContentWidth() - (mb_strlen($itemStyle->getItemExtra()) + 2)
            : $menuStyle->getContentWidth();
    }
}
