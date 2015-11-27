<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use Assert\InvalidArgumentException;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit_Framework_TestCase;

/**
 * Class AsciiArtItemTest
 * @package PhpSchool\CliMenuTest\MenuItem
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class AsciiArtItemTest extends PHPUnit_Framework_TestCase
{
    public function testExceptionIsThrownIfBreakCharNotString()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new AsciiArtItem(new \stdClass);
    }

    public function testExceptionIsThrownIfPositionNotValid()
    {
        $this->setExpectedException(InvalidArgumentException::class);
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

    public function testGetRowsLeftAligned()
    {
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

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
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

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
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

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
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

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
}
