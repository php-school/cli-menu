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

$menu = (new CliMenuBuilder)
    ->addAsciiArt($art)
    ->addLineBreak("=")
    ->addItem('First Item')
    ->addItemCallable(function () {echo "LOL!";})
    ->addSubMenuAsAction('Sub Menu')
        ->addAsciiArt($art)
        ->addLineBreak("=")
        ->addItem('Some shit')
        ->addItemCallable(function () {echo "SUB LOL!";})
        ->addGoBackAction()
        ->end()
    ->setBackgroundColour('red')
    ->addGoBackAction()
    ->build();

$menu->display();