# VideoLinkField

VideoLinkField provides an easy way to add a validating input field for YouTube & Vimeo links,
including an optional preview in the CMS if created with `->showPreview(<max_width>)` (see example below).

## Inserting a VideoLinkField

```php
<?php

use Axllent\FormFields\FieldType\VideoLink;
use Axllent\FormFields\Forms\VideoLinkField;

class MyPage extends Page
{

    private static $db = [
        'FeaturedVideo' => VideoLink::class
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main',
            VideoLinkField::create('FeaturedVideo')
                ->showPreview(500)
        );

        return $fields;
    }
}
```
