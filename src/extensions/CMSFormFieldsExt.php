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

namespace Axllent\CMSFormFields;

use SilverStripe\Admin\LeftAndMainExtension;
use SilverStripe\View\Requirements;

class CMSFormFieldsExt extends LeftAndMainExtension
{

    public function init()
    {
        parent::init();
        Requirements::css($this->ModuleBase() . '/css/cms-form-fields.css');
		Requirements::javascript($this->ModuleBase() . '/javascript/cms-form-fields.js');
    }

    private function ModuleBase()
    {
        return basename(dirname(dirname(dirname(__FILE__))));
    }
}
