<?php

namespace Hmaus\Reynaldo\Elements;

class HttpResponseElement extends BaseElement
{
    /**
     * Get HTTP status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return (int) $this->attributes['statusCode'];
    }

    /**
     * Get headers as `header name`: `header value` pairs
     * @return array|null
     */
    public function getHeaders()
    {
        $headersFromAttributes = $this->getAttribute('headers');
        $headers = [];

        foreach ($headersFromAttributes['content'] as $header) {
            $headers[$header['content']['key']['content']] = $header['content']['value']['content'];
        }

        return $headers;
    }

    /**
     * @return bool
     */
    public function hasMessageBody()
    {
        return $this->getMessageBodyAsset() !== null;
    }

    /**
     * @return bool
     */
    public function hasMessageBodySchema()
    {
        return $this->getMessageBodySchemaAsset() !== null;
    }

    /**
     * @return null|AssetElement
     */
    public function getMessageBodyAsset()
    {
        return $this->getFirstAssetByClass('messageBody');
    }

    /**
     * @return AssetElement|null
     */
    public function getMessageBodySchemaAsset()
    {
        return $this->getFirstAssetByClass('messageBodySchema');
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

    /**
     * @return DataStructureElement
     */
    public function getDataStructure()
    {
        return $this->getElementsByType(DataStructureElement::class)[0];
    }
}