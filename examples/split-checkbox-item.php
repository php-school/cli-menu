<?php
declare(strict_types=1);

use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

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
