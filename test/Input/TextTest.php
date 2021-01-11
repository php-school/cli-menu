<?php

namespace PhpSchool\CliMenuTest\Input;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Input\InputIO;
use PhpSchool\CliMenu\Input\Text;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class TextTest extends TestCase
{
    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @var InputIO
     */
    private $inputIO;

    /**
     * @var Text
     */
    private $input;

    public function setUp() : void
    {
        $this->terminal = $this->createMock(Terminal::class);
        $menu           = $this->createMock(CliMenu::class);
        $style          = $this->createMock(MenuStyle::class);

        $this->inputIO  = new InputIO($menu, $this->terminal);
        $this->input    = new Text($this->inputIO, $style);
    }

    public function testGetSetPromptText() : void
    {
        static::assertEquals('Enter text:', $this->input->getPromptText());

        $this->input->setPromptText('Text please:');
        static::assertEquals('Text please:', $this->input->getPromptText());
    }

    public function testGetSetValidationFailedText() : void
    {
        static::assertEquals('Invalid, try again', $this->input->getValidationFailedText());

        $this->input->setValidationFailedText('Failed!');
        static::assertEquals('Failed!', $this->input->getValidationFailedText());
    }

    public function testGetSetPlaceholderText() : void
    {
        static::assertEquals('', $this->input->getPlaceholderText());

        $this->input->setPlaceholderText('My Title');
        static::assertEquals('My Title', $this->input->getPlaceholderText());
    }

    /**
     * @dataProvider validateProvider
     */
    public function testValidate(string $value, bool $result) : void
    {
        static::assertEquals($this->input->validate($value), $result);
    }

    public function validateProvider() : array
    {
        return [
            ['', false],
            ['some text', true],
            ['some more text', true],
        ];
    }

    public function testFilterReturnsInputAsIs() : void
    {
        static::assertEquals('9999', $this->input->filter('9999'));
    }

    public function testAskText() : void
    {
        $this->terminal
            ->expects($this->exactly(10))
            ->method('read')
            ->willReturn('s', 'o', 'm', 'e', ' ', 't', 'e', 'x', 't', "\n");

        self::assertEquals('some text', $this->input->ask()->fetch());
    }
}
