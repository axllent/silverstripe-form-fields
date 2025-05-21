# Changelog

Notable changes to this project will be documented in this file.

## [1.6.4]

- Add `referrerpolicy="strict-origin-when-cross-origin"` to VideoIframe template to account for CSP `referrer-policy: same-origin`


## [1.6.3]

- Bugfix: remove unnecessary init()


## [1.6.2]

- Replace deprecated LeftAndMainExtension with Extension


## [1.6.1]

- Fix PHP 8.1 deprecation warnings
- Support for Silverstripe 5


## [1.6.0]

- Use Vimeo's oembed.json to return VideoLink metadata for title and thumbnail URL, allowing it to work with non-public videos (thanks @nicolas-cusan).
- VideoLink `$ThumbnailURL(large)` thumbnails are now 1280x720px. Note that only high quality YouTube videos have this size, so mileage may vary.
- Default VideoLink thumbnail size is now `medium` (480x360px), which was previously `large`. This image size has a ratio of 4:3 due to YouTube's limited sizing. This is the safe YouTube default.
- VideoLink `$Iframe` hidden overflow decreased from 2% to 1% to minimize offset.


## [1.5.2]

- Add support for embedded YouTube links (eg: `eg: https://www.youtube.com/embed/xxx`)


## [1.5.1]

- PSR-4 autoloading compatibility for Composer 2.0 (thanks [@obj63mc](https://github.com/obj63mc))
- PSR2 tidy-up


## [1.5.0]

- Set default form fields for PhoneNumber, URL & VideoLink
- Set placeholder for URLField to https://wwww.example.com
- Show video preview by default for VideoLinkField


## [1.4.0]

- Change URLField to `url` type


## [1.3.2]

- Fix deprecated implode() order (php 7.4)
- Update PHP docblocks


## [1.3.1]

- Add Italian translation


## [1.3.0]

- Add translation support (en/da/nl)
- Add code DocBlocks


## [1.2.5]

- Move templates to correct position
- Switch to silverstripe-vendormodule
- Use tabs for CSS/JS/SS


## [1.2.4]

- Fix html bug in iFrame URL


## [1.2.3]

- Better support for youtu.be links


## [1.2.2]

- PhoneNumber
- PhoneNumberField


## [1.2.1]

- Update documentation
- Don't exclude docs folder with a `composer require`


## [1.2.0]

- Append "http://" to non-validating URLs and re-validate.
- `VideoLink` field type, iframe generation, thumbnails
- `VideoLinkField` - CMS integration


## [1.1.0]

- `URL` field type
- `Blob` field type
- Better namespacing


## [1.0.1]

- Add URLField


## [1.0.0]

- Add NoticeField
