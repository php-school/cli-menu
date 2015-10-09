<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;
use MikeyMike\CliMenu\MenuItem\LineBreakItem;
use MikeyMike\CliMenu\MenuItem\MenuItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$menu = (new CliMenuBuilder('Basic CLI Menu with Separation'))
    ->addItem(new MenuItem('First Item'))
    ->addItem(new MenuItem('Second Item'))
    ->addItem(new MenuItem('Third Item'))
    ->addItem(new LineBreakItem())
    ->addItem(new LineBreakItem('/\\'))
    ->addItem(new LineBreakItem('-=-', 2))
    ->addItem(new LineBreakItem('\\/'))
    ->addItem(new LineBreakItem())
    ->addItem(new MenuItem('Fourth Item'))
    ->addItem(new MenuItem('Fifth Item'))
    ->addItem(new MenuItem('Sixth Item'))
    ->setItemCallback(function (CliMenu $menu) {
        echo sprintf('You selected "%s"', $menu->getSelectedItem()->getText());
    })
    ->build();

$menu->display();
