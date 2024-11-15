<?php

namespace Axllent\FormFields\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

class CMSFormFieldsExt extends Extension
{
    /**
     * Init
     *
     * @return void
     */
    public function init()
    {
        Requirements::css(
            'axllent/silverstripe-form-fields: css/cms-form-fields.css'
        );
        Requirements::javascript(
            'axllent/silverstripe-form-fields: javascript/cms-form-fields.js'
        );
    }
}
