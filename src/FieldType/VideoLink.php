<?php

namespace Axllent\FormFields\FieldType;

use Axllent\FormFields\Forms\VideoLinkField;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\FormField;

/**
 * VideoLink - oEmbeded Video
 *
 * Supports YouTube & Vimeo links
 */
class VideoLink extends URL
{
    /**
     * Cache in seconds
     * Default 7 days
     *
     * @var int
     *
     * @config
     */
    private static $cache_seconds = 604800;

    /**
     * Ensures that the methods are wrapped in the correct type and
     * values are safely escaped while rendering in the template.
     *
     * @var array
     */
    private static $casting = [
        'Initial'      => 'Text',
        'URL'          => 'HTMLText',
        'iFrameURL'    => 'HTMLFragment',
        'ThumbnailURL' => 'HTMLFragment',
    ];

    /**
     * Scaffold form field
     * Set the URLField as the default field type
     *
     * @param string $title  Field title
     * @param array  $params Parameters
     */
    public function scaffoldFormField($title = null, $params = null): FormField
    {
        return VideoLinkField::create($this->name, $title);
    }

    /**
     * Return the raw URL
     */
    public function URL(): string
    {
        return $this->RAW();
    }

    /**
     * Return the iframe URL
     *
     * @return string
     */
    public function getIframeURL()
    {
        $info = $this->_parseLink();
        if (!$info) {
            return false;
        }

        $params = $this->Config()->get('service');

        if ('Vimeo' == $this->Service) {
            $url = 'https://player.vimeo.com/video/' . $this->VideoID;
            if ($params && !empty($params[strtolower($this->Service)])) {
                $url .= '?'
                . http_build_query($params[strtolower($this->Service)], '', '&');
            }

            return $url;
        }
        if ('YouTube' == $this->Service) {
            $url = 'https://www.youtube.com/embed/' . $this->VideoID;
            if ($params && !empty($params[strtolower($this->Service)])) {
                $url .= '?'
                . http_build_query($params[strtolower($this->Service)], '', '&');
            }

            return $url;
        }
    }

    /**
     * Return populated iframe template
     *
     * @param string $max_width Max width
     * @param string $height    Height in percent or pixels
     *
     * @return string
     */
    public function iFrame($max_width = '100%', $height = '56%')
    {
        $url = $this->getIFrameURL();
        if (!$url) {
            return false;
        }
        if (is_numeric($max_width)) {
            $max_width .= 'px';
        }
        if (is_numeric($height)) {
            $height .= 'px';
        }

        return $this->customise(
            [
                'URL'      => $url,
                'MaxWidth' => $max_width,
                'Height'   => $height,
            ]
        )->renderWith('Axllent\FormFields\Layout\VideoIframe');
    }

    /**
     * Return service based on URL
     *
     * @return mixed YouTube|Vimeo|null
     */
    public function getService()
    {
        $info = $this->_parseLink();

        return $info ? $info['VideoService'] : false;
    }

    /**
     * Return video ID
     *
     * @return string
     */
    public function getVideoID()
    {
        $info = $this->_parseLink();

        return $info ? $info['VideoID'] : false;
    }

    /**
     * Scrape Video information from hosting sites
     *
     * @return string
     */
    public function getTitle()
    {
        if ('YouTube' == $this->getService()) {
            $data = $this->_getCachedJsonResponse(
                'https://www.youtube.com/oembed?url=' .
                urlencode(
                    'http://www.youtube.com/watch?v=' .
                    $this->VideoID
                )
                . '&format=json'
            );
            if ($data && !empty($data['title'])) {
                return $data['title'];
            }
        }
        if ('Vimeo' == $this->getService()) {
            $data = $this->_getCachedJsonResponse(
                'https://vimeo.com/api/oembed.json?url=' .
                urlencode(
                    'https://player.vimeo.com/video/' . $this->VideoID
                )
            );
            if ($data && !empty($data['title'])) {
                return $data['title'];
            }
        }
    }

    /**
     * Return thumbnail URL
     *
     * @param string $size (large|medium|small)
     *
     * @return string
     */
    public function thumbnailURL($size = 'medium')
    {
        if (!in_array($size, ['large', 'medium', 'small'])) {
            return false;
        }

        $info    = $this->_parseLink();
        $service = $this->getService();

        if (!$info || !$service) {
            return false;
        }

        if ('YouTube' == $service) {
            if ('large' == $size) {
                $img = 'maxresdefault.jpg';
            } elseif ('medium' == $size) {
                $img = 'hqdefault.jpg';
            } else {
                $img = 'mqdefault.jpg';
            }

            return 'https://i.ytimg.com/vi/' . $this->VideoID . '/' . $img;
        }

        if ('Vimeo' == $service) {
            $data = $this->_getCachedJsonResponse(
                'https://vimeo.com/api/oembed.json?url=' .
                urlencode(
                    'https://player.vimeo.com/video/' . $this->VideoID
                )
            );

            if (!$data || empty($data['thumbnail_url'])) {
                return false;
            }

            $parts    = explode('_', $data['thumbnail_url']);
            $thumbUrl = str_replace(
                $parts[count($parts) - 1],
                '',
                $data['thumbnail_url']
            );

            if (!$thumbUrl) {
                return false;
            }

            if ('large' == $size) {
                return $thumbUrl . '1280x720';
            }
            if ('medium' == $size) {
                return $thumbUrl . '480x360';
            }

            return $thumbUrl . '320x180';
        }

        return false;
    }

    /**
     * Return cached json response
     *
     * @param string $url URL
     *
     * @return mixed
     */
    private function _getCachedJsonResponse($url)
    {
        if (!class_exists('GuzzleHttp\Client')) {
            return false;
        }

        $cache_seconds = $this->config()->get('cache_seconds');
        if (!is_numeric($cache_seconds)) {
            $cache_seconds = 604800;
        }

        $cache = Injector::inst()->get(CacheInterface::class . '.videoImgCache');

        $key = md5($url);

        if (!$json = $cache->get($key)) {
            $client = new Client(
                [
                    'timeout' => 5, // seconds
                    'headers' => [ // Appear like a web browser
                        'User-Agent'      => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:83.0) Gecko/20100101 Firefox/83.0',
                        'Accept-Language' => 'en-US,en;q=0.5',
                    ],
                ]
            );

            try {
                $res  = $client->request('GET', $url);
                $json = (string) $res->getBody();
            } catch (RequestException $e) {
                return false;
            }
            $cache->set($key, $json, $cache_seconds);
        }

        $data = @json_decode($json, true);

        return (!empty($data)) ? $data : false;
    }

    /**
     * Parse video link
     *
     * @return mixed
     */
    private function _parseLink()
    {
        $value = $this->RAW();

        if (!$value) {
            return false;
        }

        $output = false;

        if (preg_match(
            '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/i',
            $value,
            $matches
        )
        ) {
            foreach ($matches as $match) {
                if (preg_match('/^[0-9]{6,12}$/', $match)) {
                    $output = [
                        'VideoID'      => $match,
                        'VideoService' => 'Vimeo',
                    ];
                }
            }
        } elseif (preg_match(
            '/https?:\/\/(www\.)?youtube\.com\/embed\/([a-z0-9\_\-]+)$/i',
            $value,
            $matches
        )
        ) {
            // https://www.youtube.com/embed/xxx
            $output = [
                'VideoID'      => $matches[2],
                'VideoService' => 'YouTube',
            ];
        } elseif (preg_match(
            '/https?:\/\/youtu\.be\/([a-z0-9\_\-]+)/i',
            $value,
            $matches
        )
        ) {
            $output = [
                'VideoID'      => $matches[1],
                'VideoService' => 'YouTube',
            ];
        } elseif (preg_match('/youtu\.?be/i', $value)) {
            $query_string = [];
            parse_str(parse_url($value, PHP_URL_QUERY), $query_string);
            if (!empty($query_string['v'])) {
                $output = [
                    'VideoID'      => $query_string['v'],
                    'VideoService' => 'YouTube',
                ];
            }
        }

        return ($output) ? $output : false;
    }
}
