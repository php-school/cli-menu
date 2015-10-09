<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;
use MikeyMike\CliMenu\MenuItem\LineBreakItem;
use MikeyMike\CliMenu\MenuItem\MenuItem;
use MikeyMike\CliMenu\MenuItem\StaticItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$menu = (new CliMenuBuilder('Basic CLI Menu with Separation'))
    ->addItem(new LineBreakItem())
    ->addItem(new StaticItem('Section 1'))
    ->addItem(new StaticItem('---------'))
    ->addItem(new MenuItem('First Item'))
    ->addItem(new MenuItem('Second Item'))
    ->addItem(new MenuItem('Third Item'))
    ->addItem(new LineBreakItem())
    ->addItem(new StaticItem('Section 2'))
    ->addItem(new StaticItem('---------'))
    ->addItem(new MenuItem('Fourth Item'))
    ->addItem(new MenuItem('Fifth Item'))
    ->addItem(new MenuItem('Sixth Item'))
    ->addItem(new LineBreakItem())
    ->addItem(new StaticItem('Section 3'))
    ->addItem(new StaticItem('---------'))
    ->addItem(new MenuItem('Seventh Item'))
    ->addItem(new MenuItem('Eighth Item'))
    ->addItem(new MenuItem('Ninth Item'))
    ->addItem(new LineBreakItem())
    ->setItemCallback(function (CliMenu $menu) {
        echo sprintf('You selected "%s"', $menu->getSelectedItem()->getText());
    })
    ->build();

$menu->display();
