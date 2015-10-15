<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Styling')
    ->addItem('First Item')
    ->addItem('Second Item')
    ->addItem('Third Item')
    ->addItemCallable($itemCallable)
    ->setWidth(70)
    ->setBackgroundColour('yellow')
    ->setForegroundColour('black')
    ->setPadding(4)
    ->setMargin(4)
    ->setUnselectedMarker(' ')
    ->setSelectedMarker('>')
    ->build();

$menu->display();
