<?php

namespace Hmaus\Reynaldo\Elements;

class HttpRequestElement extends BaseElement
{
    /**
     * Get HTTP Method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->attributes['method'];
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

    /**
     * Get headers as `header name`: `header value` pairs
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];

        foreach ($this->attributes['headers']['content'] as $header) {
            $headers[$header['content']['key']['content']] = $header['content']['value']['content'];
        }

        return $headers;
    }

    /**
     * Try to fetch content type from headers
     *
     * @return string|null
     */
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
}