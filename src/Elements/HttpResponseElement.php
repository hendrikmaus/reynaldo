<?php

namespace Hmaus\Reynaldo\Elements;

class HttpResponseElement extends BaseElement implements ApiElement, ApiHttpResponse
{
    public function getStatusCode()
    {
        return (int)$this->attributes['statusCode'];
    }

    public function getHeaders()
    {
        $headersFromAttributes = $this->getAttribute('headers');

        if (!$headersFromAttributes) {
            return [];
        }

        $headers = [];

        foreach ($headersFromAttributes['content'] as $header) {
            $headers[$header['content']['key']['content']] = $header['content']['value']['content'];
        }

        return $headers;
    }

    public function hasMessageBody()
    {
        return $this->getMessageBodyAsset() !== null;
    }

    public function getMessageBodyAsset()
    {
        return $this->getFirstAssetByClass('messageBody');
    }

    /**
     * @param $className
     * @return AssetElement|null
     */
    private function getFirstAssetByClass($className)
    {
        $assets = $this->getElementsByType(AssetElement::class);

        /** @var AssetElement $asset */
        foreach ($assets as $asset) {
            if ($asset->hasClass($className)) {
                return $asset;
            }
        }

        return null;
    }

    public function hasMessageBodySchema()
    {
        return $this->getMessageBodySchemaAsset() !== null;
    }

    public function getMessageBodySchemaAsset()
    {
        return $this->getFirstAssetByClass('messageBodySchema');
    }

    public function getDataStructure()
    {
        return $this->getFirstElementByType(DataStructureElement::class);
    }
}