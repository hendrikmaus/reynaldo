# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

The change log itself is written the way that [keepachangelog.com](http://keepachangelog.com/) describes.

## [0.1.4] - 2017-07-20
- Fix fixture scripts
- Add missing dependencies

## [0.1.3] - 2017-10-04
### Fixed:
- Add support for URI parameters members

## [0.1.2] - 2016-04-09
### Fixed
- Not all refract elements have a ´content´ field, hence the parser will now check if it exists before accessing it

## [0.1.1] - 2016-01-09
### Changed
- `\Hmaus\Reynaldo\Elements\ApiHttpResponse::getHeaders` and `\Hmaus\Reynaldo\Elements\ApiHttpRequest::getHeaders`
  return an empty array of no headers are found in their attributes

## [0.1.0] - 2016-08-30
- Initial Release
