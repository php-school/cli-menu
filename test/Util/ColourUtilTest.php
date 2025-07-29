<?php
declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Util;

use PhpSchool\CliMenu\Util\ColourUtil;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ColourUtilTest extends TestCase
{
    public function testAvailableColours() : void
    {
        self::assertSame(
            [
                'black',
                'red',
                'green',
                'yellow',
                'blue',
                'magenta',
                'cyan',
                'white',
                'default'
            ],
            ColourUtil::getDefaultColourNames()
        );
    }

    public function testMap256To8ThrowsExceptionIfCodeNotValid() : void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid colour code');
        
        ColourUtil::map256To8(512);
    }

    public function testMap256To8() : void
    {
        self::assertEquals('white', ColourUtil::map256To8(255));
        self::assertEquals('magenta', ColourUtil::map256To8(213));
        self::assertEquals('yellow', ColourUtil::map256To8(143));
        self::assertEquals('blue', ColourUtil::map256To8(103));
        self::assertEquals('green', ColourUtil::map256To8(64));
    }

    public function testValidateColourThrowsExceptionIfColourNot256AndNot8() : void
    {
        self::expectException(\Assert\InvalidArgumentException::class);
        
        $terminal = $this->createMock(Terminal::class);

        ColourUtil::validateColour($terminal, 'teal');
    }
    
    public function testValidateColourThrowsExceptionIfFallbackNotValidWhenTerminalDoesNotSupport256Colours() : void
    {
        self::expectException(\Assert\InvalidArgumentException::class);

        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->once())
            ->method('getColourSupport')
            ->willReturn(8);

        ColourUtil::validateColour($terminal, '255', 'teal');
    }

    public function testValidateColourWithFallbackWhenTerminalDoesNotSupport256Colours() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->once())
            ->method('getColourSupport')
            ->willReturn(8);

        self::assertEquals('red', ColourUtil::validateColour($terminal, '255', 'red'));
    }

    public function testValidateColourPicksFallbackFromPreComputedListWhenTerminalDoesNotSupport256Colours() : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->once())
            ->method('getColourSupport')
            ->willReturn(8);

        self::assertEquals('yellow', ColourUtil::validateColour($terminal, '148'));
    }

    #[DataProvider('invalidColourCodeProvider')]
    public function testValidateColourThrowsExceptionIfInvalid256ColourCodeUsed(string $colourCode) : void
    {
        self::expectException(\Assert\InvalidArgumentException::class);

        ColourUtil::validateColour($this->createMock(Terminal::class), $colourCode);
    }

    public static function invalidColourCodeProvider() : array
    {
        return [
            ['-1'],
            ['256'],
            ['1000'],
        ];
    }

    #[DataProvider('validColourCodeProvider')]
    public function testValidateColourWith256ColoursWhenTerminalSupports256Colours(string $colourCode) : void
    {
        $terminal = $this->createMock(Terminal::class);
        $terminal->expects($this->once())
            ->method('getColourSupport')
            ->willReturn(256);
        
        self::assertEquals($colourCode, ColourUtil::validateColour($terminal, $colourCode));
    }

    public static function validColourCodeProvider() : array
    {
        return [
            ['0'],
            ['255'],
            ['1'],
            ['100'],
        ];
    }

    public function testValidateColourWithValid8ColourName() : void
    {
        self::assertEquals('red', ColourUtil::validateColour($this->createMock(Terminal::class), 'red'));
    }
}
