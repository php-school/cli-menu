<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class SplitItemTest extends TestCase
{
   
    /**
     * @dataProvider blacklistedItemProvider
     */
    public function testConstructWithBlacklistedItemTypeThrowsException(MenuItemInterface $menuItem) : void
    {
        self::expectExceptionMessage(\InvalidArgumentException::class);
        self::expectExceptionMessage(sprintf('Cannot add a %s to a SplitItem', get_class($menuItem)));
        
        new SplitItem([$menuItem]);
    }

    /**
     * @dataProvider blacklistedItemProvider
     */
    public function testAddMenuItemsWithBlacklistedItemTypeThrowsException(MenuItemInterface $menuItem) : void
    {
        self::expectExceptionMessage(\InvalidArgumentException::class);
        self::expectExceptionMessage(sprintf('Cannot add a %s to a SplitItem', get_class($menuItem)));

        (new SplitItem([]))->addMenuItems([$menuItem]);
    }

    /**
     * @dataProvider blacklistedItemProvider
     */
    public function testAddMenuItemWithBlacklistedItemTypeThrowsException(MenuItemInterface $menuItem) : void
    {
        self::expectExceptionMessage(\InvalidArgumentException::class);
        self::expectExceptionMessage(sprintf('Cannot add a %s to a SplitItem', get_class($menuItem)));

        (new SplitItem([]))->addMenuItem($menuItem);
    }

    /**
     * @dataProvider blacklistedItemProvider
     */
    public function testSetMenuItemsWithBlacklistedItemTypeThrowsException(MenuItemInterface $menuItem) : void
    {
        self::expectExceptionMessage(\InvalidArgumentException::class);
        self::expectExceptionMessage(sprintf('Cannot add a %s to a SplitItem', get_class($menuItem)));

        (new SplitItem([]))->setItems([$menuItem]);
    }

    public function blacklistedItemProvider() : array
    {
        return [
            [new AsciiArtItem('( ︶︿︶)_╭∩╮')],
            [new LineBreakItem('*')],
            [new SplitItem([])],
        ];
    }

    public function testGetItems() : void
    {
        $item = new StaticItem('test');
        
        self::assertEquals([], (new SplitItem([]))->getItems());
        self::assertEquals([$item], (new SplitItem([$item]))->getItems());
    }

    public function testGetSelectActionReturnsNull() : void
    {
        $item = new SplitItem([]);
        $this->assertNull($item->getSelectAction());
    }

    public function testHideAndShowItemExtraHasNoEffect() : void
    {
        $item = new SplitItem([]);

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertFalse($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetRowsWithStaticItems() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));
        
        $item = new SplitItem([new StaticItem('One'), new StaticItem('Two')]);

        self::assertEquals(['One            Two            '], $item->getRows($menuStyle));
    }

    public function testGetRowsWithOneItemSelected() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));

        $menuStyle
            ->expects($this->any())
            ->method('getMarker')
            ->willReturnMap([[true, '='], [false, '*']]);

        $item = new SplitItem(
            [
                new SelectableItem('Item One', function () {}),
                new SelectableItem('Item Two', function () {})
            ]
        );

        $item->setSelectedItemIndex(0);

        self::assertEquals(['= Item One     * Item Two     '], $item->getRows($menuStyle, true));
    }

    public function testGetRowsWithMultipleLineStaticItems() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));

        $item = new SplitItem([new StaticItem("Item\nOne"), new StaticItem("Item\nTwo")]);

        self::assertEquals(
            [
                'Item           Item           ',
                'One            Two            ',
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsWithMultipleLinesWithUnSelectedMarker() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));

        $menuStyle
            ->expects($this->any())
            ->method('getMarker')
            ->with(false)
            ->will($this->returnValue('*'));

        $item = new SplitItem([
            new SelectableItem("Item\nOne", function () {}),
            new SelectableItem("Item\nTwo", function () {})
        ]);

        self::assertEquals(
            [
                '* Item         * Item         ',
                'One            Two            ',
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsWithMultipleLinesWithOneItemSelected() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));

        $menuStyle
            ->expects($this->any())
            ->method('getMarker')
            ->willReturnMap([[true, '='], [false, '*']]); 

        $item = new SplitItem([
            new SelectableItem("Item\nOne", function () {}),
            new SelectableItem("Item\nTwo", function () {})
        ]);
        
        $item->setSelectedItemIndex(0);

        self::assertEquals(
            [
                '= Item         * Item         ',
                'One            Two            ',
            ],
            $item->getRows($menuStyle, true)
        );
    }
    
    public function testGetTextThrowsAnException() : void
    {
        self::expectException(\BadMethodCallException::class);
        self::expectExceptionMessage(sprintf('Not supported on: %s', SplitItem::class));
        
        (new SplitItem([]))->getText();
    }
}
