# VideoLink Field Type

This adds the a `VideoLink` field type to your database. It extends the [URL](URL.md) field type
and provides additional validation, iframe generation, video thumbnails and can extract video titles.

**Note:** Certain functionality requires interaction with third party APIs, namely video titles
(if used) and Vimeo thumbnails. This uses official APIs provided by YouTube and Vimeo, and does not
require API keys. It does however require `guzzlehttp/guzzle` to be installed. See **Requirements** below.


## Video Support

The VideoLink field currently supports **YouTube** and **Vimeo** only.


## Requirements

If you require Video titles, or support for Vimeo then you must also install
`guzzlehttp/guzzle` as this information is scraped (legally) and cached from the
official public oembed APIs.

```shell
composer require guzzlehttp/guzzle
```

## Usage

For information regarding `VideoLinkField` (integration into the CMS) please refer to
[VideoLinkField docs](VideoLinkField.md).

The `VideoLink` object comes bundles with several functions/features allowing you to extract
certain values based on the video URL you provided.


### $IframeURL

`$IframeURL` will return a generated URL which you can use in your templates if you wish to create
the iframe code yourself. The URL is generated automatically from the service, however you can add
custom variables (see **Customisation** below).


### $Iframe([width=100%], [height=56%])

`$Iframe` will return a full responsive iframe in your template. The default height (56%) should allow
responsive scaling as it 56% results in default 16:9 ratio. The iframe SRC URL is generated
automatically from the service, however you can add custom variables (see **Customisation** below).


### $Title

`$Title` returns the title of the video as set on their respective video hosting sites.

**Note:** If you require the Title then you must have `guzzlehttp/guzzle` installed.


### $ThumbnailURL([large|medium|small])

YouTube only provide a limited set of thumbnail sizes, the default "high quality" thumbnail of 480x360px
which has a proportional ratio of 4:3 (why YouTube, why......?). A much higher quality 16:9 option is
usually available (1280x720px), however not all videos have this.

Vimeo's provides dynamic sizing, however have been set to match the three YouTube sizes.

**Note:** Vimeo thumbnail support requires `guzzlehttp/guzzle`.


#### Thumbnail Sizes:

The default size of `$ThumbnailURL` is "medium".


**YouTube:** (black bars added if video size ratio differs from set size)

- large: 1280x720px (ratio 16:9 - not all videos have this size!)
- medium: 480x360px (ratio 4:3 - YouTube default)
- small: 320×180px (ratio 16:9)


**Vimeo:** (crops nicely to avoid black bars)

- large: 1280x720px (ratio 16:9)
- medium: 480x360px (ratio 4:3 to match YouTube's medium size)
- small: 320x180px (ratio 16:9)


## Customisation of the video parameters

Both YouTube and Vimeo allow you to specify custom url parameters that affect the display
of the iframe. You can set these parameters (per service) in a yaml file, eg:

```yaml
Axllent\FormFields\FieldType\VideoLink:
  service:
    youtube:
      modestbranding: 1
      showinfo: 0
    vimeo:
      title: 0
      byline: 0
      portrait: 0
      badge: 0
```

The format is simple:

```yaml
Axllent\FormFields\FieldType\VideoLink:
  service:
    <service>: # youtube | vimeo
      <parameter1>: <value>
      <parameter2>: <value>
```

This will modify all VideoLink iframes on your site to append those parameters to their iframe URLs.

For a list of supported parameters please refer to the service documentation:

- [YouTube Parameters](https://developers.google.com/youtube/player_parameters#Parameters)
- [Vimeo Parameters](https://developer.vimeo.com/apis/oembed#arguments)


## Caching

Video titles and/or Vimeo thumbnails require `guzzlehttp/guzzle`. The API results are cached
locally by default for 7 days (604800 seconds), however you can change this in your config:

```yaml
Axllent\FormFields\FieldType\VideoLink:
  cache_seconds: 3600 # 1 hour
```
