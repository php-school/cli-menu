<?php
declare(strict_types=1);

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $username = $menu->askText()
        ->setPromptText('Enter username')
        ->setPlaceholderText('alice')
        ->ask();

    $age = $menu->askNumber()
        ->setPromptText('Enter age')
        ->setPlaceholderText('28')
        ->ask();

    $password = $menu->askPassword()
        ->setPromptText('Enter password')
        ->ask();

    var_dump($username->fetch(), $age->fetch(), $password->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('User Manager')
    ->addItem('Create New User', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
