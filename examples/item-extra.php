<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Custom Item Extra')
    ->addItem('First Item', true)
    ->addItem('Second Item')
    ->addItem('Third Item', true)
    ->addItemCallable($itemCallable)
    ->setItemExtra('[COMPLETE!]')
    ->build();

$menu->display();
