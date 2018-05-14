# Upgrade Documentation

This document serves as a reference to upgrade your current cli-menu installation if improvements, deprecations
or backwards compatibility (BC) breakages occur.

## 3.0.0

### BC breaks

* Class `PhpSchool\CliMenu\CliMenuBuilder` has been moved, use 
  `PhpSchool\CliMenu\Builder\CliMenuBuilder` instead. The old class has been aliased for now, but will be removed in 
  `3.1`.
* Removed `PhpSchool\CliMenu\Terminal` namespace, the code has been migrated to the `php-school/terminal` package and is 
  largely modified.