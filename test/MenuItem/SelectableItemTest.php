<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use Assert\InvalidArgumentException;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit_Framework_TestCase;

/**
 * Class SelectableItemTest
 * @package PhpSchool\CliMenuTest\MenuItem
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class SelectableItemTest extends PHPUnit_Framework_TestCase
{
    public function testExceptionIsThrownIfBreakCharNotString()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new SelectableItem(new \stdClass, function () {
        });
    }

    public function testCanSelectIsTrue()
    {
        $item = new SelectableItem('Item', function () {
        });
        $this->assertTrue($item->canSelect());
    }

    public function testGetSelectAction()
    {
        $callable = function () {
        };
        $item = new SelectableItem('Item', $callable);
        $this->assertSame($callable, $item->getSelectAction());
    }

    public function testShowsItemExtra()
    {
        $item = new SelectableItem('Item', function () {
        });
        $this->assertFalse($item->showsItemExtra());

        $item = new SelectableItem('Item', function () {
        }, true);
        $this->assertTrue($item->showsItemExtra());
    }

    public function testGetText()
    {
        $item = new SelectableItem('Item', function () {
        });
        $this->assertEquals('Item', $item->getText());
    }

    public function testGetRows()
    {
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));
        
        $item = new SelectableItem('Item', function () {
        });
        $this->assertEquals([' Item'], $item->getRows($menuStyle));
        $this->assertEquals([' Item'], $item->getRows($menuStyle, false));
        $this->assertEquals([' Item'], $item->getRows($menuStyle, true));
    }

    public function testGetRowsWithUnSelectedMarker()
    {
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $menuStyle
            ->expects($this->exactly(2))
            ->method('getMarker')
            ->with(false)
            ->will($this->returnValue('*'));

        $item = new SelectableItem('Item', function () {
        });
        $this->assertEquals(['* Item'], $item->getRows($menuStyle));
        $this->assertEquals(['* Item'], $item->getRows($menuStyle, false));
    }

    public function testGetRowsWithSelectedMarker()
    {
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $menuStyle
            ->expects($this->once())
            ->method('getMarker')
            ->with(true)
            ->will($this->returnValue('='));

        $item = new SelectableItem('Item', function () {
        });
        $this->assertEquals(['= Item'], $item->getRows($menuStyle, true));
    }

    public function testGetRowsWithItemExtra()
    {
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $menuStyle
            ->expects($this->once())
            ->method('getItemExtra')
            ->will($this->returnValue('[EXTRA]'));

        $item = new SelectableItem('Item', function () {
        }, true);
        $this->assertEquals([' Item       [EXTRA]'], $item->getRows($menuStyle));
    }

    public function testGetRowsWithMultipleLinesWithItemExtra()
    {
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $menuStyle
            ->expects($this->once())
            ->method('getItemExtra')
            ->will($this->returnValue('[EXTRA]'));

        $item = new SelectableItem('LONG ITEM LINE', function () {
        }, true);
        $this->assertEquals(
            [
                " LONG       [EXTRA]",
                " ITEM LINE"
            ],
            $item->getRows($menuStyle)
        );
    }
}
