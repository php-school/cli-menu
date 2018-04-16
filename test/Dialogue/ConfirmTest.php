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
class ConfirmTest extends TestCase
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

    public function testConfirmWithOddLengthConfirmAndButton() : void
    {
        $this->terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls(
                'enter',
                'enter'
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW!')
                ->display('OK!');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    public function testConfirmWithEvenLengthConfirmAndButton() : void
    {
        $this->terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls(
                'enter',
                'enter'
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW')
                ->display('OK');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    public function testConfirmWithEvenLengthConfirmAndOddLengthButton() : void
    {
        $this->terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls(
                'enter',
                'enter'
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW')
                ->display('OK!');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    public function testConfirmWithOddLengthConfirmAndEvenLengthButton() : void
    {
        $this->terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls(
                'enter',
                'enter'
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW!')
                ->display('OK');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    public function testConfirmCanOnlyBeClosedWithEnter() : void
    {
        $this->terminal
            ->method('getKeyedInput')
            ->will($this->onConsecutiveCalls(
                'enter',
                'up',
                'down',
                'enter'
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW!')
                ->display('OK');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertEquals($this->output->fetch(), file_get_contents($this->getTestFile()));
    }

    private function getTestFile() : string
    {
        return sprintf('%s/../res/%s.txt', __DIR__, $this->getName());
    }

    private function getStyle(TerminalInterface $terminal) : MenuStyle
    {
        return new MenuStyle($terminal);
    }
}
