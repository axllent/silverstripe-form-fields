<?php

namespace Axllent\FormFields\Forms;

use SilverStripe\Core\Validation\ValidationResult;
use SilverStripe\Forms\TextField;

class URLField extends TextField
{
    /**
     * Input type
     *
     * @var string
     */
    protected $inputType = 'url';

    /**
     * Return field attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [
            'class'       => 'text url',
            'placeholder' => 'https://www.example.com',
        ];

        return array_merge(
            parent::getAttributes(),
            $attributes
        );
    }

    /**
     * Return validation result
     *
     * @return bool
     */
    public function validate(): ValidationResult
    {
        $result = ValidationResult::create();
        // Don't validate empty fields
        if (empty($this->value)) {
            return $result;
        }

        if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
            if (filter_var('https://' . $this->value, FILTER_VALIDATE_URL)) {
                $this->value = 'https://' . $this->value;
            } else {
                $result->addFieldError(
                    $this->name,
                    _t(
                        __CLASS__ . '.ValidationError',
                        'Please enter a valid URL including the http:// or https://'
                    ),
                    'validation'
                );
            }
        }

        return $result;
    }
}
