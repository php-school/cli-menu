<?php
declare(strict_types=1);

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Separation')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak()
    ->addItem('Fourth Item', $itemCallable)
    ->addItem('Fifth Item', $itemCallable)
    ->addItem('Sixth Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
