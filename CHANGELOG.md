# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.3.2] - 2019-06-30
### Fixed
- logo height logo carousel.

## [1.3.1] - 2019-06-25
### Fixed
- Loading state for item containers.
- css grid fallback for older browsers.
- Added polyfils for IE11 and other old browsers.

## [1.3.0] - 2019-06-23
### Added
- Logo carousel (no carousel yet) above footer on all pages (client side rendering). Data from sanity.io, hardcoded project id.

### Changed
- Updated packages and refactored webpack config.
- Articles, events and photoalbums now pagination with client side rendering (lit-html).

## [1.2.1] - 2019-02-02
### Fixed
- Role setting naming class.

## [1.2.0] - 2019-02-02
### Added
- Committee and roles more dynamic for contact template.

### Changed
- Refactored users code.

## [1.1.4] - 2018-04-16
### Changed
- npm packages updated.
- Webpack 3 to 4.
- lightbox now from npm (as vanillelightbox).

### Fixed
- 301 to home redirect bug.

## [1.1.3] - 2018-02-22
### Changed
- More robust lightbox usage.
- Scroll classes body refactor.
- Header background image for single post removed blur animation (now always blur) and 10% higher.

## [1.1.2] - 2018-02-22
### Added
- Connected photoalbums custom column added for posts.
- Date added for single post template.

### Changed
- Improved some custom column code.

### Fixed
- Button text color.

## [1.1.1] - 2018-02-03
### Added
- Related photoalbum for news single posts.

### Changed
- Classes for photoalbum added.
- Swapped the photoalbum overview for the method from the classes.

## [1.1] - 2018-01-28
### Added
- Docker local development.
- CHANGELOG.md file.

### Changed
- Diretory structure to src and dist.
- From gulp to webpack.
- Gallery css to flexbox.
- CSS reset swapped for normalize.css.