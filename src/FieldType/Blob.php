<?php

namespace Axllent\FormFields\FieldType;

use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\DB;

class Blob extends DBField
{
    public function requireField()
    {
        DB::require_field($this->getTable(), $this->getName(), 'blob');
    }
}
