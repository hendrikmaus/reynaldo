<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiHttpRequest
{
    /**
     * Get HTTP Method
     *
     * @return string
     */
    public function getMethod();

    /**
     * @return bool
     */
    public function hasMessageBody();

    /**
     * @return bool
     */
    public function hasMessageBodySchema();

    /**
     * @return null|AssetElement
     */
    public function getMessageBodyAsset();

    /**
     * @return AssetElement|null
     */
    public function getMessageBodySchemaAsset();

    /**
     * Get headers as `header name`: `header value` pairs
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Try to fetch content type from headers
     *
     * @return string|null
     */
    public function getContentType();
}