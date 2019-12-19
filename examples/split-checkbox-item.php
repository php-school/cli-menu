<?php

use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Select a Language')
    ->addSplitItem(function (SplitItemBuilder $b) use ($itemCallable) {
        $b->setGutter(5)
            ->addCheckboxItem('Rust', $itemCallable)
            ->addCheckboxItem('C++', $itemCallable)
            ->addCheckboxItem('Go', $itemCallable)
            ->addCheckboxItem('Java', $itemCallable)
            ->addCheckboxItem('C', $itemCallable)
        ;
    })
    ->build();

$menu->open();
