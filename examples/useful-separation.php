<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;
use MikeyMike\CliMenu\MenuItem\LineBreakItem;
use MikeyMike\CliMenu\MenuItem\MenuItem;
use MikeyMike\CliMenu\MenuItem\StaticItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Useful CLI Menu Separation')
    ->addLineBreak()
    ->addStaticItem('Section 1')
    ->addStaticItem('---------')
    ->addItem('First Item')
    ->addItem('Second Item')
    ->addItem('Third Item')
    ->addLineBreak()
    ->addStaticItem('Section 2')
    ->addStaticItem('---------')
    ->addItem('Fourth Item')
    ->addItem('Fifth Item')
    ->addItem('Sixth Item')
    ->addLineBreak()
    ->addStaticItem('Section 3')
    ->addStaticItem('---------')
    ->addItem('Seventh Item')
    ->addItem('Eighth Item')
    ->addItem('Ninth Item')
    ->addLineBreak()
    ->addItemCallable($itemCallable)
    ->build();

$menu->display();
