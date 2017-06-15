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
        $this->value = trim($this->value);

        if ($this->value && !filter_var($this->value, FILTER_VALIDATE_URL)) {
            $validator->validationError(
                $this->name,
                'Please enter a valid URL including the http://',
                'validation'
            );
            return false;
        }

        return true;
    }
}
