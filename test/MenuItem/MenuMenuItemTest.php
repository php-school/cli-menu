<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use Assert\InvalidArgumentException;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit_Framework_TestCase;

/**
 * Class MenuMenuItemTest
 * @package PhpSchool\CliMenuTest\MenuItem
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MenuMenuItemTest extends PHPUnit_Framework_TestCase
{
    public function testExceptionIsThrownIfBreakCharNotString()
    {
        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->setExpectedException(InvalidArgumentException::class);
        new MenuMenuItem(new \stdClass, $subMenu);
    }

    public function testCanSelectIsTrue()
    {
        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('Item', $subMenu);
        $this->assertTrue($item->canSelect());
    }

    public function testGetSelectAction()
    {
        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('Item', $subMenu);
        
        $action = $item->getSelectAction();
        $this->assertTrue(is_callable($action));
        $this->assertInternalType('array', $action);
        $this->assertSame($item, $action[0]);
        $this->assertSame('showSubMenu', $action[1]);
    }

    public function testShowsItemExtra()
    {
        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('Item', $subMenu);
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetText()
    {
        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('Item', $subMenu);
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

        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('Item', $subMenu);
        $this->assertEquals([' Item'], $item->getRows($menuStyle));
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

        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('Item', $subMenu);
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

        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('Item', $subMenu);
        $this->assertEquals(['= Item'], $item->getRows($menuStyle, true));
    }


    public function testGetRowsWithMultipleLines()
    {
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $item = new MenuMenuItem('LONG ITEM LINE', $subMenu);
        $this->assertEquals(
            [
                " LONG ",
                " ITEM LINE"
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testShowSubMenu()
    {
        $mainMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $subMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mainMenu
            ->expects($this->once())
            ->method('closeThis');
        
        $subMenu
            ->expects($this->once())
            ->method('open');

        $item = new MenuMenuItem('Item', $subMenu);
        $item->showSubMenu($mainMenu);
    }
}
