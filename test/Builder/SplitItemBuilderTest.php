<?php

namespace PhpSchool\CliMenuTest\Builder;

use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
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

        $this->checkItems($item, $expected);
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

        $this->checkItems($item, $expected);
    }

    public function testAddSubMenu() : void
    {
        $menu = new CliMenu(null, []);
        $builder = new SplitItemBuilder($menu);
        $builder->addSubMenu('My SubMenu', function () {
        });

        $item = $builder->build();

        $this->checkItems($item, [
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
        self::assertEquals(4, self::readAttribute($item, 'gutter'));
    }

    private function checkItems(SplitItem $item, array $expected) : void
    {
        $actualItems = $this->readAttribute($item, 'items');
        self::assertCount(count($expected), $actualItems);

        foreach ($expected as $expectedItem) {
            $actualItem = array_shift($actualItems);

            self::assertInstanceOf($expectedItem['class'], $actualItem);
            unset($expectedItem['class']);

            foreach ($expectedItem as $property => $value) {
                self::assertEquals($this->readAttribute($actualItem, $property), $value);
            }
        }
    }
}
