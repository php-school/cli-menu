<?php

namespace PhpSchool\CliMenuTest;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class CliMenuBuilderTest extends TestCase
{
    public function testDefaultItems() : void
    {
        $builder = new CliMenuBuilder;
        $menu = $builder->build();
        
        $expected = [
            [
                'class' => SelectableItem::class,
                'text'  => 'Exit',
            ],
        ];
        
        $this->checkItems($menu, $expected);
    }

    public function testModifyExitButtonText() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setExitButtonText('RELEASE ME');
        $menu = $builder->build();

        $expected = [
            [
                'class' => SelectableItem::class,
                'text'  => 'RELEASE ME',
            ],
        ];

        $this->checkItems($menu, $expected);
    }

    public function testModifyStyles() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setBackgroundColour('red');
        $builder->setForegroundColour('red');
        $builder->setWidth(40);
        $builder->setPadding(4, 1);
        $builder->setMargin(4);
        $builder->setUnselectedMarker('>');
        $builder->setSelectedMarker('x');
        $builder->setItemExtra('*');
        $builder->setTitleSeparator('-');

        $terminal = static::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getWidth')
            ->will($this->returnValue(200));

        $builder->setTerminal($terminal);

        $menu = $builder->build();

        $this->checkStyleVariable($menu, 'bg', 'red');
        $this->checkStyleVariable($menu, 'fg', 'red');
        $this->checkStyleVariable($menu, 'width', 40);
        $this->checkStyleVariable($menu, 'paddingTopBottom', 4);
        $this->checkStyleVariable($menu, 'paddingLeftRight', 1);
        $this->checkStyleVariable($menu, 'margin', 4);
        $this->checkStyleVariable($menu, 'unselectedMarker', '>');
        $this->checkStyleVariable($menu, 'selectedMarker', 'x');
        $this->checkStyleVariable($menu, 'itemExtra', '*');
        $this->checkStyleVariable($menu, 'titleSeparator', '-');
    }

    public function testSetBorderShorthandFunction() : void
    {
        $terminal = static::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getWidth')
            ->will($this->returnValue(200));

        $menu = (new CliMenuBuilder)
            ->setTerminal($terminal)
            ->setBorder(2)
            ->build();
        $this->checkStyleVariable($menu, 'borderTopWidth', 2);
        $this->checkStyleVariable($menu, 'borderRightWidth', 2);
        $this->checkStyleVariable($menu, 'borderBottomWidth', 2);
        $this->checkStyleVariable($menu, 'borderLeftWidth', 2);
        $this->checkStyleVariable($menu, 'borderColour', 'white');

        $menu = (new CliMenuBuilder)
            ->setTerminal($terminal)
            ->setBorder(2, 4)
            ->build();
        $this->checkStyleVariable($menu, 'borderTopWidth', 2);
        $this->checkStyleVariable($menu, 'borderRightWidth', 4);
        $this->checkStyleVariable($menu, 'borderBottomWidth', 2);
        $this->checkStyleVariable($menu, 'borderLeftWidth', 4);
        $this->checkStyleVariable($menu, 'borderColour', 'white');

        $menu = (new CliMenuBuilder)
            ->setTerminal($terminal)
            ->setBorder(2, 4, 6)
            ->build();
        $this->checkStyleVariable($menu, 'borderTopWidth', 2);
        $this->checkStyleVariable($menu, 'borderRightWidth', 4);
        $this->checkStyleVariable($menu, 'borderBottomWidth', 6);
        $this->checkStyleVariable($menu, 'borderLeftWidth', 4);
        $this->checkStyleVariable($menu, 'borderColour', 'white');

        $menu = (new CliMenuBuilder)
            ->setTerminal($terminal)
            ->setBorder(2, 4, 6, 8)
            ->build();
        $this->checkStyleVariable($menu, 'borderTopWidth', 2);
        $this->checkStyleVariable($menu, 'borderRightWidth', 4);
        $this->checkStyleVariable($menu, 'borderBottomWidth', 6);
        $this->checkStyleVariable($menu, 'borderLeftWidth', 8);
        $this->checkStyleVariable($menu, 'borderColour', 'white');

        $menu = (new CliMenuBuilder)
            ->setTerminal($terminal)
            ->setBorder(2, 4, 6, 8, 'green')
            ->build();
        $this->checkStyleVariable($menu, 'borderTopWidth', 2);
        $this->checkStyleVariable($menu, 'borderRightWidth', 4);
        $this->checkStyleVariable($menu, 'borderBottomWidth', 6);
        $this->checkStyleVariable($menu, 'borderLeftWidth', 8);
        $this->checkStyleVariable($menu, 'borderColour', 'green');

        $menu = (new CliMenuBuilder)
            ->setTerminal($terminal)
            ->setBorder(2, 4, 6, 'green')
            ->build();
        $this->checkStyleVariable($menu, 'borderTopWidth', 2);
        $this->checkStyleVariable($menu, 'borderRightWidth', 4);
        $this->checkStyleVariable($menu, 'borderBottomWidth', 6);
        $this->checkStyleVariable($menu, 'borderLeftWidth', 4);
        $this->checkStyleVariable($menu, 'borderColour', 'green');

        $menu = (new CliMenuBuilder)
            ->setTerminal($terminal)
            ->setBorder(2, 4, 'green')
            ->build();
        $this->checkStyleVariable($menu, 'borderTopWidth', 2);
        $this->checkStyleVariable($menu, 'borderRightWidth', 4);
        $this->checkStyleVariable($menu, 'borderBottomWidth', 2);
        $this->checkStyleVariable($menu, 'borderLeftWidth', 4);
        $this->checkStyleVariable($menu, 'borderColour', 'green');

        $menu = (new CliMenuBuilder)
            ->setTerminal($terminal)
            ->setBorder(2, 'green')
            ->build();
        $this->checkStyleVariable($menu, 'borderTopWidth', 2);
        $this->checkStyleVariable($menu, 'borderRightWidth', 2);
        $this->checkStyleVariable($menu, 'borderBottomWidth', 2);
        $this->checkStyleVariable($menu, 'borderLeftWidth', 2);
        $this->checkStyleVariable($menu, 'borderColour', 'green');
    }

    public function testSetBorderTopWidth() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setBorderTopWidth(5);

        $style = $builder->build()->getStyle();

        self::assertEquals(5, $style->getBorderTopWidth());
    }

    public function testSetBorderRightWidth() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setBorderRightWidth(6);

        $style = $builder->build()->getStyle();

        self::assertEquals(6, $style->getBorderRightWidth());
    }

    public function testSetBorderBottomWidth() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setBorderBottomWidth(7);

        $style = $builder->build()->getStyle();

        self::assertEquals(7, $style->getBorderBottomWidth());
    }

    public function testSetBorderLeftWidth() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setBorderLeftWidth(8);

        $style = $builder->build()->getStyle();

        self::assertEquals(8, $style->getBorderLeftWidth());
    }

    public function testSetBorderColour() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setBorderColour('red');

        $style = $builder->build()->getStyle();

        self::assertEquals('red', $style->getBorderColour());
    }
    
    public function test256ColoursCodes() : void
    {
        $terminal = static::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getColourSupport')
            ->will($this->returnValue(256));

        $builder = new CliMenuBuilder;
        $builder->setTerminal($terminal);
        $builder->setBackgroundColour(16, 'white');
        $builder->setForegroundColour(206, 'red');
        $menu = $builder->build();

        $this->checkStyleVariable($menu, 'bg', 16);
        $this->checkStyleVariable($menu, 'fg', 206);

        $terminal = static::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getColourSupport')
            ->will($this->returnValue(8));

        $builder = new CliMenuBuilder;
        $builder->setTerminal($terminal);
        $builder->setBackgroundColour(16, 'white');
        $builder->setForegroundColour(206, 'red');
        $menu = $builder->build();

        $this->checkStyleVariable($menu, 'bg', 'white');
        $this->checkStyleVariable($menu, 'fg', 'red');
    }

    public function testSetFgThrowsExceptionWhenColourCodeIsNotInRange() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid colour code');

        $terminal = static::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getColourSupport')
            ->will($this->returnValue(256));

        $builder = new CliMenuBuilder;
        $builder->setTerminal($terminal);
        $builder->setForegroundColour(512, 'white');
    }

    public function testSetBgThrowsExceptionWhenColourCodeIsNotInRange() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid colour code');

        $terminal = static::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getColourSupport')
            ->will($this->returnValue(256));

        $builder = new CliMenuBuilder;
        $builder->setTerminal($terminal);
        $builder->setBackgroundColour(257, 'white');
    }

    public function testDisableDefaultItems() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        
        $menu = $builder->build();
            
        $this->checkVariable($menu, 'items', []);
    }

    public function testSetTitle() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setTitle('title');
        
        $menu = $builder->build();

        $this->checkVariable($menu, 'title', 'title');
    }

    public function testAddItem() : void
    {
        $callable = function () {
        };
        
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addItem('Item 1', $callable);
        $builder->addItem('Item 2', $callable);
        $menu = $builder->build();

        $expected = [
            [
                'class' => SelectableItem::class,
                'text'  => 'Item 1',
            ],
            [
                'class' => SelectableItem::class,
                'text'  => 'Item 2',
            ],
        ];
        
        $this->checkItems($menu, $expected);
    }

    public function testAddMultipleItems() : void
    {
        $callable = function () {
        };

        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addItems([
            ['Item 1', $callable],
            ['Item 2', $callable] ,
        ]);
        $menu = $builder->build();

        $expected = [
            [
                'class' => SelectableItem::class,
                'text'  => 'Item 1',
            ],
            [
                'class' => SelectableItem::class,
                'text'  => 'Item 2',
            ],
        ];

        $this->checkItems($menu, $expected);
    }

    public function testAddStaticItem() : void
    {

        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addStaticItem('Static Item 1');
        $menu = $builder->build();
        
        $expected = [
            [
                'class' => StaticItem::class,
                'text'  => 'Static Item 1',
            ]
        ];
        
        $this->checkItems($menu, $expected);
    }

    public function testAddLineBreakItem() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addLineBreak('Line Break Item 1');
        $menu = $builder->build();

        $expected = [
            [
                'class' => LineBreakItem::class,
                'breakChar'  => 'Line Break Item 1',
                'lines'  => 1,
            ]
        ];

        $this->checkItems($menu, $expected);
    }

    public function testAddLineBreakItemWithNumLines() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addLineBreak('Line Break Item 1', 3);
        $menu = $builder->build();

        $expected = [
            [
                'class' => LineBreakItem::class,
                'breakChar' => 'Line Break Item 1',
                'lines'  => 3,
            ]
        ];

        $this->checkItems($menu, $expected);
    }

    public function testAsciiArtWithDefaultPosition() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addAsciiArt("//\n//");
        $menu = $builder->build();

        $expected = [
            [
                'class' => AsciiArtItem::class,
                'text' => "//\n//",
                'position'  => AsciiArtItem::POSITION_CENTER,
            ]
        ];

        $this->checkItems($menu, $expected);
    }

    public function testAsciiArtWithSpecificPosition() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addAsciiArt("//\n//", AsciiArtItem::POSITION_LEFT);
        $menu = $builder->build();

        $expected = [
            [
                'class' => AsciiArtItem::class,
                'text' => "//\n//",
                'position'  => AsciiArtItem::POSITION_LEFT,
            ]
        ];

        $this->checkItems($menu, $expected);
    }

    public function testAsciiArtWithAlt() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addAsciiArt("//\n//", AsciiArtItem::POSITION_LEFT, 'Some ALT');
        $menu = $builder->build();

        $expected = [
            [
                'class' => AsciiArtItem::class,
                'text' => "//\n//",
                'position'  => AsciiArtItem::POSITION_LEFT,
                'alternateText' => 'Some ALT'
            ]
        ];

        $this->checkItems($menu, $expected);
    }

    public function testEndThrowsExceptionIfNoParentBuilder() : void
    {
        $builder = new CliMenuBuilder;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No parent builder to return to');
        
        $builder->end();
    }

    public function testAddSubMenu() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $subMenuBuilder = $builder->addSubMenu('sub-menu');
        
        $menu = $builder->build();
        
        $this->checkItems($menu, [
            [
                'class' => MenuMenuItem::class
            ]
        ]);
        
        $this->assertInstanceOf(CliMenuBuilder::class, $subMenuBuilder);
        $this->assertNotSame($subMenuBuilder, $builder);
        $this->assertSame($builder, $subMenuBuilder->end());
    }

    public function testAddSubMenuWithBuilder() : void
    {
        $subMenuBuilder = new CliMenuBuilder;
        
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addSubMenu('sub-menu', $subMenuBuilder);

        $menu = $builder->build();

        $this->checkItems($menu, [
            [
                'class' => MenuMenuItem::class
            ]
        ]);
    }

    public function testSubMenuInheritsParentsStyle() : void
    {
        $builder = new CliMenuBuilder;
        $menu = $builder->setBackgroundColour('green')
            ->addSubMenu('sub-menu')
                ->addItem('Some Item', function () {
                })
                ->end()
            ->build();

        $this->assertSame('green', $builder->getSubMenu('sub-menu')->getStyle()->getBg());
    }

    public function testSubMenuDoesNotInheritsParentsStyleWhenSubMenuStyleHasAlterations() : void
    {
        $builder = new CliMenuBuilder;
        $menu = $builder->setBackgroundColour('green')
            ->addSubMenu('sub-menu')
                ->addItem('Some Item', function () {
                })
                ->setBackgroundColour('red')
                ->end()
            ->build();

        $this->assertSame('red', $builder->getSubMenu('sub-menu')->getStyle()->getBg());
        $this->assertSame('green', $menu->getStyle()->getBg());
    }

    public function testGetSubMenuThrowsExceptionIfNotBuiltYet() : void
    {
        $builder = (new CliMenuBuilder)
            ->disableDefaultItems()
            ->addSubMenu('sub-menu')
                ->end();
        
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Menu: "sub-menu" cannot be retrieved until menu has been built');
        
        $builder->getSubMenu('sub-menu');
    }

    public function testGetSubMenuReturnsInstanceOfBuiltSubMenu() : void
    {
        $builder = (new CliMenuBuilder)
            ->disableDefaultItems()
            ->addSubMenu('sub-menu')
                ->end();
        
        $menu       = $builder->build();
        $subMenu    = $builder->getSubMenu('sub-menu');
        
        $this->assertInstanceOf(CliMenu::class, $menu);
        $this->assertInstanceOf(CliMenu::class, $subMenu);
        $this->assertNotSame($subMenu, $menu);
    }

    public function testSubMenuDefaultItems() : void
    {
        $builder = (new CliMenuBuilder)
            ->disableDefaultItems()
            ->addSubMenu('sub-menu')
            ->end();

        $menu       = $builder->build();
        $subMenu    = $builder->getSubMenu('sub-menu');

        $expected = [
            [
                'class' => SelectableItem::class,
                'text'  => 'Go Back',
            ],
            [
                'class' => SelectableItem::class,
                'text'  => 'Exit',
            ],
        ];

        $this->checkItems($subMenu, $expected);
    }

    public function testModifyExitAndGoBackTextOnSubMenu() : void
    {
        $builder = (new CliMenuBuilder)
            ->disableDefaultItems()
            ->addSubMenu('sub-menu')
                ->setExitButtonText("Won't you stay a little while longer?")
                ->setGoBackButtonText("Don't click this - it's definitely not a go back button")
                ->end();

        $menu       = $builder->build();
        $subMenu    = $builder->getSubMenu('sub-menu');

        $expected = [
            [
                'class' => SelectableItem::class,
                'text'  => "Don't click this - it's definitely not a go back button",
            ],
            [
                'class' => SelectableItem::class,
                'text'  => "Won't you stay a little while longer?",
            ],
        ];

        $this->checkItems($subMenu, $expected);
    }

    public function testDisableDefaultItemsDisablesExitAndGoBackOnSubMenu() : void
    {
        $builder = (new CliMenuBuilder)
            ->disableDefaultItems()
            ->addSubMenu('sub-menu')
                ->disableDefaultItems()
                ->end();

        $menu       = $builder->build();
        $subMenu    = $builder->getSubMenu('sub-menu');

        $this->checkVariable($subMenu, 'items', []);
    }

    public function testThrowsExceptionWhenDisablingRootMenu() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('You can\'t disable the root menu');

        (new CliMenuBuilder)->disableMenu();
    }

    /**
     * @dataProvider marginBelowZeroProvider
     */
    public function testSetMarginThrowsExceptionIfValueIsNotZeroOrAbove(int $value) : void
    {
        self::expectException(\Assert\InvalidArgumentException::class);
        
        
        (new CliMenuBuilder)->setMargin($value)->build();
    }

    public function marginBelowZeroProvider() : array
    {
        return [[-1], [-2], [-10]];
    }

    /**
     * @dataProvider marginAboveZeroProvider
     */
    public function testSetMarginAcceptsZeroAndPositiveIntegers(int $value) : void
    {
        $menu = (new CliMenuBuilder)->setMargin($value)->build();
        
        self::assertSame($value, $menu->getStyle()->getMargin());
    }

    public function marginAboveZeroProvider() : array
    {
        return [[0], [1], [10], [50]];
    }

    public function testSetMarginAutoAutomaticallyCalculatesMarginToCenter() : void
    {
        $terminal = self::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getWidth')
            ->will($this->returnValue(200));

        $builder = new CliMenuBuilder;
        $menu = $builder
            ->setTerminal($terminal)
            ->setMarginAuto()
            ->setWidth(100)
            ->build();
        
        self::assertSame(50, $menu->getStyle()->getMargin());
    }

    public function testSetMarginAutoOverwritesSetMargin() : void
    {
        $terminal = self::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getWidth')
            ->will($this->returnValue(200));

        $builder = new CliMenuBuilder;
        $menu = $builder
            ->setTerminal($terminal)
            ->setMargin(10)
            ->setMarginAuto()
            ->setWidth(100)
            ->build();

        self::assertSame(50, $menu->getStyle()->getMargin());
    }

    public function testSetMarginManuallyOverwritesSetMarginAuto() : void
    {
        $terminal = self::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getWidth')
            ->will($this->returnValue(200));

        $builder = new CliMenuBuilder;
        $menu = $builder
            ->setTerminal($terminal)
            ->setMarginAuto()
            ->setMargin(10)
            ->setWidth(100)
            ->build();

        self::assertSame(10, $menu->getStyle()->getMargin());
    }

    public function testSetPaddingWithUniversalValue() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setPadding(3);

        $style = $builder->build()->getStyle();
        
        self::assertEquals(3, $style->getPaddingTopBottom());
        self::assertEquals(3, $style->getPaddingLeftRight());
    }

    public function testSetPaddingWithXAndYValues() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setPadding(2, 3);

        $style = $builder->build()->getStyle();

        self::assertEquals(2, $style->getPaddingTopBottom());
        self::assertEquals(3, $style->getPaddingLeftRight());
    }

    public function testSetPaddingTopAndBottom() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setPaddingTopBottom(2);

        $style = $builder->build()->getStyle();

        self::assertEquals(2, $style->getPaddingTopBottom());
    }

    public function testSetPaddingLeftAndRight() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setPaddingLeftRight(3);

        $style = $builder->build()->getStyle();

        self::assertEquals(3, $style->getPaddingLeftRight());
    }
    
    private function checkItems(CliMenu $menu, array $expected) : void
    {
        $actualItems = $this->readAttribute($menu, 'items');
        $this->assertCount(count($expected), $actualItems);
        
        foreach ($expected as $expectedItem) {
            $actualItem = array_shift($actualItems);
            
            $this->assertInstanceOf($expectedItem['class'], $actualItem);
            unset($expectedItem['class']);
            
            foreach ($expectedItem as $property => $value) {
                $this->assertEquals($this->readAttribute($actualItem, $property), $value);
            }
        }
    }
    
    private function checkVariable(CliMenu $menu, string $property, $expected) : void
    {
        $actual = $this->readAttribute($menu, $property);
        $this->assertEquals($expected, $actual);
    }

    private function checkStyleVariable(CliMenu $menu, string $property, $expected) : void
    {
        $style = $this->readAttribute($menu, 'style');
        $this->assertEquals($this->readAttribute($style, $property), $expected);
    }
}
