<?php

declare(strict_types=1);

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

    public function setUp(): void
    {
        $this->output = new BufferedOutput();
        $this->terminal = $this->createMock(Terminal::class);

        $this->terminal->expects($this->any())
            ->method('isInteractive')
            ->willReturn(true);

        $this->terminal->expects($this->any())
            ->method('getWidth')
            ->willReturn(48);

        $this->terminal->expects($this->any())
            ->method('write')
            ->willReturnCallback(function ($buffer) {
                $this->output->write($buffer);
            });
    }

    public function testConfirmWithOddLengthConfirmAndButton(): void
    {
        $this->terminal
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                "\n",
                "\n"
            );

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

    public function testConfirmWithEvenLengthConfirmAndButton(): void
    {
        $this->terminal
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                "\n",
                "\n"
            );

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

    public function testConfirmWithEvenLengthConfirmAndOddLengthButton(): void
    {
        $this->terminal
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                "\n",
                "\n"
            );

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

    public function testConfirmWithOddLengthConfirmAndEvenLengthButton(): void
    {
        $this->terminal
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                "\n",
                "\n"
            );

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

    public function testConfirmCancellableWithShortPrompt(): void
    {
        $this->terminal
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                "\n",
                "\n"
            );

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->cancellableConfirm('PHP', null, true)
                ->display('OK', 'Cancel');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testConfirmCancellableWithLongPrompt(): void
    {
        $this->terminal
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                "\n",
                "\n"
            );

        $style = $this->getStyle($this->terminal);

        $item = new SelectableItem('Item 1', function (CliMenu $menu) {
            $menu->cancellableConfirm('PHP School Rocks FTW!', null, true)
                ->display('OK', 'Cancel');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertStringEqualsFile($this->getTestFile(), $this->output->fetch());
    }

    public function testConfirmCanOnlyBeClosedWithEnter(): void
    {
        $this->terminal
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                "\n",
                'up',
                'down',
                "\n"
            );

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

    public function testConfirmOkNonCancellableReturnsTrue()
    {
        $this->terminal
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                "\n",
                'tab',
                "\n"
            );

        $style = $this->getStyle($this->terminal);

        $return = '';

        $item = new SelectableItem('Item 1', function (CliMenu $menu) use (&$return) {
            $return = $menu->cancellableConfirm('PHP School FTW!')
                ->display('OK');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertTrue($return);
    }

    public function testConfirmOkCancellableReturnsTrue()
    {
        $this->terminal
            ->method('read')
            ->willReturn("\n", "\t", "\t", "\n");

        $style = $this->getStyle($this->terminal);

        $return = '';

        $item = new SelectableItem('Item 1', function (CliMenu $menu) use (&$return) {
            $return = $menu->cancellableConfirm('PHP School FTW!')
                ->display('OK', 'Cancel');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertTrue($return);
    }

    public function testConfirmCancelCancellableReturnsFalse()
    {
        $this->terminal
            ->method('read')
            ->willReturn("\n", "\t", "\n");

        $style = $this->getStyle($this->terminal);

        $return = '';

        $item = new SelectableItem('Item 1', function (CliMenu $menu) use (&$return) {
            $return = $menu->cancellableConfirm('PHP School FTW!', null)
                ->display('OK', 'Cancel');

            $menu->close();
        });

        $menu = new CliMenu('PHP School FTW', [$item], $this->terminal, $style);
        $menu->open();

        static::assertFalse($return);
    }

    private function getTestFile(): string
    {
        return sprintf('%s/../res/%s.txt', __DIR__, $this->name());
    }

    private function getStyle(Terminal $terminal): MenuStyle
    {
        return new MenuStyle($terminal);
    }
}
