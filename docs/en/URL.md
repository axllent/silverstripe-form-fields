# URL Field Type

This adds the a `URL` field type to your database. In reality it is just a `varchar(255)` field,
however it ensures valid link encoding in your templates.

Traditional conversion of varchar strings in templates does not account for characters such as
`[]/{}` and spaces in the encoding which can lead to html errors and broken links, eg:
`http://www.example.com/search?s[search]=words&s[short_desc]=Y&s[full_desc]=Y&s[sku]=Y&s[cid]=0`

The URL field gives you more control in your templating, plus allows you to extract parts of the URL
such as just the `host` if you wish.

It works nicely in conjunction with the [URLField](URLField.md) Form field (shown below).

## Usage
```php
<?php
use Axllent\FormFields\FieldType\URL;
use Axllent\FormFields\FormFields\URLField;

class MyPage extends Page
{
    private static $db = array(
        'Website' => URL::class
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab(
            'Root.Main',
            URLField::create('Website')
        );
        return $fields;
    }
}

```

In your template you simply need to use it as you always would:
```html
<ul>
    <li><a href="$Website.URL">Correctly encoded URL</a></li>
    <li>$Website.Host</li>
    <li>$Website.Scheme</li>
</ul>
```
