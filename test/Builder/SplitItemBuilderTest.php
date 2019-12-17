<?php

namespace PhpSchool\CliMenuTest\Builder;

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\CheckableItem;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\RadioItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SplitItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PHPUnit\Framework\TestCase;

class SplitItemBuilderTest extends TestCase
{
    public function testAddItem() : void
    {
        $callable = function () {
        };

        $menu = new CliMenu(null, []);
        $builder = new SplitItemBuilder($menu);
        $builder->addItem('Item 1', $callable);
        $builder->addItem('Item 2', $callable);
        $item = $builder->build();

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

        $this->checkItemItems($item, $expected);
    }

    public function testAddCheckableItem() : void
    {
        $callable = function () {
        };

        $menu = new CliMenu(null, []);
        $builder = new SplitItemBuilder($menu);
        $builder->addCheckableItem('Item 1', $callable);
        $builder->addCheckableItem('Item 2', $callable);
        $item = $builder->build();

        $expected = [
            [
                'class' => CheckableItem::class,
                'text'  => 'Item 1',
            ],
            [
                'class' => CheckableItem::class,
                'text'  => 'Item 2',
            ],
        ];

        $this->checkItemItems($item, $expected);
    }

    public function testAddRadioItem() : void
    {
        $callable = function () {
        };

        $menu = new CliMenu(null, []);
        $builder = new SplitItemBuilder($menu);
        $builder->addRadioItem('Item 1', $callable);
        $builder->addRadioItem('Item 2', $callable);
        $item = $builder->build();

        $expected = [
            [
                'class' => RadioItem::class,
                'text'  => 'Item 1',
            ],
            [
                'class' => RadioItem::class,
                'text'  => 'Item 2',
            ],
        ];

        $this->checkItemItems($item, $expected);
    }

    public function testAddStaticItem() : void
    {

        $menu = new CliMenu(null, []);
        $builder = new SplitItemBuilder($menu);
        $builder->addStaticItem('Static Item 1');
        $item = $builder->build();

        $expected = [
            [
                'class' => StaticItem::class,
                'text'  => 'Static Item 1',
            ]
        ];

        $this->checkItemItems($item, $expected);
    }

    public function testAddSubMenu() : void
    {
        $menu = new CliMenu(null, []);
        $builder = new SplitItemBuilder($menu);
        $builder->addSubMenu('My SubMenu', function () {
        });

        $item = $builder->build();

        $this->checkItemItems($item, [
            [
                'class' => MenuMenuItem::class
            ]
        ]);
    }

    public function testAddSubMenuUsesTextParameterAsMenuItemText() : void
    {
        $menu = new CliMenu(null, []);
        $builder = new SplitItemBuilder($menu);
        $builder->addSubMenu('My SubMenu', function () {
        });

        $item = $builder->build();

        self::assertEquals('My SubMenu', $item->getItems()[0]->getText());
    }

    public function testSetGutter() : void
    {
        $menu = new CliMenu(null, []);
        $builder = new SplitItemBuilder($menu);
        $builder->setGutter(4);

        $item = $builder->build();
        self::assertEquals(4, $item->getGutter());
    }

    public function testAddSubMenuWithClosureBinding() : void
    {
        $menu = new CliMenu(null, []);
        $builder = new SplitItemBuilder($menu);
        $builder->addSubMenu('My SubMenu', function (CliMenuBuilder $b) {
            $b->disableDefaultItems();
            $b->addItem('My Item', function () {
            });
        });

        $item = $builder->build();

        $expected = [
            [
                'class' => SelectableItem::class,
                'text'  => 'My Item',
            ]
        ];

        $this->checkItems(
            $item->getItems()[0]->getSubMenu()->getItems(),
            $expected
        );
    }

    private function checkItemItems(SplitItem $item, array $expected) : void
    {
        $this->checkItems($item->getItems(), $expected);
    }

    private function checkItems(array $actualItems, array $expected) : void
    {
        self::assertCount(count($expected), $actualItems);

        foreach ($expected as $expectedItem) {
            $actualItem = array_shift($actualItems);

            self::assertInstanceOf($expectedItem['class'], $actualItem);
            unset($expectedItem['class']);

            foreach ($expectedItem as $property => $value) {
                self::assertEquals($actualItem->{'get'. ucfirst($property)}(), $value);
            }
        }
    }
}
