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
    /**
     * Ensures that the methods are wrapped in the correct type and
     * values are safely escaped while rendering in the template.
     * @var array
     */
    private static $casting = [
        'Initial' => 'Text',
        'URL'     => 'HTMLText',
        'Scheme'  => 'HTMLFragment',
        'User'    => 'HTMLFragment',
        'Pass'    => 'HTMLFragment',
        'Host'    => 'HTMLFragment',
        'Path'    => 'HTMLFragment',
        'Query'   => 'HTMLFragment',
    ];

    /**
     * Return constructed URL
     *
     * @param  Null
     * @return String
     */
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

    /**
     * Return the URL scheme (e.g. "http" or "https").
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->parse('scheme');
    }

    /**
     * Return the URL user.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->parse('user');
    }

    /**
     * Return the URL password.
     *
     * @return string
     */
    public function getPass()
    {
        return $this->parse('pass');
    }

    /**
     * Return the URL host.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->parse('host');
    }

    /**
     * Return the URL path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->parse('path');
    }

    /**
     * Return the URL query string.
     *
     * @return string eg: x=1&y=2
     */
    public function getQuery()
    {
        return $this->parse('query');
    }

    /**
     * Parse the URL string.
     *
     * @param  Boolean optional component
     * @return string eg: x=1&y=2
     */
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
