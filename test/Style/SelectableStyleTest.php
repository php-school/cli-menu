<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Style;

use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\Style\SelectableStyle;
use PHPUnit\Framework\TestCase;

class SelectableStyleTest extends TestCase
{
    public function testHasChangedFromDefaultsWhenNoStylesChanged(): void
    {
        self::assertFalse((new SelectableStyle())->hasChangedFromDefaults());
    }

    public function testGetMarker(): void
    {
        $item = new SelectableItem('My Item', 'var_dump');
        $style = new SelectableStyle();

        self::assertSame('● ', $style->getMarker($item, true));
        self::assertSame('○ ', $style->getMarker($item, false));
    }

    public function testGetSetMarkerOn(): void
    {
        $style = new SelectableStyle();

        self::assertSame('● ', $style->getSelectedMarker());

        $style->setSelectedMarker('x ');

        self::assertSame('x ', $style->getSelectedMarker());
        self::assertTrue($style->hasChangedFromDefaults());
    }

    public function testGetSetMarkerOff(): void
    {
        $style = new SelectableStyle();

        self::assertSame('○ ', $style->getUnselectedMarker());

        $style->setUnselectedMarker('( ) ');

        self::assertSame('( ) ', $style->getUnselectedMarker());
        self::assertTrue($style->hasChangedFromDefaults());
    }

    public function testGetSetItemExtra(): void
    {
        $style = new SelectableStyle();

        self::assertSame('✔', $style->getItemExtra());

        $style->setItemExtra('[!EXTRA]!');

        self::assertSame('[!EXTRA]!', $style->getItemExtra());
        self::assertTrue($style->hasChangedFromDefaults());
    }

    public function testModifyingItemExtraForcesExtraToBeDisplayedWhenNoItemsDisplayExtra(): void
    {
        $style = new SelectableStyle();
        self::assertFalse($style->getDisplaysExtra());

        $style->setItemExtra('[!EXTRA]!');
        self::assertTrue($style->getDisplaysExtra());
    }

    public function testGetSetDisplayExtra(): void
    {
        $style = new SelectableStyle();

        self::assertFalse($style->getDisplaysExtra());

        $style->setDisplaysExtra(true);

        self::assertTrue($style->getDisplaysExtra());
        self::assertTrue($style->hasChangedFromDefaults());
    }
}
