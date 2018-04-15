<?php

namespace PhpSchool\CliMenuTest;

use PhpSchool\CliMenu\CliMenuBuilder;
use PhpSchool\CliMenu\Exception\InvalidInstantiationException;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Terminal\UnixTerminal;
use PHPUnit\Framework\TestCase;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class MenuStyleTest extends TestCase
{
    /**
     * @return MenuStyle
     */
    private function getMenuStyle()
    {
        // Use the CliMenuBuilder & reflection to get the style Obj
        $builder = new CliMenuBuilder();
        $menu    = $builder->build();

        $reflectionMenu = new \ReflectionObject($menu);
        $styleProperty  = $reflectionMenu->getProperty('style');
        $styleProperty->setAccessible(true);
        $style = $styleProperty->getValue($menu);

        $reflectionStyle  = new \ReflectionObject($style);
        $terminalProperty = $reflectionStyle->getProperty('terminal');
        $terminalProperty->setAccessible(true);
        $terminalProperty->setValue($style, $this->getMockTerminal());

        // Force recalculate terminal widths now terminal is set
        $style->setWidth(100);
        
        return $style;
    }

    private function getMockTerminal()
    {
        $terminal = $this
            ->getMockBuilder(UnixTerminal::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWidth'])
            ->getMock();

        $terminal
            ->expects(static::any())
            ->method('getWidth')
            ->will(static::returnValue(500));

        return $terminal;
    }

    public function testMenuStyleCanBeInstantiatedByCliMenuBuilder()
    {
        $builder = new CliMenuBuilder();
        $menu    = $builder->build();

        $reflectionMenu = new \ReflectionObject($menu);
        static::assertTrue($reflectionMenu->hasProperty('style'));

        $styleProperty = $reflectionMenu->getProperty('style');
        $styleProperty->setAccessible(true);
        $style = $styleProperty->getValue($menu);

        static::assertSame(MenuStyle::class, get_class($style));
    }

    public function testAvailableColours()
    {
        static::assertSame([
            'black',
            'red',
            'green',
            'yellow',
            'blue',
            'magenta',
            'cyan',
            'white',
            'default'
        ], MenuStyle::getAvailableColours());
    }

    public function testGetSelectedSetCode()
    {
        static::assertSame("\e[47;34m", $this->getMenuStyle()->getSelectedSetCode());
    }

    public function testGetSelectedUnsetCode()
    {
        static::assertSame("\e[49;39m", $this->getMenuStyle()->getSelectedUnsetCode());
    }

    public function testGetUnselectedSetCode()
    {
        static::assertSame("\e[44;37m", $this->getMenuStyle()->getUnselectedSetCode());
    }

    public function testGetUnselectedUnsetCode()
    {
        static::assertSame("\e[49;39m", $this->getMenuStyle()->getUnselectedUnsetCode());
    }

    public function testGetterAndSetters()
    {
        $style = $this->getMenuStyle();

        static::assertSame('blue', $style->getBg());
        static::assertSame('white', $style->getFg());
        static::assertSame('○', $style->getUnselectedMarker());
        static::assertSame('●', $style->getSelectedMarker());
        static::assertSame('✔', $style->getItemExtra());
        static::assertFalse($style->getDisplaysExtra());
        static::assertSame('=', $style->getTitleSeparator());
        static::assertSame(100, $style->getWidth());
        static::assertSame(2, $style->getMargin());
        static::assertSame(2, $style->getPadding());

        $style->setBg('red');
        $style->setFg('yellow');
        $style->setUnselectedMarker('-');
        $style->setSelectedMarker('>');
        $style->setItemExtra('EXTRA!');
        $style->setDisplaysExtra(true);
        $style->setTitleSeparator('+');
        $style->setWidth(200);
        $style->setMargin(10);
        $style->setPadding(10);

        static::assertSame('red', $style->getBg());
        static::assertSame('yellow', $style->getFg());
        static::assertSame('-', $style->getUnselectedMarker());
        static::assertSame('>', $style->getSelectedMarker());
        static::assertSame('EXTRA!', $style->getItemExtra());
        static::assertTrue($style->getDisplaysExtra());
        static::assertSame('+', $style->getTitleSeparator());
        static::assertSame(200, $style->getWidth());
        static::assertSame(10, $style->getMargin());
        static::assertSame(10, $style->getPadding());
    }

    public function testGetMarkerReturnsTheCorrectMarkers()
    {
        $style = $this->getMenuStyle();

        $style->setSelectedMarker('>');
        $style->setUnselectedMarker('x');

        static::assertSame('>', $style->getMarker(true));
        static::assertSame('x', $style->getMarker(false));
    }

    public function testWidthCalculation()
    {
        $style = $this->getMenuStyle();

        $style->setWidth(300);
        $style->setPadding(5);
        $style->setMargin(5);

        static::assertSame(280, $style->getContentWidth());
    }

    public function testRightHandPaddingCalculation()
    {
        $style = $this->getMenuStyle();

        $style->setWidth(300);
        $style->setPadding(5);
        $style->setMargin(5);

        static::assertSame(235, $style->getRightHandPadding(50));
    }
}
