# Phone Number Field Type

A simplistic phone number database field with RFC3966 linking option for templating.

**Note:** This is not a fully compliant number validator [like this](https://github.com/giggsey/libphonenumber-example)
however it tries to detect the country code and create a link based on that. If no
country code is detected, a default is used (64 if not set in your yaml config).

It also supports basic phone extensions.

Example valid phone number formats:

- +64 (0)9 999 9999 (`tel:+64-9-999-9999`)
- 0061-2-9999-999 (`tel:+61-2-9999-999`)
- 021 999 9999 (`tel:+64-21-999-9999`)
- (09) 999 9999 #2 (`tel:+64-9-999-9999;ext=2`)
- (0)9 999 9999 ext 4 (`tel:+64-9-999-9999;ext=4`)


## Default country (int)

The default country code is `64` (New Zealand), however you can change this in your yaml file:

```
Axllent\FormFields\FieldType\PhoneNumber:
  default_country_code: 99
```


## Usage

For usage in your PHP class please see [PhoneNumberField.md](PhoneNumberField.md)

```html
<% if $PhoneNumber %>
    <a href="$PhoneNumber.Link">$PhoneNumber</a>
<% end_if %>
