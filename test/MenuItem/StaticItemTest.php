<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use Assert\InvalidArgumentException;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class StaticItemTest extends TestCase
{
    public function testExceptionIsThrownIfArgumentNotString()
    {
        $this->expectException(InvalidArgumentException::class);
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
        $menuStyle = $this->createMock(MenuStyle::class);
        
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
        $menuStyle = $this->createMock(MenuStyle::class);

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

    public function testHideAndShowItemExtraHasNoEffect()
    {
        $item = new StaticItem('CONTENT 1 LINE');

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertFalse($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }
}
