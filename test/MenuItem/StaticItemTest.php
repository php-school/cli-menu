<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use Assert\InvalidArgumentException;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit_Framework_TestCase;

/**
 * Class StaticItemTest
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class StaticItemTest extends PHPUnit_Framework_TestCase
{
    public function testExceptionIsThrownIfArgumentNotString()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new StaticItem(new \stdClass);
    }

    public function testCanSelectIsFalse()
    {
        $item = new StaticItem('Item 1');
        $this->assertFalse($item->canSelect());
    }

    public function testGetSelectActionReturnsNull()
    {
        $item = new StaticItem('Item 1');
        $this->assertNull($item->getSelectAction());
    }

    public function testShowsItemExtraReturnsFalse()
    {
        $item = new StaticItem('Item 1');
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetText()
    {
        $item = new StaticItem('Item 1');
        $this->assertEquals('Item 1', $item->getText());
    }

    public function testGetRowsWithContentWhichFitsOnOneLine()
    {
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $menuStyle
            ->expects($this->once())
            ->method('getContentWidth')
            ->will($this->returnValue(20));

        $item = new StaticItem('CONTENT 1 LINE');
        
        $this->assertEquals(
            ['CONTENT 1 LINE'],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsWithContentWhichDoesNotFitOnOneLineIsWrapped()
    {
        $menuStyle = $this->getMockBuilder(MenuStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $menuStyle
            ->expects($this->once())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new StaticItem('CONTENT 1 LINE');

        $this->assertEquals(
            ['CONTENT 1 ', 'LINE'],
            $item->getRows($menuStyle)
        );
    }
}
