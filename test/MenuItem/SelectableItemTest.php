<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class SelectableItemTest extends TestCase
{
    public function testCanSelectIsTrue() : void
    {
        $item = new SelectableItem('Item', function () {
        });
        $this->assertTrue($item->canSelect());
    }

    public function testGetSelectAction() : void
    {
        $callable = function () {
        };
        $item = new SelectableItem('Item', $callable);
        $this->assertSame($callable, $item->getSelectAction());
    }

    public function testShowsItemExtra() : void
    {
        $item = new SelectableItem('Item', function () {
        });
        $this->assertFalse($item->showsItemExtra());

        $item = new SelectableItem('Item', function () {
        }, true);
        $this->assertTrue($item->showsItemExtra());
    }

    public function testGetText() : void
    {
        $item = new SelectableItem('Item', function () {
        });
        $this->assertEquals('Item', $item->getText());
    }

    public function testGetRows() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

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

    public function testGetRowsWithUnSelectedMarker() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

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

    public function testGetRowsWithSelectedMarker() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

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

    public function testGetRowsWithItemExtra() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

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

    public function testGetRowsWithMultipleLinesWithItemExtra() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

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
                " ITEM ",
                " LINE"
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testHideAndShowItemExtra() : void
    {
        $item = new SelectableItem('Item', function () {
        });

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertTrue($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }
}
