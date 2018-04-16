<?php

namespace PhpSchool\CliMenuTest\Dialogue;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class FlashTest extends TestCase
{
    public function testFlashWithOddLength()
    {
        $terminal = $this->createMock(TerminalInterface::class);

        $terminal->expects($this->any())
            ->method('isTTY')
            ->willReturn(true);

        $terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls(
                'enter',
                'enter'
            ));

        $terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(50);

        $style = $this->getStyle($terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->flash('PHP School FTW!')
                ->display();

            $menu->close();
        });

        $this->expectOutputString(file_get_contents($this->getTestFile()));

        $menu = new CliMenu('PHP School FTW', [$item], $terminal, $style);
        $menu->open();
    }

    public function testFlashWithEvenLength()
    {
        $terminal = $this->createMock(TerminalInterface::class);

        $terminal->expects($this->any())
            ->method('isTTY')
            ->willReturn(true);

        $terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls(
                'enter',
                'enter'
            ));

        $terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(50);

        $style = $this->getStyle($terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->flash('PHP School FTW')
                ->display();

            $menu->close();
        });

        $this->expectOutputString(file_get_contents($this->getTestFile()));

        $menu = new CliMenu('PHP School FTW', [$item], $terminal, $style);
        $menu->open();
    }

    /**
     * @dataProvider keyProvider
     * @param string $key
     */
    public function testFlashCanBeClosedWithAnyKey($key)
    {
        $terminal = $this->createMock(TerminalInterface::class);

        $terminal->expects($this->any())
            ->method('isTTY')
            ->willReturn(true);

        $terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls('enter', $key));

        $terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(50);

        $style = $this->getStyle($terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->flash('PHP School FTW!')
                ->display();

            $menu->close();
        });

        $this->expectOutputString(file_get_contents($this->getTestFile()));

        $menu = new CliMenu('PHP School FTW', [$item], $terminal, $style);
        $menu->open();
    }

    /**
     * @return array
     */
    public function keyProvider()
    {
        return [
            ['enter'],
            ['right'],
            ['down'],
            ['up'],
        ];
    }

    /**
     * @return string
     */
    private function getTestFile()
    {
        return sprintf('%s/../res/%s.txt', __DIR__, $this->getName(false));
    }

    /**
     * @param TerminalInterface $terminal
     * @return MenuStyle
     */
    private function getStyle(TerminalInterface $terminal)
    {
        return new MenuStyle($terminal);
    }
}
