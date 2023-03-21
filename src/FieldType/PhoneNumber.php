<?php

namespace Axllent\FormFields\FieldType;

use Axllent\FormFields\Forms\PhoneNumberField;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\FieldType\DBVarchar;

class PhoneNumber extends DBVarchar
{
    /**
     * Default country code
     *
     * @var int
     *
     * @config
     */
    private static $default_country_code = '64';

    /**
     * Contructor
     *
     * @param string $name    Field name
     * @param int    $size    Size
     * @param array  $options Options array
     */
    public function __construct($name = null, $size = 100, $options = [])
    {
        parent::__construct($name, $options);
        $this->size = $size ? $size : 100;
    }

    /**
     * Scaffold form field
     * Set the URLField as the default field type
     *
     * @param string $title  Field title
     * @param array  $params Parameters
     *
     * @return FormField
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        return PhoneNumberField::create($this->name, $title);
    }

    /**
     * Return a formatted href
     *
     * @return string The link to the number
     */
    public function link()
    {
        $tel = $this->parseNumber();

        return (!empty($tel['RFC3966'])) ? $tel['RFC3966'] : false;
    }

    /**
     * Basic phone parser
     * Note that this is a very basic parser
     *
     * @return array
     */
    public function parseNumber()
    {
        $tel = [
            'Original' => $this->value,
        ];

        // remove brackets
        $str = str_replace(['(', ')'], '', strval($this->value));

        // replace characters with space
        $str = trim(
            preg_replace(
                '/\s+/',
                ' ',
                preg_replace('/[^a-z0-9\#\+]/i', ' ', strtolower($str))
            )
        );

        // try detect if number starts with a + or international dialing code
        if (preg_match('/^(\+\d{1,3}\b)/', $str, $matches)) {
            $tel['CountryCode'] = substr($matches[1], 1);
            $str                = trim(substr($str, strlen($matches[0])));
        } elseif (preg_match(
            '/^(00|010|011|0011|810|0010|0011|0014)\s?(\d{1,3})\b/',
            $str,
            $matches
        )
        ) {
            $tel['CountryCode'] = $matches[2];
            $str                = trim(substr($str, strlen($matches[0])));
        }

        // try detect if an extension is included
        if (preg_match('/(#|ext|extension)\s?(\d)+$/', $str, $matches)) {
            $tel['Extension'] = $matches[2];
            $str              = trim(
                preg_replace('/' . preg_quote($matches[0], '/') . '$/', '', $str)
            );
        }

        // set default country code if none detected
        if (empty($tel['CountryCode'])) {
            $tel['CountryCode'] = $this->config()->get('default_country_code');
        }

        // remove leading 0
        if ('0' == substr($str, 0, 1)) {
            $str = trim(substr($str, 1));
        }

        // remaining number must contain at least 6 digits
        if (preg_match('/[a-z]/', $str) || preg_match_all('/\d/', $str) < 6) {
            return false;
        }

        // generate a RFC3966 link
        $tel['RFC3966'] = 'tel:+' . $tel['CountryCode'] . '-' .
        preg_replace('/[^0-9]/', '-', $str);

        if (!empty($tel['Extension'])) {
            $tel['RFC3966'] .= ';ext=' . $tel['Extension'];
        }

        return $tel;
    }
}
