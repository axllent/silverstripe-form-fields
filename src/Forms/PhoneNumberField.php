<?php

namespace Axllent\FormFields\Forms;

use Axllent\FormFields\FieldType\PhoneNumber;
use SilverStripe\Forms\TextField;

class PhoneNumberField extends TextField
{
    public function getAttributes()
    {
        $attributes = array(
            'class' => 'text',
            'placeholder' => 'Phone number links will be prefixed with +64'
        );

        return array_merge(
            parent::getAttributes(),
            $attributes
        );
    }

    public function validate($validator)
    {
        // Don't validate empty fields
        $this->value = trim($this->value);

        if (empty($this->value)) {
            return true;
        }

        $phone = PhoneNumber::create()->setValue($this->value);

        if (!$phone->Link()) {
            $validator->validationError(
                $this->name,
                'Please enter a valid phone number',
                'validation'
            );
            return false;
        }


        return true;
    }
}
