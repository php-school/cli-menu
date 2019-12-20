<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\CheckboxItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;
use PhpSchool\CliMenu\MenuItem\RadioItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;
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
    public function testAddItemsWithBlacklistedItemTypeThrowsException(MenuItemInterface $menuItem) : void
    {
        self::expectExceptionMessage(\InvalidArgumentException::class);
        self::expectExceptionMessage(sprintf('Cannot add a %s to a SplitItem', get_class($menuItem)));

        (new SplitItem([]))->addItems([$menuItem]);
    }

    /**
     * @dataProvider blacklistedItemProvider
     */
    public function testAddItemWithBlacklistedItemTypeThrowsException(MenuItemInterface $menuItem) : void
    {
        self::expectExceptionMessage(\InvalidArgumentException::class);
        self::expectExceptionMessage(sprintf('Cannot add a %s to a SplitItem', get_class($menuItem)));

        (new SplitItem([]))->addItem($menuItem);
    }

    /**
     * @dataProvider blacklistedItemProvider
     */
    public function testSetItemsWithBlacklistedItemTypeThrowsException(MenuItemInterface $menuItem) : void
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

    public function testAddItem() : void
    {
        $item1 = new StaticItem('One');
        $item2 = new StaticItem('Two');
        $splitItem = new SplitItem();
        $splitItem->addItem($item1);
        
        self::assertEquals([$item1], $splitItem->getItems());

        $splitItem->addItem($item2);

        self::assertEquals([$item1, $item2], $splitItem->getItems());
    }

    public function testAddItems() : void
    {
        $item1 = new StaticItem('One');
        $item2 = new StaticItem('Two');
        $splitItem = new SplitItem();
        $splitItem->addItems([$item1]);

        self::assertEquals([$item1], $splitItem->getItems());
        
        $splitItem->addItems([$item2]);

        self::assertEquals([$item1, $item2], $splitItem->getItems());
    }

    public function testSetItems() : void
    {
        $item1 = new StaticItem('One');
        $item2 = new StaticItem('Two');
        $item3 = new StaticItem('Three');
        $splitItem = new SplitItem([$item1]);
        $splitItem->setItems([$item2, $item3]);

        self::assertEquals([$item2, $item3], $splitItem->getItems());
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

    public function testSetGutter() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(20));

        $item = new SplitItem([new StaticItem('One Two'), new StaticItem('Three')]);

        self::assertEquals(['One Two   Three     '], $item->getRows($menuStyle));

        $item->setGutter(5);

        self::assertEquals(['One       Three     ', 'Two                 '], $item->getRows($menuStyle));
    }

    /**
     * @dataProvider belowZeroProvider
     */
    public function testSetGutterThrowsExceptionIfValueIsNotZeroOrAbove(int $value) : void
    {
        self::expectException(\Assert\InvalidArgumentException::class);
        $item = new SplitItem();
        $item->setGutter($value);
    }
    public function belowZeroProvider() : array
    {
        return [[-1], [-2], [-10]];
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
            ->willReturnMap([[true, '= '], [false, '* ']]);

        $item = new SplitItem(
            [
                new SelectableItem('Item One', function () {
                }),
                new SelectableItem('Item Two', function () {
                })
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
            ->will($this->returnValue('* '));

        $item = new SplitItem(
            [
                new SelectableItem("Item\nOne", function () {
                }),
                new SelectableItem("Item\nTwo", function () {
                })
            ]
        );

        self::assertEquals(
            [
                '* Item         * Item         ',
                '  One            Two          ',
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
            ->willReturnMap([[true, '= '], [false, '* ']]);

        $item = new SplitItem(
            [
                new SelectableItem("Item\nOne", function () {
                }),
                new SelectableItem("Item\nTwo", function () {
                })
            ]
        );
        
        $item->setSelectedItemIndex(0);

        self::assertEquals(
            [
                '= Item         * Item         ',
                '  One            Two          ',
            ],
            $item->getRows($menuStyle, true)
        );
    }

    public function testGetRowsWithItemExtra() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(50);
        $menuStyle->setItemExtra('[EXTRA]');
        $menuStyle->setDisplaysExtra(true);
        $menuStyle->setUnselectedMarker('* ');

        $item = new SplitItem(
            [
                new SelectableItem('Item 1', function () {
                }, true),
                new SelectableItem('Item 2', function () {
                }, true)
            ]
        );
        
        self::assertEquals(['* Item 1        [EXTRA]  * Item 2        [EXTRA]  '], $item->getRows($menuStyle));
    }

    public function testGetRowsWithMultipleLinesWithItemExtra() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(50);
        $menuStyle->setItemExtra(' [EXTRA]');
        $menuStyle->setDisplaysExtra(true);
        $menuStyle->setUnselectedMarker('* ');

        $item = new SplitItem(
            [
                new SelectableItem("Item 1\nItem 1", function () {
                }, true),
                new SelectableItem("Item 2\nItem 2", function () {
                }, true)
            ]
        );

        self::assertEquals(
            [
                '* Item 1        [EXTRA]  * Item 2        [EXTRA]  ',
                '  Item 1                   Item 2                 ',
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsWithMultipleLinesWithItemExtraOnOne() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())->method('getWidth')->willReturn(100);

        $menuStyle = new MenuStyle($terminal);
        $menuStyle->setPaddingLeftRight(0);
        $menuStyle->setWidth(50);
        $menuStyle->setItemExtra(' [EXTRA] ');
        $menuStyle->setDisplaysExtra(true);
        $menuStyle->setUnselectedMarker('* ');

        $item = new SplitItem(
            [
                new SelectableItem("Item 1\nItem 1", function () {
                }),
                new SelectableItem("Item 2\nItem 2", function () {
                }, true)
            ]
        );

        self::assertEquals(
            [
                '* Item 1                 * Item 2       [EXTRA]   ',
                '  Item 1                   Item 2                 ',
            ],
            $item->getRows($menuStyle)
        );
    }
    
    public function testGetTextThrowsAnException() : void
    {
        self::expectException(\BadMethodCallException::class);
        self::expectExceptionMessage(sprintf('Not supported on: %s', SplitItem::class));
        
        (new SplitItem([]))->getText();
    }

    public function testGetRowsThrowsAnExceptionIfNoItemsWereAdded() : void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage(sprintf('There should be at least one item added to: %s', SplitItem::class));
        
        (new SplitItem([]))->getRows($this->createMock(MenuStyle::class));
    }

    public function testCanBeSelectedReturnsTrueWhenItContainsSelectableItems() : void
    {
        self::assertTrue((new SplitItem([new SelectableItem('One', 'strlen')]))->canSelect());
    }

    public function testCanBeSelectedReturnsFalseWhenItContainsNoSelectableItems() : void
    {
        self::assertFalse((new SplitItem([new StaticItem('One')]))->canSelect());
    }

    public function testGetSelectedItemIndexWhenSelectableItemExists() : void
    {
        $item1 = new StaticItem('One');
        $item2 = new SelectableItem('Two', function () {
        });
        
        $splitItem = new SplitItem([$item1, $item2]);
        
        self::assertEquals(1, $splitItem->getSelectedItemIndex());
    }

    public function testGetSelectedItemIndexWhenNoSelectableItemExists() : void
    {
        $item1 = new StaticItem('One');
        $splitItem = new SplitItem([$item1]);

        self::assertNull($splitItem->getSelectedItemIndex());
    }

    public function testSetSelectedItemIndexThrowsExceptionIsIndexDoesNotExist() : void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Index: "2" does not exist');
        
        (new SplitItem([]))->setSelectedItemIndex(2);
    }

    public function testGetSelectedItemReturnsItem() : void
    {
        $item1 = new StaticItem('One');
        $item2 = new SelectableItem('Two', function () {
        });

        $splitItem = new SplitItem([$item1, $item2]);
        self::assertSame($item2, $splitItem->getSelectedItem());
    }

    public function testGetSelectedItemThrowsExceptionWhenNoSelectableItemExists() : void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('No item is selected');
        
        $item1 = new StaticItem('One');

        $splitItem = new SplitItem([$item1]);
        self::assertSame($splitItem, $splitItem->getSelectedItem());
    }

    public function testCanSelectIndex() : void
    {
        $item1 = new StaticItem('One');
        $item2 = new SelectableItem('Two', function () {
        });

        $splitItem = new SplitItem([$item1, $item2]);
        
        self::assertFalse($splitItem->canSelectIndex(0));
        self::assertFalse($splitItem->canSelectIndex(5));
        self::assertTrue($splitItem->canSelectIndex(1));
    }

    public function testCheckboxItem() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));

        $checkboxItem1 = new CheckboxItem('Item One', function () {
        });
        $checkboxItem1->getStyle()
            ->setMarkerOff('[ ] ')
            ->setMarkerOn('[✔] ');

        $checkboxItem2 = new CheckboxItem('Item Two', function () {
        });
        $checkboxItem2->getStyle()
            ->setMarkerOff('[ ] ')
            ->setMarkerOn('[✔] ');

        $item = new SplitItem(
            [
                $checkboxItem1,
                $checkboxItem2,
            ]
        );

        $item->setSelectedItemIndex(0);

        self::assertEquals(['[ ] Item One   [ ] Item Two   '], $item->getRows($menuStyle, true));

        $checkboxItem1->toggle();

        self::assertEquals(['[✔] Item One   [ ] Item Two   '], $item->getRows($menuStyle, true));
    }

    public function testRadioItem() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));

        $menuStyle
            ->expects($this->any())
            ->method('getRadioMarker')
            ->willReturn('[●] ');

        $menuStyle
            ->expects($this->any())
            ->method('getUnradioMarker')
            ->willReturn('[○] ');

        $checkboxItem1 = new RadioItem('Item One', function () {
        });

        $checkboxItem2 = new RadioItem('Item Two', function () {
        });

        $item = new SplitItem(
            [
                $checkboxItem1,
                $checkboxItem2,
            ]
        );

        $cliMenu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getItems', 'redraw', 'getSelectedItemIndex', 'getItemByIndex'])
            ->getMock();

        $cliMenu->expects($this->never())
            ->method('getItems');

        $cliMenu->expects($this->atLeastOnce())
            ->method('getSelectedItemIndex')
            ->willReturn(1);

        $cliMenu->expects($this->atLeastOnce())
            ->method('getItemByIndex')
            ->willReturn($item);

        $item->setSelectedItemIndex(0);

        self::assertEquals(['[○] Item One   [○] Item Two   '], $item->getRows($menuStyle, true));

        $checkboxItem1->getSelectAction()($cliMenu);

        self::assertEquals(['[●] Item One   [○] Item Two   '], $item->getRows($menuStyle, true));

        $checkboxItem2->getSelectAction()($cliMenu);

        self::assertEquals(['[○] Item One   [●] Item Two   '], $item->getRows($menuStyle, true));
    }
}
