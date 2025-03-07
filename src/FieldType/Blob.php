<?php

namespace Axllent\FormFields\FieldType;

use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBField;

class Blob extends DBField
{
    /**
     * Add the field to the underlying database.
     */
    public function requireField(): void
    {
        DB::require_field($this->getTable(), $this->getName(), 'blob');
    }
}
