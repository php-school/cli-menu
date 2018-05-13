<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    static $i = 1;

    foreach ($menu->getItems() as $item) {
        $i % 2 === 0
            ? $item->showItemExtra()
            : $item->hideItemExtra();

        $menu->redraw();
    }

    $i++;
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Custom Item Extra')
    ->addItem('First Item', $itemCallable, true)
    ->addItem('Second Item', $itemCallable, true)
    ->addItem('Third Item', $itemCallable, true)
    ->addLineBreak('-')
    ->setItemExtra('**')
    ->build();

$menu->open();
