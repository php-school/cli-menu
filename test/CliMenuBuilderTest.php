<?php

namespace PhpSchool\CliMenuTest;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * Class CliMenuBuilderTest
 * @package PhpSchool\CliMenuTest
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class CliMenuBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultItems()
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

    public function testModifyExitButtonText()
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

    public function testModifyStyles()
    {
        $builder = new CliMenuBuilder;
        $builder->setBackgroundColour('red');
        $builder->setForegroundColour('red');
        $builder->setWidth(40);
        $builder->setPadding(4);
        $builder->setMargin(4);
        $builder->setUnselectedMarker('>');
        $builder->setSelectedMarker('x');
        $builder->setItemExtra('*');
        $builder->setTitleSeparator('-');
        
        $terminal = $this->getMock(TerminalInterface::class);
        $terminal
            ->expects($this->once())
            ->method('getWidth')
            ->will($this->returnValue(200));
        
        $builder->setTerminal($terminal);
        
        $menu = $builder->build();
        
        $this->checkStyleVariable($menu, 'bg', 'red');
        $this->checkStyleVariable($menu, 'fg', 'red');
        $this->checkStyleVariable($menu, 'width', 40);
        $this->checkStyleVariable($menu, 'padding', 4);
        $this->checkStyleVariable($menu, 'margin', 4);
        $this->checkStyleVariable($menu, 'unselectedMarker', '>');
        $this->checkStyleVariable($menu, 'selectedMarker', 'x');
        $this->checkStyleVariable($menu, 'itemExtra', '*');
        $this->checkStyleVariable($menu, 'titleSeparator', '-');
    }

    public function testDisableDefaultItems()
    {
        $builder = new CliMenuBuilder;
        $builder->disableDefaultItems();
        
        $menu = $builder->build();
            
        $this->checkVariable($menu, 'items', []);
    }

    public function testSetTitle()
    {
        $builder = new CliMenuBuilder;
        $builder->setTitle('title');
        
        $menu = $builder->build();

        $this->checkVariable($menu, 'title', 'title');
    }

    public function testAddItem()
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

    public function testAddMultipleItems()
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

    public function testAddStaticItem()
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

    public function testAddLineBreakItem()
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

    public function testAddLineBreakItemWithNumLines()
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

    public function testAsciiArtWithDefaultPosition()
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

    public function testAsciiArtWithSpecificPosition()
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

    public function testEndThrowsExceptionIfNoParentBuilder()
    {
        $builder = new CliMenuBuilder;

        $this->setExpectedException(
            RuntimeException::class,
            'No parent builder to return to'
        );
        
        $builder->end();
    }

    public function testAddSubMenu()
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

    public function testGetSubMenuThrowsExceptionIfNotBuiltYet()
    {
        $builder = (new CliMenuBuilder)
            ->disableDefaultItems()
            ->addSubMenu('sub-menu')
                ->end();
        
        $this->setExpectedException(
            RuntimeException::class,
            'Menu: "sub-menu" cannot be retrieved until menu has been built'
        );
        
        $builder->getSubMenu('sub-menu');
    }

    public function testGetSubMenuReturnsInstanceOfBuiltSubMenu()
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

    public function testSubMenuDefaultItems()
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

    public function testModifyExitAndGoBackTextOnSubMenu()
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

    public function testDisableDefaultItemsDisablesExitAndGoBackOnSubMenu()
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
    
    /**
     * @param CliMenu $menu
     * @param array $expected
     */
    private function checkItems(CliMenu $menu, array $expected)
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
    
    /**
     * @param CliMenu $menu
     * @param string $property
     * @param mixed $expected
     */
    private function checkVariable(CliMenu $menu, $property, $expected)
    {
        $actual = $this->readAttribute($menu, $property);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param CliMenu $menu
     * @param $property
     * @param mixed $expected
     */
    private function checkStyleVariable(CliMenu $menu, $property, $expected)
    {
        $style = $this->readAttribute($menu, 'style');
        $this->assertEquals($this->readAttribute($style, $property), $expected);
    }
}
