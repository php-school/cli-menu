<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Input;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Input\InputIO;
use PhpSchool\CliMenu\Input\Number;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class NumberTest extends TestCase
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
     * @var Number
     */
    private $input;

    public function setUp(): void
    {
        $this->terminal = $this->createMock(Terminal::class);
        $menu           = $this->createMock(CliMenu::class);
        $style          = $this->createMock(MenuStyle::class);

        $this->inputIO  = new InputIO($menu, $this->terminal);
        $this->input    = new Number($this->inputIO, $style);
    }

    public function testGetSetPromptText(): void
    {
        static::assertEquals('Enter a number:', $this->input->getPromptText());

        $this->input->setPromptText('Number please:');
        static::assertEquals('Number please:', $this->input->getPromptText());
    }

    public function testGetSetValidationFailedText(): void
    {
        static::assertEquals('Not a valid number, try again', $this->input->getValidationFailedText());

        $this->input->setValidationFailedText('Failed!');
        static::assertEquals('Failed!', $this->input->getValidationFailedText());
    }

    public function testGetSetPlaceholderText(): void
    {
        static::assertEquals('', $this->input->getPlaceholderText());

        $this->input->setPlaceholderText('some placeholder text');
        static::assertEquals('some placeholder text', $this->input->getPlaceholderText());
    }

    #[DataProvider('validateProvider')]
    public function testValidate(string $value, bool $result): void
    {
        static::assertEquals($this->input->validate($value), $result);
    }

    public static function validateProvider(): array
    {
        return [
            ['10', true],
            ['10t', false],
            ['t10', false],
            ['0', true],
            ['0000000000', true],
            ['9999999999', true],
            ['-9999999999', true],
            ['-54', true],
            ['-1', true],
            ['-t10', false],
            ['-t', false],
        ];
    }

    public function testFilterReturnsInputAsIs(): void
    {
        static::assertEquals('9999', $this->input->filter('9999'));
    }

    public function testUpKeyIncrementsNumber(): void
    {
        $this->terminal
            ->expects($this->exactly(4))
            ->method('read')
            ->willReturn('1', '0', "\033[A", "\n");

        self::assertEquals(11, $this->input->ask()->fetch());
    }

    public function testDownKeyDecrementsNumber(): void
    {
        $this->terminal
            ->expects($this->exactly(4))
            ->method('read')
            ->willReturn('1', '0', "\033[B", "\n");

        self::assertEquals(9, $this->input->ask()->fetch());
    }
}
