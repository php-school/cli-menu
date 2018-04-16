<?php

namespace PhpSchool\CliMenuTest\Dialogue;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\IO\BufferedOutput;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class FlashTest extends TestCase
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

    public function testFlashWithOddLength() : void
    {
        $this->terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls(
                'enter',
                'enter'
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->flash('PHP School FTW!')
                ->display();

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    public function testFlashWithEvenLength() : void
    {
        $this->terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls(
                'enter',
                'enter'
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->flash('PHP School FTW')
                ->display();

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    /**
     * @dataProvider keyProvider
     */
    public function testFlashCanBeClosedWithAnyKey(string $key) : void
    {
        $this->terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls('enter', $key));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->flash('PHP School FTW!')
                ->display();

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    public function keyProvider() : array
    {
        return [
            ['enter'],
            ['right'],
            ['down'],
            ['up'],
        ];
    }

    private function getTestFile() : string
    {
        return sprintf('%s/../res/%s.txt', __DIR__, $this->getName(false));
    }

    private function getStyle(TerminalInterface $terminal) : MenuStyle
    {
        return new MenuStyle($terminal);
    }
}
