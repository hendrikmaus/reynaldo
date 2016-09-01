<?php

namespace Hmaus\Reynaldo\Elements;

class HttpRequestElement extends BaseElement implements ApiElement, ApiHttpRequest
{
    public function getMethod()
    {
        return $this->attributes['method'];
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
     * @param string $className
     * @return AssetElement|null
     */
    private function getFirstAssetByClass($className)
    {
        $assets = $this->getElementsByType(AssetElement::class);
        $assetHit = null;

        /** @var AssetElement $asset */
        foreach ($assets as $asset) {
            if (!$asset->hasClass($className)) {
                continue;
            }

            $assetHit = $asset;
            break;
        }

        return $assetHit;
    }

    public function hasMessageBodySchema()
    {
        return $this->getMessageBodySchemaAsset() !== null;
    }

    public function getMessageBodySchemaAsset()
    {
        return $this->getFirstAssetByClass('messageBodySchema');
    }

    public function getContentType()
    {
        $headers = $this->getHeaders();
        $contentType = null;

        foreach ($headers as $name => $value) {
            if ($name === 'Content-Type') {
                $contentType = $value;
                break;
            }
        }

        return $contentType;
    }

    public function getHeaders()
    {
        $attributeHeaders = $this->getAttribute('headers');

        if (!$attributeHeaders) {
            return [];
        }

        $headers = [];

        if (!$attributeHeaders) {
            return $headers;
        }

        foreach ($attributeHeaders['content'] as $header) {
            $headers[$header['content']['key']['content']] = $header['content']['value']['content'];
        }

        return $headers;
    }
}