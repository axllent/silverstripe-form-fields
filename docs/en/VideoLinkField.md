# VideoLinkField

VideoLinkField provides an easy way to add a validating input field for YouTube & Vimeo links,
including an optional preview in the CMS (disable with `$field->showPreview(false)` or set
a maximum width with `$field->showPreview('100%')`

## Inserting a VideoLinkField

```php
<?php

use Axllent\FormFields\FieldType\VideoLink;

class MyPage extends Page
{

    private static $db = [
        'FeaturedVideo' => VideoLink::class
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->dataFieldNyName('FeaturedVideo')
            ->showPreview('100%');

        return $fields;
    }
}
```
