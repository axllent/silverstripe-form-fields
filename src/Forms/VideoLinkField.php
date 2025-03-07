<?php

namespace Axllent\FormFields\Forms;

use Axllent\FormFields\FieldType\VideoLink;
use SilverStripe\Core\Validation\ValidationResult;

class VideoLinkField extends URLField
{
    /**
     * Preview width
     *
     * @var false|int
     *
     * @config
     */
    protected $preview_width = 500;

    /**
     * Preview height
     *
     * @var string
     *
     * @config
     */
    protected $preview_height = '56%';

    /**
     * Return field attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [
            'class'       => 'text',
            'placeholder' => _t(
                __CLASS__ . '.Placeholder',
                'Enter a valid YouTube or Vimeo link'
            ),
        ];

        return array_merge(
            parent::getAttributes(),
            $attributes
        );
    }

    /**
     * Display a video preview
     *
     * @param int $max_width Maximum width
     * @param int $height    Height in percent or pixels
     *
     * @return VideoLinkField
     */
    public function showPreview($max_width = 500, $height = '56%')
    {
        $this->preview_width  = $max_width;
        $this->preview_height = $height;

        return $this;
    }

    /**
     * Return video preview
     *
     * @return VideoLink
     */
    public function getPreview()
    {
        $url = trim(strval($this->value));
        if (!$this->preview_width || !$url) {
            return false;
        }

        $obj = VideoLink::create()->setValue($url);
        if ($obj->iFrameURL) {
            return $obj->Iframe($this->preview_width, $this->preview_height);
        }
    }

    /**
     * Return video title
     *
     * @return string
     */
    public function getVideoTitle()
    {
        $url = trim(strval($this->value));

        return VideoLink::create()->setValue($url)->Title;
    }

    /**
     * Return validation result
     *
     * @return bool
     */
    public function validate(): ValidationResult
    {
        $result = ValidationResult::create();

        // Don't validate empty fields
        if (empty($this->value)) {
            return $result;
        }

        // Use the VideoLink object to validate
        $obj = VideoLink::create()->setValue($this->value);

        if (!$obj->getService()) {
            $result->addFieldError(
                $this->name,
                _t(
                    __CLASS__ . '.ValidationError',
                    'Please enter a valid YouTube or Vimeo link'
                ),
                'validation'
            );
        }

        return $result;
    }
}
