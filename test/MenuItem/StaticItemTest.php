<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class StaticItemTest extends TestCase
{
    public function testCanSelectIsFalse() : void
    {
        $item = new StaticItem('Item 1');
        $this->assertFalse($item->canSelect());
    }

    public function testGetSelectActionReturnsNull() : void
    {
        $item = new StaticItem('Item 1');
        $this->assertNull($item->getSelectAction());
    }

    public function testShowsItemExtraReturnsFalse() : void
    {
        $item = new StaticItem('Item 1');
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetText() : void
    {
        $item = new StaticItem('Item 1');
        $this->assertEquals('Item 1', $item->getText());
    }

    public function testGetRowsWithContentWhichFitsOnOneLine() : void
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

    public function testGetRowsWithContentWhichDoesNotFitOnOneLineIsWrapped() : void
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

    public function testHideAndShowItemExtraHasNoEffect() : void
    {
        $item = new StaticItem('CONTENT 1 LINE');

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertFalse($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }
}
