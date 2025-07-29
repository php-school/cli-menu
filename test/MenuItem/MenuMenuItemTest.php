<?php
declare(strict_types=1);

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MenuMenuItemTest extends TestCase
{
    public function testCanSelectIsTrue() : void
    {
        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('Item', $subMenu);
        $this->assertTrue($item->canSelect());
    }

    public function testGetSelectAction() : void
    {
        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('Item', $subMenu);
        
        $action = $item->getSelectAction();
        $this->assertIsCallable($action);
    }

    public function testShowsItemExtra() : void
    {
        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('Item', $subMenu);
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetText() : void
    {
        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('Item', $subMenu);
        $this->assertEquals('Item', $item->getText());
    }

    public function testGetRows() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(10);

        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('Item', $subMenu);
        $item->getStyle()
            ->setUnselectedMarker('* ');
        $this->assertEquals(['* Item'], $item->getRows($menuStyle));
    }

    public function testGetRowsWithUnSelectedMarker() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(10);

        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('Item', $subMenu);
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
            ->willReturn(10);

        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('Item', $subMenu);
        $item->getStyle()
            ->setSelectedMarker('= ');
        $this->assertEquals(['= Item'], $item->getRows($menuStyle, true));
    }


    public function testGetRowsWithMultipleLines() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(10);

        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('LONG ITEM LINE', $subMenu);
        $item->getStyle()
            ->setUnselectedMarker('* ');
        $this->assertEquals(
            [
                "* LONG",
                "  ITEM LINE",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testShowSubMenu() : void
    {
        $mainMenu = $this->createMock(CliMenu::class);
        $subMenu = $this->createMock(CliMenu::class);
        
        $mainMenu
            ->expects($this->once())
            ->method('closeThis');
        
        $subMenu
            ->expects($this->once())
            ->method('open');

        $item = new MenuMenuItem('Item', $subMenu);
        $item->showSubMenu($mainMenu);
    }

    public function testHideAndShowItemExtra() : void
    {
        $subMenu = $this->createMock(CliMenu::class);
        $item = new MenuMenuItem('Item', $subMenu);

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertTrue($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetSubMenu() : void
    {
        $subMenu = $this->createMock(CliMenu::class);
        $item = new MenuMenuItem('Item', $subMenu);
        
        self::assertSame($subMenu, $item->getSubMenu());
    }
}
