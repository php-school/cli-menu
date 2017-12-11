# Change Log
All notable changes to this project will be documented in this file.
Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [Unreleased][unreleased]
### Added

### Changed

### Fixed

### Removed

## [2.1.0]
### Changed
 - Use new static for submenu to allow subclassing (#68)
 
### Added
 - Add emacs style up/down shortcuts ctrl+n and ctrl+p (#67)

## [2.0.2]
### Fixed
 - Don't output ascii art if the terminal width is too small (#63)

## [2.0.1]
### Fixed
 - Reset array keys after removing an item from the menu (#61)

## [2.0.0]
### Fixed
 - PHPUnit deprecations - updated to createMock()
   
### Changed
 - Require ext-posix (#50)
 - Make MenuStyle easier to construct by only allowing changes to be made via setters (#45)
 
### Added
 - Added getStyle() to CliMenu to get access to the style object from the menu itself (#42)
 - Added redraw method to CliMenu which can redraw the menu immediately with any style changes. See 
   examples/crazy-redraw.php for an example (#43)
 - Added tests for child menu style inheritance (#44)
 - Add getter getItems() to get all items from the menu (#46)
 - Add method removeItem(ItemInterface $item) to remove an item from the menu (#46)
 - Ability to toggle item extra while the menu is open - see examples/toggle-item-extra.php (#46)
 - Added dialogues flash and confirm - they both display some text on top of the menu, flash is dismissed with 
   any key press where the confirm requires enter to be pressed on the provided button. 
   See examples/confirm.php and examples/flash.php (#49)
 
### Removed
 - Removed windows terminal - many required terminal features are unavailable (#50)
 - Individual component instantiation restrictions (#41)
 
 ## [1.2.0] 
 ### Added
  - Added ability to disable menu items and sub-menus, they will appear dimmed and will be un-selectable (#40)
