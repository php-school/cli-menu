<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Separation')
    ->addItem('First Item')
    ->addItem('Second Item')
    ->addItem('Third Item')
    ->addLineBreak()
    ->addItem('Fourth Item')
    ->addItem('Fifth Item')
    ->addItem('Sixth Item')
    ->addItemCallable($itemCallable)
    ->build();

$menu->display();

