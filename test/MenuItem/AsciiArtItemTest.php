<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use Assert\InvalidArgumentException;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class AsciiArtItemTest extends TestCase
{
    public function testExceptionIsThrownIfBreakCharNotString()
    {
        $this->expectException(InvalidArgumentException::class);
        new AsciiArtItem(new \stdClass);
    }

    public function testExceptionIsThrownIfPositionNotValid()
    {
        $this->expectException(InvalidArgumentException::class);
        new AsciiArtItem('////\\\\', new \stdClass);
    }

    public function testCanSelectIsFalse()
    {
        $item = new AsciiArtItem('////\\\\');
        $this->assertFalse($item->canSelect());
    }

    public function testGetSelectActionReturnsNull()
    {
        $item = new AsciiArtItem('////\\\\');
        $this->assertNull($item->getSelectAction());
    }

    public function testShowsItemExtraReturnsFalse()
    {
        $item = new AsciiArtItem('////\\\\');
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetText()
    {
        $item = new AsciiArtItem('////\\\\');
        $this->assertEquals('////\\\\', $item->getText());
    }

    public function testGetArtLength()
    {
        $item = new AsciiArtItem("//\n//\n///");
        $this->assertEquals(3, $item->getArtLength());
    }

    public function testGetRowsLeftAligned()
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_LEFT);
        $this->assertEquals(
            [
                "//",
                "//",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsRightAligned()
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_RIGHT);
        $this->assertEquals(
            [
                "        //",
                "        //",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsCenterAligned()
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_CENTER);
        $this->assertEquals(
            [
                "    //    ",
                "    //    ",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsCenterAlignedWithOddWidth()
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(11));

        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_CENTER);
        $this->assertEquals(
            [
                "     //    ",
                "     //    ",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testHideAndShowItemExtraHasNoEffect()
    {
        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_CENTER);

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertFalse($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }
}
