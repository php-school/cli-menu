<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Disabled Items')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable, false, true)
    ->addItem('Third Item', $itemCallable, false, true)
    ->addSubMenu('Submenu')
        ->setTitle('Basic CLI Menu Disabled Items > Submenu')
        ->addItem('You can go in here!', $itemCallable)
        ->end()
    ->addSubMenu('Disabled Submenu')
        ->setTitle('Basic CLI Menu Disabled Items > Disabled Submenu')
        ->addItem('Nope can\'t see this!', $itemCallable)
        ->disableMenu()
        ->end()
    ->addLineBreak('-')
    ->build();

$menu->open();
