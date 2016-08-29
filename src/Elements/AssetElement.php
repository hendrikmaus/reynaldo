<?php

namespace Hmaus\Reynaldo\Elements;

class AssetElement extends BaseElement
{
    /**
     * Get content type, e.g. text/plain
     *
     * @return string|null
     */
    public function getContentType()
    {
        return $this->getAttribute('contentType');
    }

    /**
     * Get content of the assets body
     *
     * @return string|null
     */
    public function getBody()
    {
        return $this->getContent();
    }
}