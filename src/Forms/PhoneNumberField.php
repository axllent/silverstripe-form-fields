<?php

namespace Axllent\FormFields\Forms;

use Axllent\FormFields\FieldType\PhoneNumber;
use SilverStripe\Core\Validation\ValidationResult;
use SilverStripe\Forms\TextField;

class PhoneNumberField extends TextField
{
    /**
     * Return field attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        $defaultPrefix = PhoneNumber::config()->get('default_country_code');
        $attributes    = [
            'class'       => 'text',
            'placeholder' => _t(
                __CLASS__ . '.Placeholder',
                'Phone number links will be prefixed with +{prefix}',
                ['prefix' => $defaultPrefix]
            ),
        ];

        return array_merge(
            parent::getAttributes(),
            $attributes
        );
    }

    /**
     * Return validation result
     */
    public function validate(): ValidationResult
    {
        $result = ValidationResult::create();

        // Don't validate empty fields
        $this->value = trim(strval($this->value));

        if (empty($this->value)) {
            return $result;
        }

        $phone = PhoneNumber::create()->setValue($this->value);

        if (!$phone->Link()) {
            $result->addFieldError(
                $this->name,
                _t(
                    __CLASS__ . '.ValidationError',
                    'Please enter a valid phone number'
                ),
                'validation'
            );
        }

        return $result;
    }
}
