<?php

namespace Axllent\FormFields\Forms;

use SilverStripe\Forms\LiteralField;

class NoticeField extends LiteralField
{
    protected $content;

    public function __construct($name, $content, $class = 'notice', $all_tabs = true)
    {
        $classes = [
            'noticefield',
            'message',
            trim(htmlspecialchars($class))
        ];
        if ($all_tabs) {
            array_push($classes, 'persist');
        }
        $content = '<div class="' . implode($classes, ' ') . '">' . $content . '</div>';

        parent::__construct($name, $content);
    }
}
