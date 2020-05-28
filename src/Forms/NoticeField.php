<?php
namespace Axllent\FormFields\Forms;

use SilverStripe\Forms\LiteralField;

class NoticeField extends LiteralField
{
    /**
     * @var mixed
     */
    protected $content;

    /**
     * Contrustor
     *
     * @param string $name     Field name
     * @param string $content  HTML Content
     * @param string $class    Class string
     * @param bool   $all_tabs Show on all tabs
     *
     * @return NoticeField
     */
    public function __construct($name, $content, $class = 'notice', $all_tabs = true)
    {
        $classes = [
            'noticefield',
            'message',
            trim(htmlspecialchars($class)),
        ];
        if ($all_tabs) {
            array_push($classes, 'persist');
        }
        $content = '<div class="' . implode(' ', $classes) . '">' . $content . '</div>';

        parent::__construct($name, $content);
    }
}
