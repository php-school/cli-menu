<?php

namespace PhpSchool\CliMenuTest;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\MenuNotOpenException;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
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

    public function setUp()
    {
        $this->output = new BufferedOutput;
        $this->terminal = $this->createMock(Terminal::class);

        $this->terminal->expects($this->any())
            ->method('isInteractive')
            ->willReturn(true);

        $this->terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(50);

        $this->terminal->expects($this->any())
            ->method('write')
            ->will($this->returnCallback(function ($buffer) {
                $this->output->write($buffer);
            }));
    }

    public function testGetMenuStyle() : void
    {
        $menu = new CliMenu('PHP School FTW', []);
        static::assertInstanceOf(MenuStyle::class, $menu->getStyle());

        $style = new MenuStyle();
        $menu = new CliMenu('PHP School FTW', [], null, $style);
        static::assertSame($style, $menu->getStyle());
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

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
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

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testGetItems() : void
    {
        $item1 = new LineBreakItem();
        $item2 = new LineBreakItem();


        $terminal = $this->createMock(Terminal::class);
        $style = $this->getStyle($terminal);

        $menu = new CliMenu(
            'PHP School FTW',
            [
                $item1,
                $item2
            ],
            $terminal,
            $style
        );

        static::assertSame([$item1, $item2], $menu->getItems());
    }

    public function testRemoveItem() : void
    {
        $item1 = new LineBreakItem();
        $item2 = new LineBreakItem();

        $terminal = $this->createMock(Terminal::class);
        $style = $this->getStyle($terminal);

        $menu = new CliMenu(
            'PHP School FTW',
            [
                $item1,
                $item2
            ],
            $terminal,
            $style
        );

        static::assertEquals([$item1, $item2], $menu->getItems());

        $menu->removeItem($item1);

        static::assertCount(1, $menu->getItems());
        static::assertContains($item2, $menu->getItems());
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
        $terminal->expects($this->once())
            ->method('isInteractive')
            ->willReturn(false);

        $menu = new CliMenu('PHP School FTW', [], $terminal);

        $menu->open();
    }

    public function testGetTerminal() : void
    {
        $menu = new CliMenu('PHP School FTW', []);
        static::assertInstanceOf(UnixTerminal::class, $menu->getTerminal());
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

        static::expectException(MenuNotOpenException::class);

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

        static::assertEquals('yellow', $number->getStyle()->getBg());
        static::assertEquals('red', $number->getStyle()->getFg());
    }

    public function testAskTextThrowsExceptionIfMenuNotOpen() : void
    {
        $menu = new CliMenu('PHP School FTW', []);

        static::expectException(MenuNotOpenException::class);

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

        static::assertEquals('yellow', $text->getStyle()->getBg());
        static::assertEquals('red', $text->getStyle()->getFg());
    }

    public function testAskPasswordThrowsExceptionIfMenuNotOpen() : void
    {
        $menu = new CliMenu('PHP School FTW', []);

        static::expectException(MenuNotOpenException::class);

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

        static::assertEquals('yellow', $password->getStyle()->getBg());
        static::assertEquals('red', $password->getStyle()->getFg());
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

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
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
        self::assertSame(['c' => $action], $this->readAttribute($menu, 'customControlMappings'));
        
        $menu->removeCustomControlMapping('c');
        self::assertSame([], $this->readAttribute($menu, 'customControlMappings'));
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
