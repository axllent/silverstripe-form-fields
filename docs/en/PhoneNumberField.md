# PhoneNumberField

PhoneNumberField provides a simple validating input field. If a link cannot be generated
from the value the form does not validate.

Example valid phone number formats:

- +64 (0)9 999 9999
- 0061-2-9999-999
- 021 999 9999
- (09) 999 9999 #2
- (0)9 999 9999 ext 4


## PhoneNumber & PhoneNumberField in your class

```php
<?php

use Axllent\FormFields\FieldType\PhoneNumber;
use Axllent\FormFields\Forms\PhoneNumberField;

class MyPage extends Page
{

    private static $db = [
        'PhoneNumber' => PhoneNumber::class
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main',
            PhoneNumberField::create('PhoneNumber')
        );

        return $fields;
    }
}
```
