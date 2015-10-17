<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$art = <<<ART
        _ __ _
       / |..| \
       \/ || \/
        |_''_|

      PHP SCHOOL
LEARNING FOR ELEPHPANTS
ART;

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->addAsciiArt($art)
    ->addLineBreak("=")
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addLineBreak('-')
    ->setWidth(70)
    ->setBackgroundColour('green')
    ->build();

$menu->display();
