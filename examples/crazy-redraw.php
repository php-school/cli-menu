<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $colour = function () {
        return array_rand(array_flip(['blue', 'green', 'red', 'yellow']));
    };
    $int = function () {
        return rand(1, 20);
    };

    $bg = $colour();
    while (($fg = $colour()) === $bg) {
    }
    
    $menu->getStyle()->setBg($bg);
    $menu->getStyle()->setFg($fg);
    $menu->getStyle()->setPadding($int());
    $menu->getStyle()->setMargin($int());

    $items = $menu->getItems();
    
    array_walk($items, function (MenuItemInterface $item) use ($menu) {
        $menu->removeItem($item);
    });

    $items = array_filter($items, function (MenuItemInterface $item) {
        return !$item instanceof LineBreakItem;
    });

    foreach (range(0, rand(1, 5)) as $i) {
        $items[] = new LineBreakItem(array_rand(array_flip(['♥', '★', '^'])), rand(1, 3));
    }
    shuffle($items);

    array_walk(
        $items,
        function (MenuItemInterface $item) use ($menu) {
            $menu->addItem($item);
        }
    );
    
    $menu->redraw(true);
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->setWidth(80)
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
