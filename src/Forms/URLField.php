<?php

namespace Axllent\FormFields\Forms;

use SilverStripe\Forms\TextField;

class URLField extends TextField
{
    public function getAttributes()
    {
        $attributes = array(
            'class' => 'text',
            'placeholder' => 'http://www.example.com'
        );

        return array_merge(
            parent::getAttributes(),
            $attributes
        );
    }

    public function validate($validator)
    {
        // Don't validate empty fields
        if (empty($this->value)) {
            return true;
        }

        if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
            if (filter_var('http://' . $this->value, FILTER_VALIDATE_URL)) {
                $this->value = 'http://' . $this->value;
            } else {
                $validator->validationError(
                    $this->name,
                    _t(__CLASS__ . '.ValidationError', 'Please enter a valid URL including the http:// or https://'),
                    'validation'
                );
                return false;
            }
        }

        return true;
    }
}
