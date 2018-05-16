# Upgrade Documentation

This document serves as a reference to upgrade your current cli-menu installation if improvements, deprecations
or backwards compatibility (BC) breakages occur.

## 3.0.0

### BC breaks

* Class `PhpSchool\CliMenu\CliMenuBuilder` has been moved, use 
  `PhpSchool\CliMenu\Builder\CliMenuBuilder` instead. Please migrate to the new namespace.
* Removed `PhpSchool\CliMenu\Terminal` namespace, the code has been migrated to the `php-school/terminal` package and is 
  largely modified.
* Method `addSubMenu` in '\PhpSchool\CliMenu\Builder\CliMenuBuilder' has an additional required parameter
  added at the beginning of the parameter list. It must be a unique ID for the submenu. The method can be found in the trait
  `\PhpSchool\CliMenu\Builder\BuilderUtils`.
