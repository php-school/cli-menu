<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Disabled Items')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable, false, true)
    ->addItem('Third Item', $itemCallable, false, true)
    ->addSubMenu('sub-menu-1', 'Submenu')
        ->setTitle('Basic CLI Menu Disabled Items > Submenu')
        ->addItem('You can go in here!', $itemCallable)
        ->end()
    ->addSubMenu('sub-menu-2', 'Disabled Submenu')
        ->setTitle('Basic CLI Menu Disabled Items > Disabled Submenu')
        ->addItem('Nope can\'t see this!', $itemCallable)
        ->disableMenu()
        ->end()
    ->addLineBreak('-')
    ->build();

$menu->open();
