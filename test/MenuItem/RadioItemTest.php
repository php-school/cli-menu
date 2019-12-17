<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\RadioItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class RadioItemTest extends TestCase
{
    public function testCanSelectIsTrue() : void
    {
        $item = new RadioItem('Item', function () {
        });
        $this->assertTrue($item->canSelect());
    }

    public function testGetSelectAction() : void
    {
        $callable = function () {
            return 'callable is called';
        };
        $item = new RadioItem('Item', $callable);

        $cliMenu = $this->getMockBuilder(CLiMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertSame($callable(), $item->getSelectAction()($cliMenu));
    }

    public function testShowsItemExtra() : void
    {
        $item = new RadioItem('Item', function () {
        });
        $this->assertFalse($item->showsItemExtra());

        $item = new RadioItem('Item', function () {
        }, true);
        $this->assertTrue($item->showsItemExtra());
    }

    public function testGetText() : void
    {
        $item = new RadioItem('Item', function () {
        });
        $this->assertEquals('Item', $item->getText());
    }

    public function testGetRows() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(8);

        $item = new RadioItem('Item', function () {
        });

        $itemChecked = new RadioItem('Item', function () {
        });
        $itemChecked->toggle();
        $this->assertEquals(['[○] Item'], $item->getRows($menuStyle));
        $this->assertEquals(['[○] Item'], $item->getRows($menuStyle, false));
        $this->assertEquals(['[●] Item'], $itemChecked->getRows($menuStyle, true));
    }

    public function testSetText() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(12);

        $item = new RadioItem('Item', function () {
        });
        $item->setText('New Text');

        $itemChecked = new RadioItem('Item', function () {
        });
        $itemChecked->setText('New Text');
        $itemChecked->toggle();
        $this->assertEquals(['[○] New Text'], $item->getRows($menuStyle));
        $this->assertEquals(['[○] New Text'], $item->getRows($menuStyle, false));
        $this->assertEquals(['[●] New Text'], $itemChecked->getRows($menuStyle, true));
    }

    public function testTogglesMarker() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(12);

        $item = new RadioItem('Item', function () {
        });

        $itemChecked = new RadioItem('Item', function () {
        });
        $itemChecked->toggle();
        $this->assertEquals(['[○] Item'], $item->getRows($menuStyle));
        $this->assertEquals(['[○] Item'], $item->getRows($menuStyle, false));
        $this->assertEquals(['[●] Item'], $itemChecked->getRows($menuStyle, true));

        $itemChecked->toggle();

        $this->assertEquals(['[○] Item'], $itemChecked->getRows($menuStyle, true));
    }

    public function testTogglesUnselectedMarkers() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(12);

        $item1 = new RadioItem('Item', function () {
        });

        $item2 = new RadioItem('Item', function () {
        });

        $item3 = new RadioItem('Item', function () {
        });

        $cliMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getItems', 'redraw'])
            ->getMock();

        $cliMenu->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn([$item1, $item2, $item3]);

        $this->assertEquals(['[○] Item'], $item1->getRows($menuStyle));
        $this->assertEquals(['[○] Item'], $item2->getRows($menuStyle, false));
        $this->assertEquals(['[○] Item'], $item3->getRows($menuStyle, true));

        $item1->getSelectAction()($cliMenu);

        $this->assertEquals(['[●] Item'], $item1->getRows($menuStyle));
        $this->assertEquals(['[○] Item'], $item2->getRows($menuStyle, false));
        $this->assertEquals(['[○] Item'], $item3->getRows($menuStyle, true));

        $item3->getSelectAction()($cliMenu);

        $this->assertEquals(['[○] Item'], $item1->getRows($menuStyle));
        $this->assertEquals(['[○] Item'], $item2->getRows($menuStyle, false));
        $this->assertEquals(['[●] Item'], $item3->getRows($menuStyle, true));
    }

    public function testGetRowsWithItemExtra() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(20);
        $menuStyle->setItemExtra('[EXTRA]');
        $menuStyle->setDisplaysExtra(true);

        $item = new RadioItem('Item', function () {
        }, true);
        $this->assertEquals(['[○] Item     [EXTRA]'], $item->getRows($menuStyle));
    }

    public function testGetRowsWithMultipleLinesWithItemExtra() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(20);
        $menuStyle->setItemExtra('[EXTRA]');
        $menuStyle->setDisplaysExtra(true);

        $item = new RadioItem('LONG ITEM LINE', function () {
        }, true);
        $this->assertEquals(
            [
                "[○] LONG     [EXTRA]",
                "    ITEM LINE",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testHideAndShowItemExtra() : void
    {
        $item = new RadioItem('Item', function () {
        });

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertTrue($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }
}
