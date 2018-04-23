<?php

namespace PhpSchool\CliMenuTest\Input;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Input\InputIO;
use PhpSchool\CliMenu\Input\Password;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class PasswordTest extends TestCase
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

    public function setUp()
    {
        $this->terminal = $this->createMock(Terminal::class);
        $menu           = $this->createMock(CliMenu::class);
        $style          = $this->createMock(MenuStyle::class);

        $this->inputIO  = new InputIO($menu, $this->terminal);
        $this->input    = new Password($this->inputIO, $style);
    }

    public function testGetSetPromptText() : void
    {
        static::assertEquals('Enter password:', $this->input->getPromptText());

        $this->input->setPromptText('Password please:');
        static::assertEquals('Password please:', $this->input->getPromptText());
    }

    public function testGetSetValidationFailedText() : void
    {
        static::assertEquals('Invalid password, try again', $this->input->getValidationFailedText());

        $this->input->setValidationFailedText('Failed!');
        static::assertEquals('Failed!', $this->input->getValidationFailedText());
    }

    public function testGetSetPlaceholderText() : void
    {
        static::assertEquals('', $this->input->getPlaceholderText());

        $this->input->setPlaceholderText('***');
        static::assertEquals('***', $this->input->getPlaceholderText());
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
            ['10', false],
            ['mypassword', false],
            ['pppppppppppppppp', true],
        ];
    }

    public function testFilterConcealsPassword() : void
    {
        static::assertEquals('****', $this->input->filter('pppp'));
    }

    public function testAskPassword() : void
    {
        $this->terminal
            ->expects($this->exactly(17))
            ->method('read')
            ->willReturn('1', '2', '3', '4', '5', '6', '7', '8', '9', '1', '2', '3', '4', '5', '6', '7', "\n");

        self::assertEquals('1234567891234567', $this->input->ask()->fetch());
    }

    /**
     * @dataProvider customValidateProvider
     */
    public function testValidateWithCustomValidator(string $value, bool $result) : void
    {
        $customValidate = function ($input) {
              return preg_match('/\d/', $input) && preg_match('/[a-zA-Z]/', $input);
        };

        $this->input->setValidator($customValidate);

        static::assertEquals($this->input->validate($value), $result);
    }

    public function customValidateProvider() : array
    {
        return [
            ['10', false],
            ['mypassword', false],
            ['pppppppppppppppp', false],
            ['1t', true],
            ['999ppp', true],
        ];
    }
}
