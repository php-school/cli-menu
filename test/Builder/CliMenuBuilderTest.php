<?php

namespace PhpSchool\CliMenuTest\Builder;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

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
        
        $this->checkMenuItems($menu, $expected);
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

        $this->checkMenuItems($menu, $expected);
    }

    public function testModifyStyles() : void
    {
        $terminal = static::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getWidth')
            ->will($this->returnValue(200));
        
        $builder = new CliMenuBuilder($terminal);
        $builder->setBackgroundColour('red');
        $builder->setForegroundColour('red');
        $builder->setWidth(40);
        $builder->setPadding(4, 1);
        $builder->setMargin(4);
        $builder->setUnselectedMarker('>');
        $builder->setSelectedMarker('x');
        $builder->setItemExtra('*');
        $builder->setTitleSeparator('-');

        $menu = $builder->build();
        $style = $menu->getStyle();

        self::assertEquals('red', $style->getBg());
        self::assertEquals('red', $style->getFg());
        self::assertEquals(40, $style->getWidth());
        self::assertEquals(4, $style->getPaddingTopBottom());
        self::assertEquals(1, $style->getPaddingLeftRight());
        self::assertEquals(4, $style->getMargin());
        self::assertEquals('>', $style->getUnselectedMarker());
        self::assertEquals('x', $style->getSelectedMarker());
        self::assertEquals('*', $style->getItemExtra());
        self::assertEquals('-', $style->getTitleSeparator());
    }

    public function testSetBorderShorthandFunction() : void
    {
        $terminal = static::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getWidth')
            ->will($this->returnValue(200));

        $style = (new CliMenuBuilder($terminal))
            ->setBorder(2)
            ->build()
            ->getStyle();

        self::assertEquals(2, $style->getBorderTopWidth());
        self::assertEquals(2, $style->getBorderRightWidth());
        self::assertEquals(2, $style->getBorderBottomWidth());
        self::assertEquals(2, $style->getBorderLeftWidth());
        self::assertEquals('white', $style->getBorderColour());

        $style = (new CliMenuBuilder($terminal))
            ->setBorder(2, 4)
            ->build()
            ->getStyle();

        self::assertEquals(2, $style->getBorderTopWidth());
        self::assertEquals(4, $style->getBorderRightWidth());
        self::assertEquals(2, $style->getBorderBottomWidth());
        self::assertEquals(4, $style->getBorderLeftWidth());
        self::assertEquals('white', $style->getBorderColour());

        $style = (new CliMenuBuilder($terminal))
            ->setBorder(2, 4, 6)
            ->build()
            ->getStyle();

        self::assertEquals(2, $style->getBorderTopWidth());
        self::assertEquals(4, $style->getBorderRightWidth());
        self::assertEquals(6, $style->getBorderBottomWidth());
        self::assertEquals(4, $style->getBorderLeftWidth());
        self::assertEquals('white', $style->getBorderColour());

        $style = (new CliMenuBuilder($terminal))
            ->setBorder(2, 4, 6, 8)
            ->build()
            ->getStyle();

        self::assertEquals(2, $style->getBorderTopWidth());
        self::assertEquals(4, $style->getBorderRightWidth());
        self::assertEquals(6, $style->getBorderBottomWidth());
        self::assertEquals(8, $style->getBorderLeftWidth());
        self::assertEquals('white', $style->getBorderColour());

        $style = (new CliMenuBuilder($terminal))
            ->setBorder(2, 4, 6, 8, 'green')
            ->build()
            ->getStyle();

        self::assertEquals(2, $style->getBorderTopWidth());
        self::assertEquals(4, $style->getBorderRightWidth());
        self::assertEquals(6, $style->getBorderBottomWidth());
        self::assertEquals(8, $style->getBorderLeftWidth());
        self::assertEquals('green', $style->getBorderColour());

        $style = (new CliMenuBuilder($terminal))
            ->setBorder(2, 4, 6, 'green')
            ->build()
            ->getStyle();

        self::assertEquals(2, $style->getBorderTopWidth());
        self::assertEquals(4, $style->getBorderRightWidth());
        self::assertEquals(6, $style->getBorderBottomWidth());
        self::assertEquals(4, $style->getBorderLeftWidth());
        self::assertEquals('green', $style->getBorderColour());

        $style = (new CliMenuBuilder($terminal))
            ->setBorder(2, 4, 'green')
            ->build()
            ->getStyle();

        self::assertEquals(2, $style->getBorderTopWidth());
        self::assertEquals(4, $style->getBorderRightWidth());
        self::assertEquals(2, $style->getBorderBottomWidth());
        self::assertEquals(4, $style->getBorderLeftWidth());
        self::assertEquals('green', $style->getBorderColour());

        $style = (new CliMenuBuilder($terminal))
            ->setBorder(2, 'green')
            ->build()
            ->getStyle();

        self::assertEquals(2, $style->getBorderTopWidth());
        self::assertEquals(2, $style->getBorderRightWidth());
        self::assertEquals(2, $style->getBorderBottomWidth());
        self::assertEquals(2, $style->getBorderLeftWidth());
        self::assertEquals('green', $style->getBorderColour());
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

        $builder = new CliMenuBuilder($terminal);
        $builder->setBackgroundColour(16, 'white');
        $builder->setForegroundColour(206, 'red');
        $style = $builder->build()
            ->getStyle();

        self::assertEquals(16, $style->getBg());
        self::assertEquals(206, $style->getFg());

        $terminal = static::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getColourSupport')
            ->will($this->returnValue(8));

        $builder = new CliMenuBuilder($terminal);
        $builder->setBackgroundColour(16, 'white');
        $builder->setForegroundColour(206, 'red');
        $style = $builder->build()
            ->getStyle();

        self::assertEquals('white', $style->getBg());
        self::assertEquals('red', $style->getFg());
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

        $builder = new CliMenuBuilder($terminal);
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

        $builder = new CliMenuBuilder($terminal);
        $builder->setBackgroundColour(257, 'white');
    }

    public function testDisableDefaultItems() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        
        $menu = $builder->build();

        self::assertEquals([], $menu->getItems());
    }

    public function testSetTitle() : void
    {
        $builder = new CliMenuBuilder;
        $builder->setTitle('title');
        
        $menu = $builder->build();

        self::assertEquals('title', $menu->getTitle());
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
        
        $this->checkMenuItems($menu, $expected);
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

        $this->checkMenuItems($menu, $expected);
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
        
        $this->checkMenuItems($menu, $expected);
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

        $this->checkMenuItems($menu, $expected);
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

        $this->checkMenuItems($menu, $expected);
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

        $this->checkMenuItems($menu, $expected);
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

        $this->checkMenuItems($menu, $expected);
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

        $this->checkMenuItems($menu, $expected);
    }

    public function testAddSubMenu() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addSubMenu('My SubMenu', function () {
        });
        
        $menu = $builder->build();
        
        $this->checkMenuItems($menu, [
            [
                'class' => MenuMenuItem::class
            ]
        ]);
    }

    public function testAddSubMenuWithBuilder() : void
    {
        $subMenuBuilder = new CliMenuBuilder;
        
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addSubMenuFromBuilder('My SubMenu', $subMenuBuilder);

        $menu = $builder->build();

        $this->checkMenuItems($menu, [
            [
                'class' => MenuMenuItem::class
            ]
        ]);
    }

    public function testAddSubMenuUsesTextParameterAsMenuItemText() : void
    {
        $subMenuBuilder = new CliMenuBuilder;

        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addSubMenuFromBuilder('My SubMenu', $subMenuBuilder);

        $menu = $builder->build();

        self::assertEquals('My SubMenu', $menu->getItems()[0]->getText());
    }

    public function testSubMenuInheritsParentsStyle() : void
    {
        $terminal = self::createMock(Terminal::class);
        $terminal
            ->expects($this->any())
            ->method('getWidth')
            ->will($this->returnValue(200));
        
        $menu = (new CliMenuBuilder($terminal))
            ->setBackgroundColour('green')
            ->addSubMenu('My SubMenu', function (CliMenuBuilder $b) {
                $b->addItem('Some Item', function () {
                });
            })
            ->build();

        self::assertSame('green', $menu->getItems()[0]->getSubMenu()->getStyle()->getBg());
        self::assertSame($menu->getStyle(), $menu->getItems()[0]->getSubMenu()->getStyle());
    }

    public function testSubMenuDoesNotInheritsParentsStyleWhenSubMenuStyleHasAlterations() : void
    {
        $menu = (new CliMenuBuilder)
            ->setBackgroundColour('green')
            ->addSubMenu('My SubMenu', function (CliMenuBuilder $b) {
                $b->addItem('Some Item', function () {
                })
                ->setBackgroundColour('red');
            })
            ->build();

        self::assertSame('red', $menu->getItems()[0]->getSubMenu()->getStyle()->getBg());
        self::assertSame('green', $menu->getStyle()->getBg());
    }

    public function testSubMenuDefaultItems() : void
    {
        $menu = (new CliMenuBuilder)
            ->disableDefaultItems()
            ->addSubMenu('My SubMenu', function () {
            })
            ->build();

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

        $this->checkMenuItems($menu->getItems()[0]->getSubMenu(), $expected);
    }

    public function testModifyExitAndGoBackTextOnSubMenu() : void
    {
        $menu = (new CliMenuBuilder)
            ->disableDefaultItems()
            ->addSubMenu('My SubMenu', function (CliMenuBuilder $b) {
                $b->setExitButtonText("Won't you stay a little while longer?")
                    ->setGoBackButtonText("Don't click this - it's definitely not a go back button");
            })
            ->build();
                

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

        $this->checkMenuItems($menu->getItems()[0]->getSubMenu(), $expected);
    }

    public function testDisableDefaultItemsDisablesExitAndGoBackOnSubMenu() : void
    {
        $menu = (new CliMenuBuilder)
            ->disableDefaultItems()
            ->addSubMenu('My SubMenu', function (CliMenuBuilder $b) {
                $b->disableDefaultItems();
            })
            ->build();
                
        self::assertEquals($menu->getItems()[0]->getSubMenu()->getItems(), []);
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

        $builder = new CliMenuBuilder($terminal);
        $menu = $builder
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

        $builder = new CliMenuBuilder($terminal);
        $menu = $builder
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

        $builder = new CliMenuBuilder($terminal);
        $menu = $builder
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

    public function testAddSubMenuWithClosureBinding() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addSubMenu('My SubMenu', function () {
            $this->disableDefaultItems();
            $this->addItem('My Item', function () {
            });
        });

        $menu = $builder->build();

        $expected = [
            [
                'class' => SelectableItem::class,
                'text'  => 'My Item',
            ]
        ];

        $this->checkMenuItems($menu->getItems()[0]->getSubMenu(), $expected);
    }

    public function testAddSplitItemWithClosureBinding() : void
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        $builder->addSplitItem(function () {
            $this->addItem('My Item', function () {
            });
        });

        $menu = $builder->build();

        $expected = [
            [
                'class' => SelectableItem::class,
                'text'  => 'My Item',
            ]
        ];

        $this->checkItems($menu->getItems()[0]->getItems(), $expected);
    }

    private function checkMenuItems(CliMenu $menu, array $expected) : void
    {
        $this->checkItems($menu->getItems(), $expected);
    }

    private function checkItems(array $actualItems, array $expected) : void
    {
        $propMap = [
            'breakChar' => 'getText',
        ];

        self::assertCount(count($expected), $actualItems);

        foreach ($expected as $expectedItem) {
            $actualItem = array_shift($actualItems);

            self::assertInstanceOf($expectedItem['class'], $actualItem);
            unset($expectedItem['class']);

            foreach ($expectedItem as $property => $value) {
                if (isset($propMap[$property])) {
                    $getter = $propMap[$property];
                } else {
                    $getter = 'get'. ucfirst($property);
                }

                self::assertEquals($actualItem->{$getter}(), $value);
            }
        }
    }
}
