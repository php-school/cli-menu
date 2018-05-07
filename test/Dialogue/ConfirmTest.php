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

    public function testConfirmWithOddLengthConfirmAndButton() : void
    {
        $this->terminal
            ->method('read')
            ->will($this->onConsecutiveCalls(
                "\n",
                "\n"
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW!')
                ->display('OK!');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testConfirmWithEvenLengthConfirmAndButton() : void
    {
        $this->terminal
            ->method('read')
            ->will($this->onConsecutiveCalls(
                "\n",
                "\n"
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW')
                ->display('OK');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testConfirmWithEvenLengthConfirmAndOddLengthButton() : void
    {
        $this->terminal
            ->method('read')
            ->will($this->onConsecutiveCalls(
                "\n",
                "\n"
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW')
                ->display('OK!');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testConfirmWithOddLengthConfirmAndEvenLengthButton() : void
    {
        $this->terminal
            ->method('read')
            ->will($this->onConsecutiveCalls(
                "\n",
                "\n"
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW!')
                ->display('OK');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testConfirmCanOnlyBeClosedWithEnter() : void
    {
        $this->terminal
            ->method('read')
            ->will($this->onConsecutiveCalls(
                "\n",
                'up',
                'down',
                "\n"
            ));

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->confirm('PHP School FTW!')
                ->display('OK');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    private function getTestFile() : string
    {
        return sprintf('%s/../res/%s.txt', __DIR__, $this->getName());
    }

    private function getStyle(Terminal $terminal) : MenuStyle
    {
        return new MenuStyle($terminal);
    }
}
