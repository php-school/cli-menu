<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use Assert\InvalidArgumentException;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit\Framework\TestCase;

/**
 * Class LineBreakItemTest
 * @package PhpSchool\CliMenuTest\MenuItem
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class LineBreakItemTest extends TestCase
{
    public function testExceptionIsThrownIfBreakCharNotString()
    {
        $this->expectException(InvalidArgumentException::class);
        new LineBreakItem(new \stdClass);
    }

    public function testExceptionIsThrownIfNumLinesNotInt()
    {
        $this->expectException(InvalidArgumentException::class);
        new LineBreakItem('*', new \stdClass);
    }

    public function testCanSelectIsFalse()
    {
        $item = new LineBreakItem('*');
        $this->assertFalse($item->canSelect());
    }

    public function testGetSelectActionReturnsNull()
    {
        $item = new LineBreakItem('*');
        $this->assertNull($item->getSelectAction());
    }

    public function testShowsItemExtraReturnsFalse()
    {
        $item = new LineBreakItem('*');
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetText()
    {
        $item = new LineBreakItem('*');
        $this->assertEquals('*', $item->getText());
    }

    public function testGetRowsRepeatsCharForMenuWidth()
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new LineBreakItem('*');
        $this->assertEquals(['**********'], $item->getRows($menuStyle));
    }

    public function testGetRowsRepeatsCharForMenuWidthMultiLines()
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new LineBreakItem('*', 3);
        $this->assertEquals(['**********', '**********', '**********'], $item->getRows($menuStyle));
    }

    public function testGetRowsWithPhraseThatDoesNotFitInWidthEvenlyIsTrimmed()
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

    public function testGetRowsWithMultiByteChars()
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(5));

        $item = new LineBreakItem('❅', 2);
        $this->assertEquals(['❅❅❅❅❅', '❅❅❅❅❅'], $item->getRows($menuStyle));
    }

    public function testHideAndShowItemExtraHasNoEffect()
    {
        $item = new LineBreakItem('*');

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertFalse($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }
}
