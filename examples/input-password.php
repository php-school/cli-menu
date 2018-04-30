<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $result = $menu->askPassword()
        ->setPlaceholderText('')
        ->setValidator(function ($password) {
            if ($password === 'password') {
                $this->setValidationFailedText('Password is too weak');
                return false;
            } else if (strlen($password) <= 6) {
                $this->setValidationFailedText('Password is not long enough');
                return false;
            } 
            
            return true;
        })
        ->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter password', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
