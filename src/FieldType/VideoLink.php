<?php

namespace Axllent\FormFields\FieldType;

use SilverStripe\Core\Injector\Injector;
use Psr\SimpleCache\CacheInterface;

/**
 * VideoLink - oEmbeded Video
 *
 * Supports YouTube & Vimeo links
 */
class VideoLink extends URL
{
    /**
     * @config
     */
    private static $cache_seconds = 604800; // 7 days

    private static $casting = array(
        'Initial' => 'Text',
        'URL' => 'HTMLText',
        'iFrameURL' => 'HTMLFragment',
        'ThumbnailURL' => 'HTMLFragment'
    );

    public function URL()
    {
        return $this->RAW();
    }

    public function getIframeURL()
    {
        $info = $this->parseLink();
        if (!$info) {
            return false;
        }

        $params = $this->Config()->get('service');

        if ($this->Service == 'Vimeo') {
            $url = 'https://player.vimeo.com/video/' . $this->VideoID;
            if ($params && !empty($params[strtolower($this->Service)])) {
                $url .= '?' . http_build_query($params[strtolower($this->Service)], '', '&');
            }
            return $url;
        }
        if ($this->Service == 'YouTube') {
            $url = 'https://www.youtube.com/embed/' . $this->VideoID;
            if ($params && !empty($params[strtolower($this->Service)])) {
                $url .= '?' . http_build_query($params[strtolower($this->Service)], '', '&');
            }
            return $url;
        }
    }

    public function Iframe($maxwidth = '100%', $height = '56%')
    {
        $url = $this->getIFrameURL();
        if (!$url) {
            return false;
        }
        if (is_numeric($maxwidth)) {
            $maxwidth .= 'px';
        }
        if (is_numeric($height)) {
            $height .= 'px';
        }
        return $this->customise([
            'URL' => $url,
            'MaxWidth' => $maxwidth,
            'Height' => $height
        ])->renderWith('Axllent\\FormFields\\Layout\\VideoIframe');
    }

    public function getService()
    {
        $info = $this->parseLink();
        return $info ? $info['VideoService'] : false;
    }

    public function getVideoID()
    {
        $info = $this->parseLink();
        return $info ? $info['VideoID'] : false;
    }

    /**
     * Scrape Video information from hosting sites
     * @param NULL
     * @return String
     */
    public function getTitle()
    {
        if ($this->getService() == 'YouTube') {
            $data = $this->getCachedJsonResponse(
                'https://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=' .
                $this->VideoID .
                '&format=json'
            );
            if ($data && !empty($data['title'])) {
                return $data['title'];
            }
        }
        if ($this->getService() == 'Vimeo') {
            $data = $this->getCachedJsonResponse(
                'https://vimeo.com/api/v2/video/' . $this->VideoID . '.json'
            );
            if ($data && !empty($data[0]) && !empty($data[0]['title'])) {
                return $data[0]['title'];
            }
        }
    }

    public function ThumbnailURL($size = 'large')
    {
        if (!in_array($size, ['large', 'medium', 'small'])) {
            return false;
        }
        $info = $this->parseLink();
        $service = $this->getService();
        if (!$info || !$service) {
            return false;
        }

        if ($service == 'YouTube') {
            if ($size == 'large') {
                $img = 'hqdefault.jpg';
            } elseif ($size == 'medium') {
                $img = 'mqdefault.jpg';
            } else {
                $img = 'default.jpg';
            }
            return 'https://i.ytimg.com/vi/' . $this->VideoID . '/' . $img;
        }

        if ($service == 'Vimeo') {
            $data = $this->getCachedJsonResponse(
                'https://vimeo.com/api/v2/video/' . $this->VideoID . '.json'
            );
            if (!$data) {
                return false;
            }
            if ($size == 'large' && (!empty($data[0]['thumbnail_large']))) {
                return $data[0]['thumbnail_large'];
            } elseif ($size == 'medium' && (!empty($data[0]['thumbnail_medium']))) {
                return $data[0]['thumbnail_medium'];
            } elseif (!empty($data[0]['thumbnail_small'])) {
                return $data[0]['thumbnail_small'];
            }
        }

        return false;
    }

    private function getCachedJsonResponse($url)
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
            $client = new \GuzzleHttp\Client([
                'timeout'  => 5, // seconds
                'headers' => [ // Appear like a web browser
                    'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:51.0) Gecko/20100101 Firefox/51.0',
                    'Accept-Language' => 'en-US,en;q=0.5'
                ]
            ]);
            try {
                $res = $client->request('GET', $url);
                $json = (string) $res->getBody();
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                return false;
            }
            $cache->set($key, $json, $cache_seconds);
        }

        $data = @json_decode($json, true);
        return (!empty($data)) ? $data : false;
    }

    private function parseLink()
    {
        $value = $this->RAW();

        if (!$value) {
            return false;
        }

        $output = false;

        if (preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/i', $value, $matches)) {
            foreach ($matches as $match) {
                if (preg_match('/^[0-9]{6,12}$/', $match)) {
                    $output = array(
                        'VideoID' => $match,
                        'VideoService' => 'Vimeo'
                    );
                }
            }
        } elseif (preg_match('/https?:\/\/youtu\.be\/([a-z0-9\_\-]+)/i', $value, $matches)) {
            $output = [
                'VideoID' => $matches[1],
                'VideoService' => 'YouTube'
            ];
        } elseif (preg_match('/youtu\.?be/i', $value)) {
            $query_string = array();
            parse_str(parse_url($value, PHP_URL_QUERY), $query_string);
            if (!empty($query_string['v'])) {
                $output = [
                    'VideoID' => $query_string['v'],
                    'VideoService' => 'YouTube'
                ];
            }
        }

        return ($output) ? $output : false;
    }
}
