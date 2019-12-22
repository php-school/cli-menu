<?php

namespace PhpSchool\CliMenuTest;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\MenuNotOpenException;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;
use PhpSchool\Terminal\UnixTerminal;
use PhpSchool\Terminal\IO\BufferedOutput;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class CliMenuTest extends TestCase
{
    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @var BufferedOutput
     */
    private $output;

    public function setUp() : void
    {
        $this->output = new BufferedOutput;
        $this->terminal = $this->createMock(Terminal::class);

        $this->terminal->expects($this->any())
            ->method('isInteractive')
            ->willReturn(true);

        $this->terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(46);

        $this->terminal->expects($this->any())
            ->method('write')
            ->will($this->returnCallback(function ($buffer) {
                $this->output->write($buffer);
            }));
    }

    public function testGetMenuStyle() : void
    {
        $menu = new CliMenu('PHP School FTW', []);
        self::assertInstanceOf(MenuStyle::class, $menu->getStyle());

        $style = new MenuStyle();
        $menu = new CliMenu('PHP School FTW', [], null, $style);
        self::assertSame($style, $menu->getStyle());
    }

    public function testReDrawThrowsExceptionIfMenuNotOpen() : void
    {
        $menu = new CliMenu('PHP School FTW', []);

        $this->expectException(MenuNotOpenException::class);

        $menu->redraw();
    }

    public function testSimpleOpenClose() : void
    {
        $this->terminal->expects($this->once())
            ->method('read')
            ->willReturn("\n");

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSimpleOpenCloseWithBorders() : void
    {
        $this->terminal->expects($this->once())
            ->method('read')
            ->willReturn("\n");

        $style = $this->getStyle($this->terminal);
        $style->setBorder(1, 2, 'red');

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSimpleOpenCloseWithLeftAndRightBorders() : void
    {
        $this->terminal->expects($this->once())
            ->method('read')
            ->willReturn("\n");

        $style = $this->getStyle($this->terminal);
        $style->setBorderLeftWidth(2);
        $style->setBorderRightWidth(2);
        $style->setBorderColour('red');

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSimpleOpenCloseWithMarginAutoAndBorders() : void
    {
        $this->terminal->expects($this->once())
            ->method('read')
            ->willReturn("\n");

        $style = $this->getStyle($this->terminal);
        $style->setBorder(1, 2, 'red');
        $style->setMarginAuto();
        $style->setWidth(30);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSimpleOpenCloseWithPaddingTopAndBottom() : void
    {
        $this->terminal->expects($this->once())
            ->method('read')
            ->willReturn("\n");

        $style = $this->getStyle($this->terminal);
        $style->setPaddingTopBottom(2);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSimpleOpenCloseWithPaddingLeftAndRight() : void
    {
        $this->terminal->expects($this->once())
            ->method('read')
            ->willReturn("\n");

        $style = $this->getStyle($this->terminal);
        $style->setPaddingLeftRight(5);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSimpleOpenCloseWithDifferentXAndYPadding() : void
    {
        $this->terminal->expects($this->once())
            ->method('read')
            ->willReturn("\n");

        $style = $this->getStyle($this->terminal);
        $style->setPaddingLeftRight(5);
        $style->setPaddingTopBottom(4);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }
    
    public function testReDrawReDrawsImmediately() : void
    {
        $this->terminal->expects($this->once())
            ->method('read')
            ->willReturn("\n");

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->getStyle()->setBg('red');
            $menu->redraw();
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testRedrawClearsTerminalFirstIfOptionIsPassed() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->any())
            ->method('isInteractive')
            ->willReturn(true);

        $terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(80);

        $terminal->expects($this->any())
            ->method('write')
            ->will($this->returnCallback(function ($buffer) {
                $this->output->write($buffer);
            }));
        
        $terminal->expects($this->exactly(3))
            ->method('read')
            ->willReturn("\n", "\n", "\n");
        
        $terminal->expects($this->atLeast(2))
            ->method('clear');

        $style = $this->getStyle($terminal);
        $style->setWidth(70);

        $hits = 0;
        $item = new SelectableItem('Item 1', function (CliMenu $menu) use (&$hits) {
            if ($hits === 0) {
                $menu->getStyle()->setWidth(50);
                $menu->redraw(true);
            }

            if ($hits === 1) {
                $menu->getStyle()->setWidth(70);
                $menu->redraw(true);
            }
            
            if ($hits === 2) {
                $menu->close();
            }
            
            $hits++;
        });

        $menu = new CliMenu('PHP School FTW', [$item], $terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testGetItems() : void
    {
        $item1 = new LineBreakItem();
        $item2 = new LineBreakItem();


        $style = $this->getStyle($terminal = new MockTerminal);

        $menu = new CliMenu(
            'PHP School FTW',
            [
                $item1,
                $item2
            ],
            $terminal,
            $style
        );

        self::assertSame([$item1, $item2], $menu->getItems());
    }

    public function testRemoveItem() : void
    {
        $item1 = new LineBreakItem();
        $item2 = new LineBreakItem();

        $style = $this->getStyle($terminal = new MockTerminal);

        $menu = new CliMenu(
            'PHP School FTW',
            [
                $item1,
                $item2
            ],
            $terminal,
            $style
        );

        self::assertEquals([$item1, $item2], $menu->getItems());

        $menu->removeItem($item1);

        self::assertCount(1, $menu->getItems());
        self::assertContains($item2, $menu->getItems());
    }

    public function testRemoveItemThrowsExceptionWhenItemDoesntExistInMenu() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $item1 = new LineBreakItem();

        $menu = new CliMenu('PHP School FTW', []);

        $menu->removeItem($item1);
    }

    public function testFlashThrowsExceptionIfParameterContainsNewline() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $menu = new CliMenu('PHP School FTW', []);
        $menu->flash("Foo\nBar");
    }

    public function testConfirmThrowsExceptionIfParameterContainsNewline() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $menu = new CliMenu('PHP School FTW', []);
        $menu->confirm("Foo\nBar");
    }

    public function testThrowsExceptionIfTerminalIsNotValidTTY() : void
    {
        $this->expectException(\PhpSchool\CliMenu\Exception\InvalidTerminalException::class);

        $terminal = $this->createMock(Terminal::class);
        $terminal
            ->method('getWidth')
            ->willReturn(100);

        $terminal->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $menu = new CliMenu('PHP School FTW', [new StaticItem('One')], $terminal);

        $menu->open();
    }

    public function testOpenThrowsExceptionIfNoItemsInMenu() : void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Menu must have at least 1 item before it can be opened');
        
        (new CliMenu('PHP School FTW', [], $this->terminal))->open();
    }

    public function testGetTerminal() : void
    {
        $menu = new CliMenu('PHP School FTW', []);
        self::assertInstanceOf(UnixTerminal::class, $menu->getTerminal());
    }

    public function testAddItem() : void
    {
        $menu = new CliMenu('PHP School FTW', []);

        $this->assertCount(0, $menu->getItems());

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $menu->addItem($item);

        $this->assertCount(1, $menu->getItems());
    }

    public function testAddItems() : void
    {
        $menu = new CliMenu('PHP School FTW', []);

        $this->assertCount(0, $menu->getItems());

        $item1 = new SelectableItem('Item 2', function (CliMenu $menu) {
            $menu->close();
        });

        $item2 = new SelectableItem('Item 2', function (CliMenu $menu) {
            $menu->close();
        });

        $menu->addItems([$item1, $item2]);

        $this->assertCount(2, $menu->getItems());
    }

    public function testSetItems() : void
    {
        $menu = new CliMenu('PHP School FTW', []);

        $this->assertCount(0, $menu->getItems());

        $item1 = new SelectableItem('Item 2', function (CliMenu $menu) {
            $menu->close();
        });

        $item2 = new SelectableItem('Item 2', function (CliMenu $menu) {
            $menu->close();
        });

        $item3 = new SelectableItem('Item 2', function (CliMenu $menu) {
            $menu->close();
        });

        $item4 = new SelectableItem('Item 2', function (CliMenu $menu) {
            $menu->close();
        });

        $menu->addItems([$item1, $item2]);

        $this->assertCount(2, $menu->getItems());
        
        $menu->setItems([$item3, $item4]);

        $this->assertCount(2, $menu->getItems());
        $this->assertSame([$item3, $item4], $menu->getItems());
    }

    public function testAskNumberThrowsExceptionIfMenuNotOpen() : void
    {
        $menu = new CliMenu('PHP School FTW', []);

        self::expectException(MenuNotOpenException::class);

        $menu->askNumber();
    }

    public function testAskNumberStyle() : void
    {
        $terminal = $this->createMock(Terminal::class);

        $terminal->expects($this->any())
            ->method('isInteractive')
            ->willReturn(true);

        $terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(100);

        $terminal->expects($this->any())
            ->method('read')
            ->willReturn("\n");

        $menu = new CliMenu('PHP School FTW', [], $terminal);

        $number = null;
        $menu->addItem(new SelectableItem('Ask Number', function (CliMenu $menu) use (&$number) {
            $number = $menu->askNumber();
            $menu->close();
        }));
        $menu->open();

        self::assertEquals('yellow', $number->getStyle()->getBg());
        self::assertEquals('red', $number->getStyle()->getFg());
    }

    public function testAskTextThrowsExceptionIfMenuNotOpen() : void
    {
        $menu = new CliMenu('PHP School FTW', []);

        self::expectException(MenuNotOpenException::class);

        $menu->askText();
    }

    public function testAskTextStyle() : void
    {
        $terminal = $this->createMock(Terminal::class);

        $terminal->expects($this->any())
            ->method('isInteractive')
            ->willReturn(true);

        $terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(100);

        $terminal->expects($this->any())
            ->method('read')
            ->willReturn("\n");

        $menu = new CliMenu('PHP School FTW', [], $terminal);

        $text = null;
        $menu->addItem(new SelectableItem('Ask Number', function (CliMenu $menu) use (&$text) {
            $text = $menu->askText();
            $menu->close();
        }));
        $menu->open();

        self::assertEquals('yellow', $text->getStyle()->getBg());
        self::assertEquals('red', $text->getStyle()->getFg());
    }

    public function testAskPasswordThrowsExceptionIfMenuNotOpen() : void
    {
        $menu = new CliMenu('PHP School FTW', []);

        self::expectException(MenuNotOpenException::class);

        $menu->askPassword();
    }

    public function testAskPasswordStyle() : void
    {
        $terminal = $this->createMock(Terminal::class);

        $terminal->expects($this->any())
            ->method('isInteractive')
            ->willReturn(true);

        $terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(100);

        $terminal->expects($this->any())
            ->method('read')
            ->willReturn("\n");

        $menu = new CliMenu('PHP School FTW', [], $terminal);

        $password = null;
        $menu->addItem(new SelectableItem('Ask Number', function (CliMenu $menu) use (&$password) {
            $password = $menu->askPassword();
            $menu->close();
        }));
        $menu->open();

        self::assertEquals('yellow', $password->getStyle()->getBg());
        self::assertEquals('red', $password->getStyle()->getFg());
    }

    public function testAddCustomControlMappingThrowsExceptionWhenOverwritingExistingDefaultControls() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot rebind this input');

        $menu = new CliMenu('PHP School FTW', []);
        $menu->addCustomControlMapping(' ', function () {
        });
    }

    public function testAddCustomControlMappingThrowsExceptionWhenAttemptingToOverwriteAddedCustomControlMap() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot rebind this input');

        $menu = new CliMenu('PHP School FTW', []);
        $menu->addCustomControlMapping('c', function () {
        });
        $menu->addCustomControlMapping('c', function () {
        });
    }

    public function testAddCustomControlMapping() : void
    {
        $this->terminal->expects($this->once())
            ->method('read')
            ->willReturn('c');

        $style = $this->getStyle($this->terminal);

        $action = function (CliMenu $menu) {
            $menu->close();
        };
        $item = new SelectableItem('Item 1', $action);

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->addCustomControlMapping('c', $action);
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testAddCustomControlMappingsThrowsExceptionWhenOverwritingExistingDefaultControls() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot rebind this input');

        $menu = new CliMenu('PHP School FTW', []);
        $menu->addCustomControlMappings([
            ' ' => function () {
            }
        ]);
    }

    public function testAddCustomControlMappingsThrowsExceptionWhenAttemptingToOverwriteAddedCustomControlMap() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot rebind this input');

        $menu = new CliMenu('PHP School FTW', []);
        $menu->addCustomControlMappings([
            'c' => function () {
            }
        ]);
        $menu->addCustomControlMappings([
            'c' => function () {
            }
        ]);
    }

    public function testAddCustomControlMappings() : void
    {
        $this->terminal->expects($this->any())
            ->method('read')
            ->willReturn('c', 'x');

        $style = $this->getStyle($this->terminal);

        $action = function (CliMenu $menu) {
            $menu->close();
        };
        $item = new SelectableItem('Item 1', $action);

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->addCustomControlMappings([
            'c' => $action,
            'x' => $action
        ]);

        $menu->open();
        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());

        $menu->open();
        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testRemoveCustomControlMappingThrowsExceptionIfNoSuchMappingExists() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('This input is not registered');

        $menu = new CliMenu('PHP School FTW', []);
        $menu->removeCustomControlMapping('c');
    }

    public function testRemoveCustomControlMapping() : void
    {
        $action = function (CliMenu $menu) {
            $menu->close();
        };

        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addCustomControlMapping('c', $action);
        self::assertSame(['c' => $action], $menu->getCustomControlMappings());
        
        $menu->removeCustomControlMapping('c');
        self::assertSame([], $menu->getCustomControlMappings());
    }

    public function testSplitItemWithNoSelectableItemsScrollingVertically() : void
    {
        $this->terminal->expects($this->exactly(3))
            ->method('read')
            ->willReturn("\033[B", "\033[B", "\n");
        
        $action = function (CliMenu $menu) {
            $menu->close();
        };
        
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new SelectableItem('One', $action));
        $menu->addItem(new SplitItem([new StaticItem('Two'), new StaticItem('Three')]));
        $menu->addItem(new SelectableItem('Four', $action));
        
        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSplitItemWithSelectableItemsScrollingVertical() : void
    {
        $this->terminal->expects($this->exactly(4))
            ->method('read')
            ->willReturn("\033[B", "\033[B", "\033[B", "\n");

        $action = function (CliMenu $menu) {
            $menu->close();
        };

        $splitAction = function (CliMenu $menu) {
        };

        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new SelectableItem('One', $action));
        $menu->addItem(
            new SplitItem(
                [new SelectableItem('Two', $splitAction), new SelectableItem('Three', $splitAction)]
            )
        );
        $menu->addItem(new SelectableItem('Four', $action));

        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSplitItemWithSelectableItemsScrollingRight() : void
    {
        $this->terminal->expects($this->exactly(6))
            ->method('read')
            ->willReturn("\033[B", "\033[C", "\033[C", "\033[C", "\033[B", "\n");

        $action = function (CliMenu $menu) {
            $menu->close();
        };

        $splitAction = function (CliMenu $menu) {
        };

        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new SelectableItem('One', $action));
        $menu->addItem(
            new SplitItem(
                [new SelectableItem('Two', $splitAction), new SelectableItem('Three', $splitAction)]
            )
        );
        $menu->addItem(new SelectableItem('Four', $action));

        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSplitItemWithSelectableItemsScrollingLeft() : void
    {
        $this->terminal->expects($this->exactly(6))
            ->method('read')
            ->willReturn("\033[B", "\033[D", "\033[D", "\033[D", "\033[B", "\n");

        $action = function (CliMenu $menu) {
            $menu->close();
        };

        $splitAction = function (CliMenu $menu) {
        };

        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new SelectableItem('One', $action));
        $menu->addItem(
            new SplitItem(
                [
                    new SelectableItem('Two', $splitAction),
                    new SelectableItem('Three', $splitAction),
                    new SelectableItem('Four', $splitAction),
                ]
            )
        );
        $menu->addItem(new SelectableItem('Five', $action));

        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testSplitItemWithSelectableAndStaticItemsScrollingHorizontally() : void
    {
        $this->terminal->expects($this->exactly(6))
            ->method('read')
            ->willReturn("\033[B", "\033[D", "\033[D", "\033[D", "\033[B", "\n");

        $action = function (CliMenu $menu) {
            $menu->close();
        };

        $splitAction = function (CliMenu $menu) {
        };

        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new SelectableItem('One', $action));
        $menu->addItem(
            new SplitItem(
                [
                    new SelectableItem('Two', $splitAction),
                    new StaticItem('Three'),
                    new SelectableItem('Four', $splitAction),
                ]
            )
        );
        $menu->addItem(new SelectableItem('Five', $action));

        $menu->open();

        self::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }


    public function testSelectableCallableReceivesSelectableAndNotSplitItem() : void
    {
        $this->terminal->expects($this->exactly(1))
            ->method('read')
            ->willReturn("\n");

        $actualSelectedItem = null;
        $action = function (CliMenu $menu) use (&$actualSelectedItem) {
            $actualSelectedItem = $menu->getSelectedItem();
            $menu->close();
        };

        $expectedSelectedItem  = new SelectableItem('Two', $action);
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(
            new SplitItem(
                [
                    $expectedSelectedItem,
                    new StaticItem('Three'),
                    new SelectableItem('Four', $action),
                ]
            )
        );
        $menu->open();
        
        self::assertSame($expectedSelectedItem, $actualSelectedItem);
    }

    public function testAddItemSelectsFirstSelectableItemWhenItemsExistButNoneAreSelectable() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new StaticItem('No Selectable'));

        try {
            $menu->getSelectedItem();
            $this->fail('Exception not thrown');
        } catch (\RuntimeException $e) {
        }

        $menu->addItem($item = new SelectableItem('Selectable', function () {
        }));

        self::assertEquals($item, $menu->getSelectedItem());
    }

    public function testAddItemsSelectsFirstSelectableItemWhenItemsExistButNoneAreSelectable() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new StaticItem('No Selectable'));

        try {
            $menu->getSelectedItem();
            $this->fail('Exception not thrown');
        } catch (\RuntimeException $e) {
        }

        $menu->addItems([$item = new SelectableItem('Selectable', function () {
        })]);

        self::assertEquals($item, $menu->getSelectedItem());
    }

    public function testSetItemsReSelectsFirstSelectableItem() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new StaticItem('No Selectable'));
        $menu->addItem($item = new SelectableItem('Selectable', function () {
        }));

        self::assertEquals($item, $menu->getSelectedItem());

        $menu->setItems([$item2 = new SelectableItem('Selectable', function () {
        })]);

        self::assertEquals($item2, $menu->getSelectedItem());
    }

    public function testRemoveItemReSelectsFirstSelectableItemIfSelectedItemRemoved() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new StaticItem('No Selectable'));
        $menu->addItem($item = new SelectableItem('Selectable', function () {
        }));

        self::assertEquals($item, $menu->getSelectedItem());

        $menu->removeItem($item);

        try {
            $menu->getSelectedItem();
            $this->fail('Exception not thrown');
        } catch (\RuntimeException $e) {
        }

        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new StaticItem('No Selectable'));
        $menu->addItem($item1 = new SelectableItem('Selectable', function () {
        }));
        $menu->addItem($item2 = new SelectableItem('Selectable', function () {
        }));

        self::assertEquals($item1, $menu->getSelectedItem());

        $menu->removeItem($item1);

        self::assertEquals($item2, $menu->getSelectedItem());
    }

    public function testGetSelectedItemThrowsExceptionIfNoSelectedItem() : void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('No selected item');

        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem(new StaticItem('No Selectable'));
        $menu->getSelectedItem();
    }

    public function testMenuCanOpenAndFunctionWithoutAnySelectableItems() : void
    {
        $this->terminal->expects($this->exactly(3))
            ->method('read')
            ->willReturn("\033[B", "\033[B", 'Q');
        $menu = new CliMenu('PHP School FTW', [new StaticItem('One')], $this->terminal);
        $menu->addCustomControlMapping('Q', function (CliMenu $menu) {
            $menu->close();
        });
        $menu->open();

        self::assertCount(1, $menu->getItems());
    }

    public function testSetSelectedItemThrowsExceptionIfItemDoesNotExistInMenu() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem($item1 = new SelectableItem('Selectable 1', function () {
        }));
        $menu->addItem($item2 = new SelectableItem('Selectable 2', function () {
        }));

        $item3 = new SelectableItem('Selectable 2', function () {
        });

        $this->expectException(\InvalidArgumentException::class, 'Item does not exist in menu');

        $menu->setSelectedItem($item3);
    }

    public function testSetSelectedItem() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem($item1 = new SelectableItem('Selectable 1', function () {
        }));
        $menu->addItem($item2 = new SelectableItem('Selectable 2', function () {
        }));

        $menu->setSelectedItem($item2);

        self::assertSame($item2, $menu->getSelectedItem());
    }

    public function testGetSelectedItemIndexThrowsExceptionIfNoItemSelected() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);

        $this->expectException(\RuntimeException::class, 'No selected item');
        $menu->getSelectedItemIndex();
    }

    public function testGetSelectedItemIndex() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);
        $menu->addItem($item1 = new SelectableItem('Selectable 1', function () {
        }));
        $menu->addItem($item2 = new SelectableItem('Selectable 2', function () {
        }));

        $menu->setSelectedItem($item2);

        self::assertSame(1, $menu->getSelectedItemIndex());
    }

    public function testGetItemByIndexThrowsExceptionIfItemDoesNotExistInMenu() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);

        $this->expectException(\RuntimeException::class, 'Item with index does not exist');
        $menu->getItemByIndex(3);
    }

    public function testGetItemByIndex() : void
    {
        $menu = new CliMenu('PHP School FTW', [], $this->terminal);

        $menu->addItem($item1 = new SelectableItem('Selectable 1', function () {
        }));
        $menu->addItem($item2 = new SelectableItem('Selectable 2', function () {
        }));

        $menu->setSelectedItem($item2);

        self::assertSame($item2, $menu->getItemByIndex(1));
    }

    private function getTestFile() : string
    {
        return sprintf('%s/res/%s.txt', __DIR__, $this->getName());
    }

    private function getStyle(Terminal $terminal) : MenuStyle
    {
        return new MenuStyle($terminal);
    }
}
