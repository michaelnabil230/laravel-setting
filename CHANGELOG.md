# Changelog

All notable changes to `setting` will be documented in this file.

## v2.0.0 - 2023-02-22

### What's Changed

- Bump dependabot/fetch-metadata from 1.3.4 to 1.3.5 by @dependabot in https://github.com/michaelnabil230/laravel-setting/pull/9
- Bump ramsey/composer-install from 1 to 2 by @dependabot in https://github.com/michaelnabil230/laravel-setting/pull/10
- Bump dependabot/fetch-metadata from 1.3.5 to 1.3.6 by @dependabot in https://github.com/michaelnabil230/laravel-setting/pull/12
- Upgrade package by @michaelnabil230 in https://github.com/michaelnabil230/laravel-setting/pull/13

### New Contributors

- @michaelnabil230 made their first contribution in https://github.com/michaelnabil230/laravel-setting/pull/13

**Full Changelog**: https://github.com/michaelnabil230/laravel-setting/compare/v1.2.6...v2.0.0

## v1.2.6 - 2022-10-22

### What's Changed

- Bump aglipanci/laravel-pint-action from 0.1.0 to 1.0.0 by @dependabot in https://github.com/michaelnabil230/laravel-setting/pull/7
- Bump dependabot/fetch-metadata from 1.3.3 to 1.3.4 by @dependabot in https://github.com/michaelnabil230/laravel-setting/pull/8
- Format workflow
- Rename file `AbstractStore` to `SettingStore`
- Added method `setExtraColumns` in `SettingStore`
- Fix types in the helper setting
- Some Improvements in `RedisSettingStore`

**Full Changelog**: https://github.com/michaelnabil230/laravel-setting/compare/v1.2.5...v1.2.6

## v1.2.5 - 2022-07-22

### Added

- Add  workflow of `laravel/pint`

### Changed

- Change the name of all classes in the package from `LaravelSetting` to `Setting`

**Full Changelog**: https://github.com/michaelnabil230/laravel-setting/compare/v1.2.4...v1.2.5

## v1.2.4 - 2022-07-15

### What's Changed

- Bump dependabot/fetch-metadata from 1.3.1 to 1.3.3 by @dependabot in https://github.com/michaelnabil230/laravel-setting/pull/6

**Full Changelog**: https://github.com/michaelnabil230/laravel-setting/compare/v1.2.3...v1.2.4

## v1.2.3 - 2022-06-03

**Full Changelog**: https://github.com/michaelnabil230/laravel-setting/compare/v1.2.2...v1.2.3

## [v1.2.2](https://github.com/michaelnabil230/laravel-setting/commit/d067645b6706929afdfaafa6dd5f347ef1bd69eb) - 2022-05-09

### Changed

- Move file `helpers` to `src\helpers.php`

## [v1.2.1](https://github.com/michaelnabil230/laravel-setting/commit/e0c16dfe8a7648a436360fa91cfa5eb2b11d679e) - 2022-05-09

### Fixed

- Fix the `DataBase` mode

## [v1.2.0](https://github.com/michaelnabil230/laravel-setting/commit/00b42f635171f3063137608d378004495ba722f8) - 2022-04-22

### Added

- Add `flip` is a helper function flipping a boolean the key in the setting
- Add `enable` is a helper function that can enable the key in the setting
- Add `disable` is a helper function that can disable the key in the setting
- Add the new package `friendsofphp/php-cs-fixer` in `require-dev` for formatting code

### Changed

- Rename the filename of fixer style and everything related to it

### Fixed

- Fix the types in helper file

## 1.0.0 - 2022-01-07

- initial release
