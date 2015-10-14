<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;
use MikeyMike\CliMenu\MenuItem\MenuItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$art = <<<ART
  _ __ _
 / |..| \
 \/ || \/
  |_''_|

PHP SCHOOL
ART;

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$subMenu = (new CliMenuBuilder($itemCallable))
    ->addAsciiArt($art)
    ->addLineBreak("=")
    ->addItem('Some shit')
    ->setAsSubMenu()
    ->build();

$menu = (new CliMenuBuilder($itemCallable))
    ->addAsciiArt($art)
    ->addLineBreak("=")
    ->addItem('First Item')
    ->addSubMenu('Sub Menu', $subMenu)
    ->setBackgroundColour('red')
    ->build();

$menu->display();