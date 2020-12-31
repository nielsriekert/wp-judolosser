# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.13.0] - 2020-12-31
### Changed
- From featured image to photo acf field Events.

## [1.12.0] - 2020-12-31
### Added
- Dutch translations.

## [1.11.0] - 2020-12-31
### Added
- Endpoints for events.
- Navigation to page card type.

### Changed
- Lot of refactors (css and php).

## [1.10.1] - 2020-12-29
### Fixed
- Photo Album templates.

## [1.10.0] - 2020-12-28
### Changed
- Refactored cards and more.

## [1.9.0] - 2020-12-28
### Added
- Locations.

### Changed
- Refactored a lot of code.
- Random stuff.

## [1.8.1] - 2020-11-08
### Fixed
- Event page ajax request (removed nonce).

## [1.8.0] - 2020-11-08
### Added
- Bedrock setup (for local development).
- Vertrouwenspersonen to committees.
- Contactpersoon to committee roles.
- Updated npm packages.

## [1.7.0] - 2019-09-04
### Added
- Attachment admin column for event and page post type.

### Changed
- Updated npm packages.

## [1.6.2] - 2019-11-14
### Fixed
- Photoalbums not displaying photos from Custom Field Suite fields.
- Photo album grid layout on mobile.

## [1.6.1] - 2019-11-10
### Fixed
- Connected photo albums single.php.

## [1.6.0] - 2019-10-13
### Added
- ACF support for photo albums.
- Tracy debugger for development.

### Changed
- Refactored some old code.

## [1.5.0] - 2019-07-18
### Changed
- Logo carousel slower and no grouping.
- Logo carousel links no force new tab.

### Fixed
- Event card wrong excerpt.

## [1.4.0] - 2019-07-17
### Added
- From logo row to carousel.

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