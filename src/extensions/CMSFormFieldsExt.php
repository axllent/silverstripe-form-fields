<?php
/**
* Custom CMS Form Types for SilverStripe 4
* =========================================
*
* A series of additional Form types (including css & JavaScript)
* for the SilverStripe CMS.
*
* License: MIT-style license http://opensource.org/licenses/MIT
* Authors: Techno Joy development team (www.technojoy.co.nz)
*/

namespace Axllent\FormFields;

use SilverStripe\Admin\LeftAndMainExtension;
use SilverStripe\View\Requirements;

class CMSFormFieldsExt extends LeftAndMainExtension
{

    public function init()
    {
        parent::init();
        Requirements::css('axllent/silverstripe-form-fields: css/cms-form-fields.css');
		Requirements::javascript('axllent/silverstripe-form-fields: javascript/cms-form-fields.js');
    }
}
