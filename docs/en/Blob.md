# Blob Field Type

This adds the traditional `blob` field type to the framework.

## Usage
```php
<?php
use SilverStripe\ORM\DataObject;
use Axllent\FormFields\FieldType\Blob;

class MyObject extends DataObject
{
    private static $db = array(
        'EncryptedData' => Blob::class
    );
}
```
