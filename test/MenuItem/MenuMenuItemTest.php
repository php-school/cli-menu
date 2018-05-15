<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuStyle;
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
        $this->assertTrue(is_callable($action));
        $this->assertInternalType('array', $action);
        $this->assertSame($item, $action[0]);
        $this->assertSame('showSubMenu', $action[1]);
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
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('Item', $subMenu);
        $this->assertEquals([' Item'], $item->getRows($menuStyle));
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
            ->will($this->returnValue('* '));

        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('Item', $subMenu);
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
            ->will($this->returnValue('= '));

        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('Item', $subMenu);
        $this->assertEquals(['= Item'], $item->getRows($menuStyle, true));
    }


    public function testGetRowsWithMultipleLines() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $subMenu = $this->createMock(CliMenu::class);

        $item = new MenuMenuItem('LONG ITEM LINE', $subMenu);
        $this->assertEquals(
            [
                " LONG ITEM",
                " LINE",
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
