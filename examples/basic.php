<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;
use MikeyMike\CliMenu\MenuItem\MenuItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$menu = (new CliMenuBuilder('Basic CLI Menu'))
    ->addItem(new MenuItem('First Item'))
    ->addItem(new MenuItem('Second Item'))
    ->addItem(new MenuItem('Third Item'))
    ->setItemCallback(function (CliMenu $menu) {
        echo sprintf('You selected "%s"', $menu->getSelectedItem()->getText());
    })
    ->build();

$menu->display();
