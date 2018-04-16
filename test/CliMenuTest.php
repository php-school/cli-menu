<?php

namespace PhpSchool\CliMenuTest;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\MenuNotOpenException;
use PhpSchool\CliMenu\IO\BufferedOutput;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use PhpSchool\CliMenu\Terminal\UnixTerminal;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class CliMenuTest extends TestCase
{
    /**
     * @var TerminalInterface
     */
    private $terminal;

    /**
     * @var BufferedOutput
     */
    private $output;

    public function setUp()
    {
        $this->output = new BufferedOutput;
        $this->terminal = $this->createMock(TerminalInterface::class);
        $this->terminal->expects($this->any())
            ->method('getOutput')
            ->willReturn($this->output);

        $this->terminal->expects($this->any())
            ->method('isTTY')
            ->willReturn(true);

        $this->terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(50);
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
            ->method('getKeyedInput')
            ->willReturn('enter');

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    public function testReDrawReDrawsImmediately() : void
    {
        $this->terminal->expects($this->once())
            ->method('getKeyedInput')
            ->willReturn('enter');

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->getStyle()->setBg('red');
            $menu->redraw();
            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    public function testGetItems() : void
    {
        $item1 = new LineBreakItem();
        $item2 = new LineBreakItem();


        $terminal = $this->createMock(TerminalInterface::class);
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

        $terminal = $this->createMock(TerminalInterface::class);
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

        $terminal = $this->createMock(TerminalInterface::class);
        $terminal->expects($this->once())
            ->method('isTTY')
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

    private function getTestFile() : string
    {
        return sprintf('%s/res/%s.txt', __DIR__, $this->getName());
    }

    private function getStyle(TerminalInterface $terminal) : MenuStyle
    {
        return new MenuStyle($terminal);
    }
}
