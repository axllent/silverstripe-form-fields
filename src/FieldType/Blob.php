<?php

namespace Axllent\FormFields\FieldType;

use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBField;

class Blob extends DBField
{
    /**
     * Add the field to the underlying database.
     *
     * @param  Null
     * @return void
     */
    public function requireField()
    {
        DB::require_field($this->getTable(), $this->getName(), 'blob');
    }
}
