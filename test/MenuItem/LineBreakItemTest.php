<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class LineBreakItemTest extends TestCase
{
    public function testCanSelectIsFalse() : void
    {
        $item = new LineBreakItem('*');
        $this->assertFalse($item->canSelect());
    }

    public function testGetSelectActionReturnsNull() : void
    {
        $item = new LineBreakItem('*');
        $this->assertNull($item->getSelectAction());
    }

    public function testShowsItemExtraReturnsFalse() : void
    {
        $item = new LineBreakItem('*');
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetText() : void
    {
        $item = new LineBreakItem('*');
        $this->assertEquals('*', $item->getText());
    }

    public function testGetRowsRepeatsCharForMenuWidth() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new LineBreakItem('*');
        $this->assertEquals(['**********'], $item->getRows($menuStyle));
    }

    public function testGetRowsRepeatsCharForMenuWidthMultiLines() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new LineBreakItem('*', 3);
        $this->assertEquals(['**********', '**********', '**********'], $item->getRows($menuStyle));
    }

    public function testGetRowsWithPhraseThatDoesNotFitInWidthEvenlyIsTrimmed() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(5));
        
        //ABC should be repeated but ABCABC is 6 and the allowed length is 5
        //so ABCABC is trimmed to ABCAB

        $item = new LineBreakItem('ABC', 3);
        $this->assertEquals(['ABCAB', 'ABCAB', 'ABCAB'], $item->getRows($menuStyle));
    }

    public function testGetRowsWithMultiByteChars() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(5));

        $item = new LineBreakItem('❅', 2);
        $this->assertEquals(['❅❅❅❅❅', '❅❅❅❅❅'], $item->getRows($menuStyle));
    }

    public function testSetText() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(5));

        $item = new LineBreakItem('ABC', 2);
        $item->setText('❅-');
        $this->assertEquals(['❅-❅-❅', '❅-❅-❅'], $item->getRows($menuStyle));
    }

    public function testHideAndShowItemExtraHasNoEffect() : void
    {
        $item = new LineBreakItem('*');

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertFalse($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }
}
