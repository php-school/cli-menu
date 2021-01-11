<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;
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
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(10);
        
        $item = new SelectableItem('Item', function () {
        });
        $this->assertEquals(['○ Item'], $item->getRows($menuStyle));
        $this->assertEquals(['○ Item'], $item->getRows($menuStyle, false));
        $this->assertEquals(['● Item'], $item->getRows($menuStyle, true));
    }

    public function testSetText() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(10);

        $item = new SelectableItem('Item', function () {
        });
        $item->setText('New Text');
        $this->assertEquals(['○ New Text'], $item->getRows($menuStyle));
        $this->assertEquals(['○ New Text'], $item->getRows($menuStyle, false));
        $this->assertEquals(['● New Text'], $item->getRows($menuStyle, true));
    }

    public function testGetRowsWithUnSelectedMarker() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new SelectableItem('Item', function () {
        });
        $item->getStyle()
            ->setUnselectedMarker('* ');
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

        $item = new SelectableItem('Item', function () {
        });
        $item->getStyle()
            ->setSelectedMarker('= ');
        $this->assertEquals(['= Item'], $item->getRows($menuStyle, true));
    }

    public function testGetRowsWithItemExtra() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(20);

        $item = new SelectableItem('Item', function () {
        }, true);
        $item->getStyle()
            ->setItemExtra('[EXTRA]')
            ->setDisplaysExtra(true)
            ->setUnselectedMarker('* ');
        $this->assertEquals(['* Item       [EXTRA]'], $item->getRows($menuStyle));
    }

    public function testGetRowsWithMultipleLinesWithItemExtra() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(20);

        $item = new SelectableItem('LONG ITEM LINE', function () {
        }, true);
        $item->getStyle()
            ->setItemExtra('[EXTRA]')
            ->setDisplaysExtra(true)
            ->setUnselectedMarker('* ');
        $this->assertEquals(
            [
                "* LONG ITEM  [EXTRA]",
                "  LINE",
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
