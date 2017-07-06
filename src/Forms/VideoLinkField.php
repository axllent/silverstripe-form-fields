<?php

namespace Axllent\FormFields\Forms;

use Axllent\FormFields\FieldType\VideoLink;

class VideoLinkField extends URLField
{

    protected $display_video = false;

    protected $preview_height = false;

    public function getAttributes()
    {
        $attributes = array(
            'class' => 'text',
            'placeholder' => 'Enter a valid YouTube or Vimeo link'
        );

        return array_merge(
            parent::getAttributes(),
            $attributes
        );
    }

    public function showPreview($maxwidth = 500, $height = '56%')
    {
        $this->display_video = $maxwidth;
        $this->preview_height = $height;
        return $this;
    }

    public function getPreview()
    {
        $url = trim($this->value);
        if (!$this->display_video || !$url) {
            return false;
        }
        $obj = VideoLink::create()->setValue($url);
        if ($obj->iFrameURL) {
            return $obj->Iframe($this->display_video, $this->preview_height);
        }
    }

    public function getVideoTitle()
    {
        $url = trim($this->value);
        return VideoLink::create()->setValue($url)->Title;
    }

    public function validate($validator)
    {
        parent::validate($validator);

        // Don't validate empty fields
        if (empty($this->value)) {
            return true;
        }

        // Use the VideoLink object to validate
        $obj = VideoLink::create()->setValue($this->value);

        if (!$obj->getService()) {
            $validator->validationError(
                $this->name,
                'Please enter a valid YouTube or Vimeo link',
                'validation'
            );
            return false;
        }

        return true;
    }
}
