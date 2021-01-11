<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Style;

use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\Style\DefaultStyle;
use PHPUnit\Framework\TestCase;

class DefaultStyleTest extends TestCase
{
    public function testHasChangedFromDefaultsWhenNoStylesChanged() : void
    {
        self::assertTrue((new DefaultStyle())->hasChangedFromDefaults());
    }

    public function testGetMarker() : void
    {
        $item = new LineBreakItem('X');
        $style = new DefaultStyle;

        self::assertSame('', $style->getMarker($item, false));
        self::assertSame('', $style->getMarker($item, true));
    }

    public function testGetSetItemExtra() : void
    {
        $style = new DefaultStyle;

        self::assertSame('', $style->getItemExtra());
    }


    public function testGetSetDisplayExtra() : void
    {
        $style = new DefaultStyle;

        self::assertFalse($style->getDisplaysExtra());
    }
}
