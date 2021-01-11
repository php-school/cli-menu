<p align="center">
    <img src="https://cloud.githubusercontent.com/assets/2174476/10601666/071e3e24-770b-11e5-9cba-8ae6402ff550.gif" width="300" />
</p>

<p align="center">
    <a href="https://travis-ci.org/php-school/cli-menu" title="Build Status" target="_blank">
     <img src="https://img.shields.io/travis/php-school/cli-menu/master.svg?style=flat-square&label=Linux" />
    </a>
    <a href="https://ci.appveyor.com/project/mikeymike/cli-menu" title="Windows Build Status" target="_blank">
     <img src="https://img.shields.io/appveyor/ci/mikeymike/cli-menu/master.svg?style=flat-square&label=Windows" />
    </a>
    <a href="https://codecov.io/github/php-school/cli-menu" title="Coverage Status" target="_blank">
     <img src="https://img.shields.io/codecov/c/github/php-school/cli-menu.svg?style=flat-square" />
    </a>
    <a href="https://scrutinizer-ci.com/g/php-school/cli-menu/" title="Scrutinizer Code Quality" target="_blank">
     <img src="https://img.shields.io/scrutinizer/g/php-school/cli-menu.svg?style=flat-square" />
    </a>
    <a href="https://phpschool-team.slack.com/messages">
      <img src="https://phpschool.herokuapp.com/badge.svg">
    </a>
</p>

---
## Contents

  * [Minimum Requirements](#minimum-requirements)
  * [Installation](#installation)
  * [Upgrading](#upgrading)
  * [Usage](#usage)
    * [Quick Setup](#quick-setup)
    * [Examples](#examples)
  * [API](#api)
    * [Appearance](#appearance)
      * [Menu Title](#menu-title)
      * [Colour](#colour)
      * [Width](#width)
      * [Padding](#padding)
      * [Margin](#margin)
      * [Borders](#borders)
      * [Exit Button Text](#exit-button-text)
      * [Remove Exit Button](#remove-exit-button)
    * [Items](#appearance)
      * [Selectable Item](#selectable-item)
      * [Checkbox Item](#checkbox-item)
      * [Radio Item](#radio-item)
      * [Line Break Item](#line-break-item)
      * [Static Item](#static-item)
      * [Ascii Art Item](#ascii-art-item)
      * [Sub Menu Item](#sub-menu-item)
      * [Split Item](#split-item)
    * [Disabling Items & Sub Menus](#disabling-items--sub-menus)
    * [Item Markers](#item-markers)
    * [Item Extra](#item-extra)
    * [Menu Methods](#menu-methods)
      * [Redrawing the Menu](#redrawing-the-menu)
      * [Getting, Removing and Adding items](#getting-removing-and-adding-items)
    * [Custom Control Mapping](#custom-control-mapping)
    * [Item Keyboard Shortcuts](#item-keyboard-shortcuts)
    * [Dialogues](#dialogues)
      * [Flash](#flash)
      * [Confirm](#confirm)
    * [Inputs](#inputs)
      * [Text](#text-input)
      * [Number](#number-input)
      * [Password](#password-input)
      * [Custom Input](#custom-input)
    * [Dialogues & Input Styling](#dialogues--input-styling)
  * [Docs Translations](#docs-translations)
  * [Integrations](#integrations)

## Minimum Requirements

 * PHP 7.1
 * Composer
 * ext-posix

## Installation

```bash
composer require php-school/cli-menu
```

## Upgrading

Please refer to the [Upgrade Documentation](UPGRADE.md) documentation to see what is required to upgrade your installed 
`cli-menu` version.

## Usage

### Quick Setup
Here is a super basic example menu which will echo out the text of the selected item to get you started.
```php
<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->setBorder(1, 2, 'yellow')
    ->setPadding(2, 4)
    ->setMarginAuto()
    ->build();


$menu->open();
```

### Examples

Check out the [examples](examples) directory and run them to see what is possible! The best way to run the examples is to git clone the repository:

```shell
git clone https://github.com/php-school/cli-menu.git
cd cli-menu
composer install --no-dev
cd examples
php basic.php
```

#### Basic Menu 
<img width="600" alt="basic" src="https://cloud.githubusercontent.com/assets/2817002/11442386/cb0e41a2-950c-11e5-8dd6-913aeab1632a.png">

#### Basic Menu Auto Centered
<img width="600" alt="submenu" src="https://user-images.githubusercontent.com/2817002/39929334-1c68a450-5538-11e8-947a-e97b21c4fa1a.png">

#### Basic Menu with separation
<img width="600" alt="basic-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442388/cdece950-950c-11e5-8128-4f849a1aea9f.png">

#### Menu with crazy separation
<img width="600" alt="crazy-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442389/d04627fc-950c-11e5-8c80-f82b8fe3f5da.png">

#### Custom Styles
<img width="600" alt="custom-styles" src="https://cloud.githubusercontent.com/assets/2817002/11442391/d3d72d1c-950c-11e5-9698-c2aeec002b24.png">

#### Borders and 256 colours
<img width="600" alt="submenu" src="https://user-images.githubusercontent.com/2817002/39929340-1cee9fce-5538-11e8-91de-eccac734f2a5.png">

#### Useful Separation
<img width="600" alt="useful-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442393/d862c72e-950c-11e5-8cbc-d8c73899627a.png">

#### Displaying Item Extra
<img width="600" alt="item-extra" src="https://cloud.githubusercontent.com/assets/2817002/11442395/dfe460f2-950c-11e5-9aed-9bc9c91b7ea6.png">

#### Remove Defaults
<img width="600" alt="remove-defaults" src="https://cloud.githubusercontent.com/assets/2817002/11442399/e3e8b8a6-950c-11e5-8dad-fdd4db93b850.png">

#### Submenu
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/11442401/e6f03ef2-950c-11e5-897a-6d55496a4105.png">
<img width="600" alt="submenu-options" src="https://cloud.githubusercontent.com/assets/2817002/11442403/eaf4782e-950c-11e5-82c5-ab57f84cd6bc.png">

#### Split Item
<img width="600" alt="split-item" src="https://user-images.githubusercontent.com/5318258/40056391-0b3383c8-5897-11e8-852c-f0df820a040f.png">

#### Disabled Items & Submenus
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2174476/19047849/868fa8c0-899b-11e6-9004-811c8da6d435.png">

#### Checkbox Items
<img width="600" alt="checkbox" src="https://user-images.githubusercontent.com/2817002/74604044-62cd9200-50ba-11ea-941f-377a51c9dcfd.png">
<img width="600" alt="checkbox-split" src="https://user-images.githubusercontent.com/2817002/74604048-63febf00-50ba-11ea-9b20-39eb3a79989d.png">

#### Radio Items

<img width="600" alt="radio" src="https://user-images.githubusercontent.com/2817002/74604049-652fec00-50ba-11ea-8361-faf325245793.png">
<img width="600" alt="radio-split" src="https://user-images.githubusercontent.com/2817002/74604050-65c88280-50ba-11ea-9ee0-d4ea654e5a87.png">

#### Flash Dialogue
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/19786090/1f07dad6-9c94-11e6-91b0-c20ab2e6e27d.png">

#### Confirm Dialogue
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/19786092/215d2dc2-9c94-11e6-910d-191b7b74f4d2.png">

#### Number Input
<img width="600" alt="submenu" src="https://user-images.githubusercontent.com/2817002/39929969-bf45be46-5539-11e8-99f5-3dfdc0cf1fe3.png">
<img width="600" alt="submenu" src="https://user-images.githubusercontent.com/2817002/39930002-d2353dd8-5539-11e8-8dfb-e404201b7c42.png">

#### Text Input
<img width="600" alt="submenu" src="https://user-images.githubusercontent.com/2817002/39929338-1cbc5a28-5538-11e8-9a68-b4e84e772251.png">

#### Password Input
<img width="600" alt="submenu" src="https://user-images.githubusercontent.com/2817002/39929339-1cd4cd2e-5538-11e8-9330-335dd1b7ed1c.png">

#### Using cli-menu to create art

Want to see something really cool? Well you can use cli-menu to create a drawing canvas on your terminal. Check it out!:

<img width="600" alt="submenu" src="https://user-images.githubusercontent.com/2817002/40175009-15e1e6c0-59d7-11e8-914f-c9136fd417b4.png">

## API

The `CliMenu` object is constructed via the Builder class

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    /**
     *  Customise
    **/
    ->build();
```

Once you have a menu object, you can open and close it like so:

```php
$menu->open();
$menu->close();
```

### Appearance

#### Menu Title

You can give your menu a title and you can customise the separator, a line which displays under the title.
Whatever string you pass to `setTitleSeparator` will be repeated for the width of the Menu.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setTitle('One Menu to rule them all!')
    ->setTitleSeparator('*-')
    ->build();
```

#### Colour

You can change the foreground and background colour of the menu to any of the following colours:

* black
* red
* green
* yellow
* blue
* magenta
* cyan
* white

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setForegroundColour('green')
    ->setBackgroundColour('black')
    ->build();
```

If your terminal supports 256 colours then you can also use any of those by specifying the code, like `230`. You can find a list
of the [colours and codes here](https://jonasjacek.github.io/colors/). If you specify a code and the terminal does not support 256 colours
it will automatically fallback to a sane default, using a generated map you can see in src/Util/ColourUtil.php. You can also manually specify the
fallback colour as the second argument to `setForegroundColour` and `setBackgroundColour.

In this example if no 256 colour support is found it will automatically fall back to `green` and `blue`.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setForegroundColour('40')
    ->setBackgroundColour('92')
    ->build();
```

In this example if no 256 colour support is found it will fall back to `yellow` and `magenta`.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setForegroundColour('40', 'yellow')
    ->setBackgroundColour('92', 'magenta')
    ->build();
```

#### Width

Customise the width of the menu. Setting a value larger than the size of the terminal will result in
the width being the same as the terminal size. The width will include the padding and the border. So with a width of 100
and all around border of 5 and all around padding of 5 will leave for a content width of 80 (5 + 5 + 80 + 5 + 5).

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setWidth(1000) //if terminal is only 400, width will also be 400
    ->build();
```

If you want to use the full width of the terminal, you can grab the terminal object and ask/set it from there like so:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = ($builder = new CliMenuBuilder)
    ->setWidth($builder->getTerminal()->getWidth())
    ->build();
```

If you want to use the full width of the terminal and apply a margin, use the terminal width, and we will do the calculations
automatically (shrink the width based on the margin).

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = ($builder = new CliMenuBuilder)
    ->setWidth($builder->getTerminal()->getWidth())
    ->setMargin(2)
    ->build();
```

#### Padding

The padding can be set for all sides with one value or can be set individually for top/bottom and left/right.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setPadding(10) //10 padding top/bottom/left/right
    ->build();
```

Different values can also be set for the top/bottom and the left/right padding:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setPaddingTopBottom(10)
    ->setPaddingLeftRight(5)
    ->build();
```

Configure top/bottom and left/right padding using the shorthand method:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setPadding(10, 5) //top/bottom = 10, left/right = 5
    ->build();
```

#### Margin

The margin can be customised as one value. It can also be set automatically which will center the menu nicely in the 
terminal.

Automatically center menu:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setWidth(200)
    ->setMarginAuto() 
    ->build();
```

Arbitrary margin:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setWidth(200)
    ->setMargin(5)
    ->build();
```

#### Borders

Borders can be customised just like CSS borders. We can add any amount of border to either side, left, right top or 
bottom and we can apply a colour to it.

Set universal red border of 2:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setWidth(200)
    ->setBorder(2, 'red')
    ->build();
```

Configure each border separately:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setWidth(200)
    ->setBorderTopWidth(2)
    ->setBorderRightWidth(4)
    ->setBorderBottomWidth(2)
    ->setBorderLeftWidth(4)
    ->setBorderColour('42', 'red') //SpringGreen2 fallback to red
    ->build();
```

Configure each border separately using the shorthand method, like CSS:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setWidth(200)
    ->setBorder(3, 4, 'red') //top/bottom = 3, left/right = 4
    ->setBorder(3, 4, 5, 'red') //top = 3, left/right = 4, bottom = 5
    ->setBorder(3, 4, 5, 6, 'red') //top = 3, left = 4, bottom = 5, right = 6
    ->build();
```

#### Exit Button Text

Modify the exit button text:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->setExitButtonText("Don't you want me baby?")
    ->build();
```

#### Remove Exit Button

You can remove the exit button altogether:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->disableDefaultItems()
    ->build();
```

Note: This will also disable the Go Back button for sub menus. 

You can manually add exit and go back buttons using the following:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Action\ExitAction;
use PhpSchool\CliMenu\Action\GoBackAction;

$menu = (new CliMenuBuilder)
    ->disableDefaultItems()
    ->addSubMenu('Super Sub Menu', function (CliMenuBuilder $b) {
        $b->disableDefaultItems()
            ->setTitle('Behold the awesomeness')
            ->addItem('Return to parent menu', new GoBackAction); //add a go back button
    })
    ->addItem('Leave this place now !', new ExitAction) //add an exit button
    ->build();
```

### Items

There a few different types of items you can add to your menu

* Selectable Item - This is the type of item you need for something to be selectable (you can hit enter and it will invoke your callable) 
* Checkbox Item - This is a checkbox type of item that keeps track of its toggled state to show a different marker.
* Radio Item - This is a radio type of item that keeps track of its toggled state to show a different marker. Disables all other radios within its `CliMenu` level.
* Line Break Item - This is used to break up areas, it can span multiple lines and will be the width of Menu. Whatever string is passed will be repeated.
* Static Item - This will print whatever text is passed, useful for headings.
* Ascii Art Item - Special item which allows usage of Ascii art. It takes care of padding and alignment.
* Sub Menu Item - Special item to allow an item to open another menu. Useful for an options menu.
* Split Item - Special item to fit multiple items on the same row.

### Selectable Item

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$menu = (new CliMenuBuilder)
    ->addItem('The Item Text', function (CliMenu $menu) { 
        echo 'I am alive!'; 
    })
    ->build();
```

You can add multiple items at once like so:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$callable = function (CliMenu $menu) {
    echo 'I am alive!';
};

$menu = (new CliMenuBuilder)
    ->addItems([
        ['Item 1', $callable],
        ['Item 2', $callable],
        ['Item 3', $callable],
    ])
    ->build();
```

Note: You can add as many items as you want and they can all have a different action. The action is the second parameter
and must be a valid PHP `callable`. Try using an `Invokable` class to keep your actions easily testable.

### Checkbox Item

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$callable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->addCheckboxItem('Item 1', $callable)
    ->addCheckboxItem('Item 2', $callable)
    ->addCheckboxItem('Item 3', $callable)
    ->build();
```

You can add multiple checkbox items at once like so:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$callable = function (CliMenu $menu) {
    echo 'I am alive!';
};

$menu = (new CliMenuBuilder)
    ->addCheckboxItems([
        ['Item 1', $callable],
        ['Item 2', $callable],
        ['Item 3', $callable],
    ])
    ->build();
```

When selecting an item, it will be toggled. Notice at first each item is unchecked. After selecting one it will become
checked.

### Radio Item

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$callable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->addRadioItem('Item 1', $callable)
    ->addRadioItem('Item 2', $callable)
    ->addRadioItem('Item 3', $callable)
    ->build();
```

You can add multiple radio items at once like so:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$callable = function (CliMenu $menu) {
    echo 'I am alive!';
};

$menu = (new CliMenuBuilder)
    ->addRadioItems([
        ['Item 1', $callable],
        ['Item 2', $callable],
        ['Item 3', $callable],
    ])
    ->build();
```

When selecting an item, it will be toggled. Notice at first each item is unchecked. After selecting one it will become
checked and all other `RadioItem` within the same level will be unchecked.

### Line Break Item

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->addLineBreak('<3', 2)
    ->build();
```

The above would repeat the character sequence `<3` across the Menu for 2 lines

### Static Item

Static items are similar to Line Breaks, however, they don't repeat and fill. It is output as is.
If the text is longer than the width of the Menu, it will be continued on the next line.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$menu = (new CliMenuBuilder)
    ->addStaticItem('AREA 1')
    //add some items here
    ->addStaticItem('AREA 2')
    //add some boring items here
    ->addStaticItem('AREA 51')
    //add some top secret items here 
    ->build();
```

### Ascii Art Item

The following will place the Ascii art in the centre of your menu. Use these constants to alter the 
alignment:

* AsciiArtItem::POSITION_CENTER
* AsciiArtItem::POSITION_LEFT
* AsciiArtItem::POSITION_RIGHT

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;

$art = <<<ART
        _ __ _
       / |..| \
       \/ || \/
        |_''_|
      PHP SCHOOL
LEARNING FOR ELEPHANTS
ART;

$menu = (new CliMenuBuilder)
    ->addAsciiArt($art, AsciiArtItem::POSITION_CENTER)
    ->build();
```

The third optional parameter to `addAsciiArt` is alternate text. If the ascii art is too wide for the terminal, then 
it will not be displayed at all. However, if you pass a string to the third argument, in the case that the ascii art is too 
wide for the terminal the alternate text will be displayed instead.

### Sub Menu Item

Sub Menus are really powerful! You can add Menus to Menus, whattttt?? You can have your main menu and then an options menu.
The options item will look like a normal item except when you hit it, you will enter to another menu, which
can have different styles and colours!

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$callable = function (CliMenu $menu) {
    echo "I'm just a boring selectable item";
};

$menu = (new CliMenuBuilder)
    ->addItem('Normal Item', $callable)
    ->addSubMenu('Super Sub Menu', function (CliMenuBuilder $b) {
        $b->setTitle('Behold the awesomeness')
            ->addItem(/** **/);
    })
    ->build();
```

In this example a single sub menu will be created. Upon entering the sub menu, you will be able to return to the main menu
or exit completely. A Go Back button will be automatically added. You can customise this text using the `->setGoBackButtonText()` method on the `CliMenuBuilder` 
instance for the sub menu.

There are a few things to note about the syntax and builder process here

1. The first parameter to `addSubMenu` is the text to be displayed on the menu which you select to enter the submenu. 
2. The second parameter is a closure, which will be invoked with a new instance of `CliMenuBuilder` which you can use to customise the sub menu exactly the same way you would the parent
3. If you do not modify the styles of the sub menu (eg, colours) it will inherit styles from the parent!

If you have already have a configured menu builder you can just pass that to `addSubMenuFromBuilder` and be done:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

$subMenuBuilder = (new CliMenuBuilder)
    ->setTitle('Behold the awesomeness')
    ->addItem(/** **/);

$menu = (new CliMenuBuilder)
    ->addSubMenuFromBuilder('Super Sub Menu', $subMenuBuilder)
    ->build();
```

Note: The submenu menu item will be an instance of `\PhpSchool\CliMenu\MenuItem\MenuMenuItem`. If you need access to the submenu,
you can get it via `$menuMenuItem->getSubMenu()`.

### Split Item

Split Items allows you to add multiple items on the same row. The full width of the menu will be split evenly between all items. You can move between those items using left/right arrows.

You can set the number of spaces separating items using `->setGutter()` (defaults to 2).

Only Selectable, Checkbox, Radio, Static and SubMenu items are currently allowed inside a Split Item.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setWidth(150)
    ->addStaticItem('Below is a SplitItem')
    ->addSplitItem(function (SplitItemBuilder $b) use ($itemCallable) {
        $b->setGutter(5)
            ->addSubMenu('Sub Menu on a split item', function (CliMenuBuilder $b) {
                $b->setTitle('Behold the awesomeness')
                    ->addItem('This is awesome', function() { print 'Yes!'; });
            })
            ->addItem('Item 2', $itemCallable)
            ->addStaticItem('Item 3 - Static');
    })
    ->build();

$menu->open();
```

There are a few things to note about the syntax and builder process here:

1. The first parameter to `addSplitItem` is a closure, which will be invoked with a new instance of `SplitItemBuilder` which you can use to add items to the split item.
2. You can call `addItem`, `addCheckboxItem`, `addRadioItem`, `addSubMenu` and `addStaticItem` on the `SplitItemBuilder`. 
3. `SplitItemBuilder` has a fluent interface so you can chain method calls.

### Disabling Items & Sub Menus

In this example we are disabling certain items and a submenu but still having them shown in the menu. 

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Disabled Items')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable, false, true)
    ->addItem('Third Item', $itemCallable, false, true)
    ->addSubMenu('Submenu', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Basic CLI Menu Disabled Items > Submenu')
            ->addItem('You can go in here!', $itemCallable);
    })
    ->addSubMenu('Disabled Submenu', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Basic CLI Menu Disabled Items > Disabled Submenu')
            ->addItem('Nope can\'t see this!', $itemCallable)
            ->disableMenu();
    })
    ->addLineBreak('-')
    ->build();
```

The third param on the `->addItem` call is what disables an item while the `->disableMenu()` call disables the relevant menu. 

The outcome is a full menu with dimmed rows to denote them being disabled. When a user navigates the menu these items are jumped over to the next available selectable item.

### Item Markers

The marker displayed by the side of the currently active item can be modified, UTF-8 characters are supported.
The marker for un-selected items can also be modified. If you want to disable it, just set it to an empty string. Item
markers only display on *selectable* items, which are: `\PhpSchool\CliMenu\MenuItem\SelectableItem` & `\PhpSchool\CliMenu\MenuItem\MenuMenuItem`.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\SelectableStyle;

$menu = (new CliMenuBuilder)
    ->modifySelectableStyle(function (SelectableStyle $style) {
        $style->setUnselectedMarker('❅ ')
            ->setSelectedMarker('✏ ')

            // disable unselected marker
            ->setUnselectedMarker('')
        ;
    })
    ->build();
```

You may also change the marker for `\PhpSchool\CliMenu\MenuItem\CheckboxItem`:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\CheckboxStyle;

$menu = (new CliMenuBuilder)
    ->modifyCheckboxStyle(function (CheckboxStyle $style) {
        $style->setUncheckedMarker('[○] ')
            ->setCheckedMarker('[●] ');
    })
    ->addCheckboxItem('Orange juice', function () {})
    ->addCheckboxItem('Bread', function () {})
    ->build();
```

and for `\PhpSchool\CliMenu\MenuItem\RadioItem`:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\RadioStyle;

$menu = (new CliMenuBuilder)
    ->modifyRadioStyle(function (RadioStyle $style) {
        $style->setUncheckedMarker('[ ] ')
            ->setCheckedMarker('[✔] ');
    })
    ->addRadioItem('Go shopping', function () {})
    ->addRadioItem('Go camping', function () {})
    ->build();
```

### Item Extra

You can optionally display some arbitrary text on the right hand side of an item. You can customise this text and
you indicate which items to display it on. We use it to display `[COMPLETED]` on completed exercises, where the menu lists
exercises for a workshop application.

Item Extra is currently limited to only selectable items (menus, checkboxes & radios included)

The third parameter to `addItem` is a boolean whether to show the item extra or not. It defaults to false.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Style\SelectableStyle;

$menu = (new CliMenuBuilder)
    ->modifySelectableStyle(function (SelectableStyle $style) {
        $style->setItemExtra('✔');
    })
    ->addItem('Exercise 1', function (CliMenu $menu) { echo 'I am complete!'; }, true)
    ->build();
```

If no items have display extra set to true, then the item extra will not be displayed. If you toggle the item to show
it's item extra in a callback or at runtime it will render incorrectly.

In order to fix that you need to tell the menu to display item extra explicitly. You can do this when constructing the
menu like so:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$menu = (new CliMenuBuilder)
    ->setItemExtra('✔')
    ->addItem('Exercise 1', function (CliMenu $menu) { 
        $selectedItem = $menu->getSelectedItem();
        if ($selectedItem->showsItemExtra()) {
            $selectedItem->hideItemExtra();
        } else {
            $selectedItem->showItemExtra();
        }       
    })
    ->displayExtra()
    ->build();
```

## Menu Methods

The next set of documentation applies to methods available directly on the `\PhpSchool\CliMenu\CliMenu` instance. Typically
you will invoke these methods whilst your menu is open in you action callbacks.

### Redrawing the Menu

You can modify the menu and its style when executing an action and then you can redraw it! In this example we will toggle the background
colour in an action.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    $menu->getStyle()->setBg($menu->getStyle()->getBg() === 'red' ? 'blue' : 'red');
    $menu->redraw();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

If you change the menu drastically, such as making the width smaller, when it redraws you might see artifacts of the previous draw
as `redraw` only draws over the top of the terminal. If this happens you can pass `true` to `redraw` and it will first clear
the terminal before redrawing.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    $menu->getStyle()->setWidth($menu->getStyle()->getWidth() === 100 ? 80 : 100);
    $menu->redraw(true);
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

### Getting, Removing and Adding items

You can also interact with the menu items in an action. You can add, remove and replace items. If you do this, you 
will likely want to redraw the menu as well so the new list is rendered. 

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;

$itemCallable = function (CliMenu $menu) {
    foreach ($menu->getItems() as $item) {
        $menu->removeItem($item);
    }
    
    //add single item
    $menu->addItem(new LineBreakItem('-'));
    
    //add multiple items
    $menu->addItems([new LineBreakItem('-'), new LineBreakItem('*')]);
    
    //replace all items
    $menu->setItems([new LineBreakItem('+'), new LineBreakItem('-')]);

    $menu->redraw();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

## Custom Control Mapping

This functionality allows to map custom key presses to a callable. For example we can set the key press "x" to close the menu:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$exit = function(CliMenu $menu) {
    $menu->close();
};

$menu = (new CliMenuBuilder)
    ->addItem('Item 1', function(CliMenu $menu) {})
    ->build();

$menu->addCustomControlMapping("x", $exit);

$menu->open();
```

Another example is mapping shortcuts to a list of items:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$myCallback = function(CliMenu $menu) {
    echo "Client 1\nClient 2\nClient 3\n";
};

$menu = (new CliMenuBuilder)
    ->addItem('List of [C]lients', $myCallback)
    ->build();

// Now, pressing Uppercase C (it's case sensitive) will call $myCallback
$menu->addCustomControlMapping('C', $myCallback);

$menu->open();
```

## Item Keyboard Shortcuts

If you enable auto shortcuts `CliMenuBuilder` will parse the items text and check for shortcuts. Any single character inside square brackets
will be treated as a shortcut. Pressing that character when the menu is open will trigger that items callable.

This functionality works for split items as well as sub menus. The same characters can be used inside sub menus and the
callable which is invoked will depend on which menu is currently open. 

Note: all shortcuts are lower cased.

To enable this automatic keyboard shortcut mapping simply call `->enableAutoShortcuts()`:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$myCallback = function(CliMenu $menu) {
    echo "Client 1\nClient 2\nClient 3\n";
};

$menu = (new CliMenuBuilder)
    ->enableAutoShortcuts()
    ->addItem('List of [C]lients', $myCallback)
    ->build();

$menu->open();

//Pressing c will execute $myCallback.
```

You can customise the shortcut matching by passing your own regex to `enableAutoShortcuts`. Be careful to only match 
one character in the first capture group or an exception will be thrown.

### Dialogues

#### Flash

Show a one line message over the top of the menu. It has a separate style object which is colored by default different
to the menu. It can be modified to suit your own style. The dialogue is dismissed with any key press. In the example
below we change the background color on the flash to green.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

require_once(__DIR__ . '/../vendor/autoload.php');
    
$itemCallable = function (CliMenu $menu) {
    $flash = $menu->flash("PHP School FTW!!");
    $flash->getStyle()->setBg('green');
    $flash->display();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

#### Confirm

Prompts are very similar to flashes except that a button is shown which has to be selected to dismiss them. The button
text can be customised.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    $menu->confirm('PHP School FTW!')
        ->display('OK!');
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```
### Inputs

Inputs - added in version 3.0 of `cli-menu` allow to prompt the user for input and validate it. The following types are supported:
text, number and password. Inputs can be executed in any item callback. They have separate style objects which are colored by default different to the menu.
They can be modified to suit your own style.

Each input is created by calling one of the `ask*` methods which will return an
instance of the input you requested. To execute the prompt and wait for the input you must
call `ask()` on the input. When the input has been received and validated, `ask()` will return
an instance of `InputResult`. `InputResult` exposes the method `fetch` to grab the raw input.

#### Text Input

The text input will prompt for a string and when the enter key is hit it will validate that
the string is not empty. As well as the style you can modify the prompt text (the default is 'Enter text:'), the 
placeholder text (the default is empty) and the validation failed text (the default is 'Invalid, try again').

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    $result = $menu->askText()
        ->setPromptText('Enter your name')
        ->setPlaceholderText('Jane Doe')
        ->setValidationFailedText('Please enter your name')
        ->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter text', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();

```

#### Number Input

The number input will prompt for an integer value (signed or not) and when the enter key is hit it will validate that
the input is actually a number (`/^-?\d+$/`). As well as the style you can modify the prompt text (the default is 'Enter a number:'), the 
placeholder text (the default is empty) and the validation failed text (the default is 'Not a valid number, try again').

When entering a number you can use the up/down keys to increment and decrement the number.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    $result = $menu->askNumber()
        ->setPromptText('Enter your age')
        ->setPlaceholderText(10)
        ->setValidationFailedText('Invalid age, try again')
        ->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter number', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();

```

#### Password Input

The password input will prompt for a text value and when the enter key is hit it will validate that the input is 16 characters or longer.
As well as the style you can modify the prompt text (the default is 'Enter password:'), the 
placeholder text (the default is empty) and the validation failed text (the default is 'Invalid password, try again'). You can also set
a custom password validator as a PHP callable. When typing passwords they are echo'd back to the user as an asterisk. 

Ask for a password with the default validation:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    $result = $menu->askPassword()
        ->setPromptText('Please enter your password')
        ->setValidationFailedText('Invalid password, try again')
        ->setPlaceholderText('')
        ->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter password', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();

```

Validators can be any PHP callable. The callable will be passed the input value and must return a boolean, false indicating
validation failure and true indicating validation success. If validation fails then the validation failure text will be shown.

It is also possible to customise the validation failure message dynamically, but only when using a `Closure` as a validator.
The closure will be binded to the `Password` input class which will allow you to call `setValidationFailedText` inside the closure.

Ask for a password with custom validation. Here we validate the password is not equal to `password` and that the
password is longer than 20 characters.

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    $result = $menu->askPassword()
        ->setPromptText('Please enter your password')
        ->setValidationFailedText('Invalid password, try again')
        ->setPlaceholderText('')
        ->setValidator(function ($password) {
            return $password !== 'password' && strlen($password) > 20;            
        })
        ->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter password', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();

```

Ask for a password with custom validation and set the validation failure message dynamically:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

$itemCallable = function (CliMenu $menu) {
    $result = $menu->askPassword()
        ->setPromptText('Please enter your password')
        ->setValidationFailedText('Invalid password, try again')
        ->setPlaceholderText('')
        ->setValidator(function ($password) {
            if ($password === 'password') {
                $this->setValidationFailedText('Password is too weak');
                return false;
            } else if (strlen($password) <= 20) {
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
    ->addLineBreak('-')
    ->build();

$menu->open();

```

#### Custom Input

If you need a new type of input which is not covered by the bundled selection then you can create your own by implementing
`\PhpSchool\CliMenu\Input\Input` - take a look at existing implementations to see how they are built. If all you need is some custom
validation - extend the `\PhpSchool\CliMenu\Input\Text` class and overwrite the `validate` method. You can then use it in
your menu item actions like so:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Input\Text;
use PhpSchool\CliMenu\Input\InputIO;

$itemCallable = function (CliMenu $menu) {
    
    $style = (new MenuStyle())
        ->setBg('yellow')
        ->setFg('black');
        
    $input = new class (new InputIO($menu, $menu->getTerminal()), $style) extends Text {
        public function validate(string $value) : bool
        {
            //some validation
            return true;
        }
    };
    
    $result = $input->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter password', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();

```

### Dialogues & Input Styling

All of the dialogues and inputs expose a `getStyle()` method which you can use to customise the appearance of them. However, if
you want to create a consistent style for all your dialogues and inputs without configuring it for each one
you can build up a `MenuStyle` object and pass it to the dialogue and input methods like so:

```php
<?php

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;

$popupStyle = (new MenuStyle)
    ->setBg('green')
    ->setFg('magenta');
    
$itemCallable = function (CliMenu $menu) use ($popupStyle) {
    $menu->flash("PHP School FTW!!", $popupStyle)->display();
    $menu->confirm('PHP School FTW!', $popupStyle)->display('OK!');
    $menu->askNumber($popupStyle)->ask();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

---

Once you get going you might just end up with something that looks a little like this... 

![Learn You PHP CLI Menu](https://cloud.githubusercontent.com/assets/2174476/11409864/be082444-93ba-11e5-84ab-1b6cfa38aef8.png)

You can see the construction code here for more clarity on how to perform advanced configuration:
[PHP School](https://github.com/php-school/php-workshop/blob/3240d3217bbf62b1063613fc13eb5adff2299bbe/src/Factory/MenuFactory.php)

## Integrations

 * [Symfony Console](https://github.com/RedAntNL/console)
 * [Laravel](https://github.com/nunomaduro/laravel-console-menu)
 * [Laravel Artisan](https://github.com/VladReshet/ArtisanUI)
