<?php

namespace Axllent\FormFields\FieldType;

use SilverStripe\ORM\FieldType\DBVarchar;

/**
 * Class URL represents a variable-length string of up to 255 characters, designed to store raw text
 *
 * Allows for valid template encoding or extraction of URL parts in templates
 */
class URL extends DBVarchar
{
    private static $casting = array(
        'Initial' => 'Text',
        'URL' => 'HTMLText',
        'Scheme' => 'HTMLFragment',
        'User' => 'HTMLFragment',
        'Pass' => 'HTMLFragment',
        'Host' => 'HTMLFragment',
        'Path' => 'HTMLFragment',
        'Query' => 'HTMLFragment'
    );

    public function URL()
    {
        $p = $this->parse();
        $link = '';

        $link .= (!empty($p['scheme'])) ? $p['scheme'] . '://' : false;
        $link .= (!empty($p['user'])) ? $p['user'] . ':' : false;
        $link .= (!empty($p['pass'])) ? $p['pass'] . '@' : false;
        $link .= (!empty($p['host'])) ? $p['host'] : false;
        $link .= (!empty($p['path'])) ? $p['path'] : false;
        if (!empty($p['query'])) {
            parse_str($p['query'], $get_array);
            $link .= '?' . http_build_query($get_array, '', '&amp;');
        }
        $link .= (!empty($p['fragment'])) ? '#' . $p['fragment'] : false;

        return $link;
    }

    public function getScheme()
    {
        return $this->parse('scheme');
    }

    public function getUser()
    {
        return $this->parse('user');
    }

    public function getPass()
    {
        return $this->parse('pass');
    }

    public function getHost()
    {
        return $this->parse('host');
    }

    public function getPath()
    {
        return $this->parse('path');
    }

    public function getQuery()
    {
        return $this->parse('query');
    }

    public function parse($component = false)
    {
        $parts = parse_url($this->RAW());

        if (!$component) {
            return $parts;
        } elseif (!empty($parts[$component])) {
            return $parts[$component];
        } else {
            return false;
        }
    }
}
