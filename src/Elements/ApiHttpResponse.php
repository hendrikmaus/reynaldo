<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiHttpResponse
{
    /**
     * Get HTTP status code
     *
     * @return int
     */
    public function getStatusCode();

    /**
     * Get headers as `header name`: `header value` pairs
     *
     * @return array|null
     */
    public function getHeaders();

    /**
     * @return bool
     */
    public function hasMessageBody();

    /**
     * @return bool
     */
    public function hasMessageBodySchema();

    /**
     * @return ApiAsset|null
     */
    public function getMessageBodyAsset();

    /**
     * @return ApiAsset|null
     */
    public function getMessageBodySchemaAsset();

    /**
     * @return ApiDataStructure
     */
    public function getDataStructure();
}