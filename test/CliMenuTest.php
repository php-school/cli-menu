<?php

namespace PhpSchool\CliMenuTest;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\MenuNotOpenException;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use PHPUnit_Framework_TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class CliMenuTest extends PHPUnit_Framework_TestCase
{
    public function testGetMenuStyle()
    {
        $menu = new CliMenu('PHP School FTW', []);
        static::assertInstanceOf(MenuStyle::class, $menu->getStyle());

        $style = new MenuStyle();
        $menu = new CliMenu('PHP School FTW', [], null, $style);
        static::assertSame($style, $menu->getStyle());
    }

    public function testReDrawThrowsExceptionIfMenuNotOpen()
    {
        $menu = new CliMenu('PHP School FTW', []);

        $this->expectException(MenuNotOpenException::class);

        $menu->reDraw();
    }

    public function testSimpleOpenClose()
    {
        $terminal = $this->createMock(TerminalInterface::class);

        $terminal->expects($this->any())
            ->method('isTTY')
            ->willReturn(true);

        $terminal->expects($this->once())
            ->method('getKeyedInput')
            ->willReturn('enter');

        $terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(50);

        $style = $this->getStyle($terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->close();
        });

        $this->expectOutputString(file_get_contents($this->getTestFile()));

        $menu = new CliMenu('PHP School FTW', [$item], $terminal, $style);
        $menu->open();
    }

    public function testReDrawReDrawsImmediately()
    {
        $terminal = $this->createMock(TerminalInterface::class);

        $terminal->expects($this->any())
            ->method('isTTY')
            ->willReturn(true);

        $terminal->expects($this->once())
            ->method('getKeyedInput')
            ->willReturn('enter');

        $terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(50);

        $style = $this->getStyle($terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->getStyle()->setBg('red');
            $menu->redraw();
            $menu->close();
        });

        $this->expectOutputString(file_get_contents($this->getTestFile()));

        $menu = new CliMenu('PHP School FTW', [$item], $terminal, $style);
        $menu->open();
    }

    /**
     * @return string
     */
    private function getTestFile()
    {
        return sprintf('%s/res/%s.txt', __DIR__, $this->getName());
    }

    /**
     * @param TerminalInterface $terminal
     * @return MenuStyle
     */
    private function getStyle(TerminalInterface $terminal)
    {
        return new MenuStyle(
            'blue',
            'white',
            100,
            2,
            2,
            '○',
            '●',
            '✔',
            false,
            '=',
            $terminal
        );
    }
}
