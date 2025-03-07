<?php

namespace Axllent\FormFields\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

class CMSFormFieldsExt extends Extension
{
    /**
     * OnAfterInit function
     *
     * @return void
     */
    public function onAfterInit()
    {
        Requirements::css(
            'axllent/silverstripe-form-fields: css/cms-form-fields.css',
        );
        Requirements::javascript(
            'axllent/silverstripe-form-fields: javascript/cms-form-fields.js',
        );
    }
}
