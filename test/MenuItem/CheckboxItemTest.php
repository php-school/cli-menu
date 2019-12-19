<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\CheckboxItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class CheckboxItemTest extends TestCase
{
    public function testCanSelectIsTrue() : void
    {
        $item = new CheckboxItem('Item', function () {
        });
        $this->assertTrue($item->canSelect());
    }

    public function testGetSelectAction() : void
    {
        $callable = function () {
            return 'callable is called';
        };
        $item = new CheckboxItem('Item', $callable);

        $cliMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertSame($callable(), $item->getSelectAction()($cliMenu));
    }

    public function testSelectActionTogglesItem() : void
    {
        $callable = function () {
        };

        $item = new CheckboxItem('Item', $callable);

        self::assertFalse($item->getChecked());

        $cliMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item->getSelectAction()($cliMenu);

        self::assertTrue($item->getChecked());
    }

    public function testShowsItemExtra() : void
    {
        $item = new CheckboxItem('Item', function () {
        });
        $this->assertFalse($item->showsItemExtra());

        $item = new CheckboxItem('Item', function () {
        }, true);
        $this->assertTrue($item->showsItemExtra());
    }

    public function testGetText() : void
    {
        $item = new CheckboxItem('Item', function () {
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

        $item = new CheckboxItem('Item', function () {
        });

        $itemChecked = new CheckboxItem('Item', function () {
        });
        $itemChecked->toggle();
        $this->assertEquals(['[ ] Item'], $item->getRows($menuStyle));
        $this->assertEquals(['[ ] Item'], $item->getRows($menuStyle, false));
        $this->assertEquals(['[✔] Item'], $itemChecked->getRows($menuStyle, true));
    }

    public function testSetText() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(12);

        $item = new CheckboxItem('Item', function () {
        });
        $item->setText('New Text');

        $itemChecked = new CheckboxItem('Item', function () {
        });
        $itemChecked->setText('New Text');
        $itemChecked->toggle();
        $this->assertEquals(['[ ] New Text'], $item->getRows($menuStyle));
        $this->assertEquals(['[ ] New Text'], $item->getRows($menuStyle, false));
        $this->assertEquals(['[✔] New Text'], $itemChecked->getRows($menuStyle, true));
    }

    public function testTogglesMarker() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(12);

        $item = new CheckboxItem('Item', function () {
        });

        $itemChecked = new CheckboxItem('Item', function () {
        });
        $itemChecked->toggle();
        $this->assertEquals(['[ ] Item'], $item->getRows($menuStyle));
        $this->assertEquals(['[ ] Item'], $item->getRows($menuStyle, false));
        $this->assertEquals(['[✔] Item'], $itemChecked->getRows($menuStyle, true));

        $itemChecked->toggle();

        $this->assertEquals(['[ ] Item'], $itemChecked->getRows($menuStyle, true));
    }

    public function testGetRowsWithItemExtra() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(20);

        $item = new CheckboxItem('Item', function () {
        }, true);
        $item->getStyle()
            ->setItemExtra('[EXTRA]')
            ->setDisplaysExtra(true);
        $this->assertEquals(['[ ] Item     [EXTRA]'], $item->getRows($menuStyle));
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

        $item = new CheckboxItem('LONG ITEM LINE', function () {
        }, true);
        $item->getStyle()
            ->setItemExtra('[EXTRA]')
            ->setDisplaysExtra(true);
        $this->assertEquals(
            [
                "[ ] LONG     [EXTRA]",
                "    ITEM LINE",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testHideAndShowItemExtra() : void
    {
        $item = new CheckboxItem('Item', function () {
        });

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertTrue($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }
}
