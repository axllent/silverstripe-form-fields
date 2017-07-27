# Custom SilverStripe forms & fields

An extension that adds custom form fields & types to the SilverStripe 4.


## Form Types

See docs for more information.

- [PhoneNumberField](docs/en/PhoneNumberField.md) - Field for a single phone number (provides `Link()` support) with validation.
- [NoticeField](docs/en/NoticeField.md) - Add notices into the CMS. NoticeFields can be persistent across all tabs and are dismissable.
- [URLField](docs/en/URLField.md) - Easily add an validating input field for URLs.
- [VideoLinkField](docs/en/VideoLinkField.md) - Easy embedding of YouTube & Vimeo video fields in the CMS.


## Object Types

See docs for more information.

- [PhoneNumber](docs/en/PhoneNumber.md) - Phone number with `Link()` support.
- [URL](docs/en/URL.md) - Encode urls properly in templates.
- [Blob](docs/en/Blob.md) - Adds blob field type to the framework.
- [VideoLink](docs/en/VideoLink.md) - Video link field (YouTube/Vimeo supported) for easy template integration.


## Requirements

- SilverStripe ^4.0 CMS
- [Guzzle](https://github.com/guzzle/guzzle) (if you require fetching of Vimeo thumbnails for VideoLink)


## Installation via Composer

You can install it via composer:

```
composer require axllent/silverstripe-form-fields
```
