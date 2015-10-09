<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;
use MikeyMike\CliMenu\MenuItem\AsciiArtItem;
use MikeyMike\CliMenu\MenuItem\LineBreakItem;
use MikeyMike\CliMenu\MenuItem\MenuItem;
use MikeyMike\CliMenu\MenuStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$art = <<<ART
  _ __ _
 / |..| \
 \/ || \/
  |_''_|

PHP SCHOOL
ART;

$menu = (new CliMenuBuilder())
    ->addItem(new AsciiArtItem($art, AsciiArtItem::POSITION_CENTER))
    ->addItem(new LineBreakItem('='))
    ->addItem(new MenuItem('First Item'))
    ->addItem(new MenuItem('Second Item'))
    ->addItem(new MenuItem('Third Item'))
    ->setItemCallback(function (CliMenu $menu) {
        echo sprintf('You selected "%s"', $menu->getSelectedItem()->getText());
    })
    ->setMenuStyle(new MenuStyle('black', 'green', 50))
    ->build();

$menu->display();
