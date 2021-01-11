<?php

namespace PhpSchool\CliMenuTest\Input;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Input\InputIO;
use PhpSchool\CliMenu\Input\Text;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\InputCharacter;
use PhpSchool\Terminal\IO\BufferedOutput;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InputIOTest extends TestCase
{
    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @var BufferedOutput
     */
    private $output;

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

    public function setUp() : void
    {
        $this->terminal = $this->createMock(Terminal::class);
        $this->terminal
            ->method('getWidth')
            ->willReturn(100);

        $this->output   = new BufferedOutput;
        $this->menu     = $this->createMock(CliMenu::class);
        $this->style    = new MenuStyle($this->terminal);
        $this->inputIO  = new InputIO($this->menu, $this->terminal);

        $this->style->setBg('yellow');
        $this->style->setFg('red');

        $parentStyle = new MenuStyle($this->terminal);
        $parentStyle->setBg('blue');

        $this->menu
            ->expects($this->any())
            ->method('getStyle')
            ->willReturn($parentStyle);
    }

    public function testEnterReturnsOutputIfValid() : void
    {
        $this->terminal
            ->expects($this->exactly(2))
            ->method('read')
            ->willReturn('1', "\n");

        $result = $this->inputIO->collect(new Text($this->inputIO, $this->style));

        self::assertEquals('1', $result->fetch());

        echo $this->output->fetch();
    }

    public function testCustomControlFunctions() : void
    {
        $this->inputIO->registerControlCallback(InputCharacter::UP, function ($input) {
            return ++$input;
        });

        $this->terminal
            ->expects($this->exactly(4))
            ->method('read')
            ->willReturn('1', '0', "\033[A", "\n");

        $result = $this->inputIO->collect(new Text($this->inputIO, $this->style));

        self::assertEquals('11', $result->fetch());
    }

    public function testBackspaceDeletesPreviousCharacter() : void
    {
        $this->terminal
            ->expects($this->exactly(6))
            ->method('read')
            ->willReturn('1', '6', '7', "\177", "\177", "\n");

        $result = $this->inputIO->collect(new Text($this->inputIO, $this->style));

        self::assertEquals('1', $result->fetch());
    }

    public function testValidationErrorCausesErrorMessageToBeDisplayed() : void
    {
        $input = new class ($this->inputIO, $this->style) extends Text {
            public function validate(string $input) : bool
            {
                return $input[-1] === 'p';
            }
        };

        $this->terminal
            ->expects($this->exactly(6))
            ->method('read')
            ->willReturn('1', 't', "\n", "\177", 'p', "\n");

        $result = $this->inputIO->collect($input);

        self::assertEquals('1p', $result->fetch());
    }
}
