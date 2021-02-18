Changelog
=========

## x.y.z - UNRELEASED

--------

## 2.1.0 - 2021-02-18

### Added

* Added interfaces for the Message, Parser, and Seralizer classes.

--------

## 2.0.0 - 2019-03-08

### Changed

* Added parameter types and return types where possible.
* [Support] Added support for PHP 7.3
* [Support] Dropped support for PHP 5.6
* [Support] Dropped support for PHP 7.0
* [Support] Dropped support for PHP 7.1

--------

## 1.1.0 - 2018-06-25

### Changed

* [Parser] Huge performance improvements, large files in particular are now parsed over 30 times faster.
* [Parser] Simplify multibyte support as EDIFACT requires that tag names and separators/delimiters are ASCII.

--------

## 1.0.0 - 2018-01-28

### Added

* [Support] Add support for PHP 7.2
* [Parser/Serializer] Created a class to manage control characters.
* [Segments] Created a SegmentInterface and an AbstractSegment for downstream libraries.
* [Segments] Added a Factory for creating segments.

### Changed

* [Segments] Renamed the getName() method to getSegmentCode() to avoid downstream clashes.
* [Exceptions] Library specific exceptions are now thrown.

### Removed

* [Support] Drop support for HHVM

--------

## 0.1.1 - 2016-12-09

### Fixed

* [Serializer] Ensure control characters are escaped. (Henrik Nordquist)

--------

## 0.1.0 - 2015-08-24

### Added

* Initial Release
