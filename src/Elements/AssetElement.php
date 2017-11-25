<?php

namespace Hmaus\Reynaldo\Elements;

class AssetElement extends BaseElement implements ApiElement, ApiAsset
{
    public function getContentType()
    {
        return $this->getAttribute('contentType')['content'];
    }

    public function getBody()
    {
        return $this->getContent();
    }
}