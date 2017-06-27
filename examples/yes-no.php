<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $menu->yesNo(sprintf('switch [%s]?!', 6789))
        ->setYesText('OK')
        ->setNoText('Cancel')
        ->display(function ($res) {
            var_dump($res);
        });
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('YesNo Show Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
