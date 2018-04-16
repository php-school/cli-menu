<?php

namespace PhpSchool\CliMenuTest\Input;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Input\InputIO;
use PhpSchool\CliMenu\Input\Text;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InputIOTest extends TestCase
{
    /**
     * @var TerminalInterface
     */
    private $terminal;

    /**
     * @var CliMenu
     */
    private $menu;

    /**
     * @var MenuStyle
     */
    private $style;

    /**
     * @var InputIO
     */
    private $inputIO;

    public function setUp()
    {
        $this->terminal = $this->createMock(TerminalInterface::class);
        $this->menu     = $this->createMock(CliMenu::class);
        $this->style    = $this->createMock(MenuStyle::class);
        $this->inputIO  = new InputIO($this->menu, $this->style, $this->terminal);
    }

    public function testEnterReturnsOutputIfValid() : void
    {
        $this->terminal
            ->expects($this->exactly(2))
            ->method('getKeyedInput')
            ->willReturn('1', 'enter');

        $result = $this->inputIO->collect(new Text($this->inputIO));

        self::assertEquals('1', $result->fetch());
    }

    public function testCustomControlFunctions() : void
    {
        $this->inputIO->registerControlCallback('u', function ($input) {
            return ++$input;
        });

        $this->terminal
            ->expects($this->exactly(4))
            ->method('getKeyedInput')
            ->with(["\n" => 'enter', "\r" => 'enter', "\177" => 'backspace', 'u' => 'u'])
            ->willReturn('1', '0', 'u', 'enter');

        $result = $this->inputIO->collect(new Text($this->inputIO));

        self::assertEquals('11', $result->fetch());
    }

    public function testBackspaceDeletesPreviousCharacter() : void
    {
        $this->terminal
            ->expects($this->exactly(6))
            ->method('getKeyedInput')
            ->willReturn('1', '6', '7', 'backspace', 'backspace', 'enter');

        $result = $this->inputIO->collect(new Text($this->inputIO));

        self::assertEquals('1', $result->fetch());
    }

    public function testValidationErrorCausesErrorMessageToBeDisplayed() : void
    {
        $input = new class ($this->inputIO) extends Text {
            public function validate(string $input) : bool
            {
                return $input[-1] === 'p';
            }
        };

        $this->terminal
            ->expects($this->exactly(6))
            ->method('getKeyedInput')
            ->willReturn('1', 't', 'enter', 'backspace', 'p', 'enter');

        $result = $this->inputIO->collect($input);

        self::assertEquals('1p', $result->fetch());
    }
}
