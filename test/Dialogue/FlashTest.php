<?php

namespace PhpSchool\CliMenuTest\Dialogue;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\IO\BufferedOutput;
use PhpSchool\Terminal\Terminal;
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

    public function testFlashWithOddLength() : void
    {
        $this->terminal
            ->method('read')
            ->will($this->onConsecutiveCalls(
                "\n",
                "\n"
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->flash('PHP School FTW!')
                ->display();

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testFlashWithEvenLength() : void
    {
        $this->terminal
            ->method('read')
            ->will($this->onConsecutiveCalls(
                "\n",
                "\n"
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->flash('PHP School FTW')
                ->display();

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    /**
     * @dataProvider keyProvider
     */
    public function testFlashCanBeClosedWithAnyKey(string $key) : void
    {
        $this->terminal
            ->method('read')
            ->will($this->onConsecutiveCalls("\n", $key));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->flash('PHP School FTW!')
                ->display();

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function keyProvider() : array
    {
        return [
            ["\n"],
            ['right'],
            ['down'],
            ['up'],
        ];
    }

    private function getTestFile() : string
    {
        return sprintf('%s/../res/%s.txt', __DIR__, $this->getName(false));
    }

    private function getStyle(Terminal $terminal) : MenuStyle
    {
        return new MenuStyle($terminal);
    }
}
