<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiHttpRequest extends ApiElement
{
    /**
     * Get HTTP Method
     *
     * @return string
     */
    public function getMethod();

    /**
     * Whether or not the element has a message body.
     * You'll be able to get the message body as an ApiAsset using `getMessageBodyAsset`
     *
     * @return bool
     */
    public function hasMessageBody();

    /**
     * Whether or not the element has schema for its message body.
     * You'll be able to get the schema as an ApiAsset using `getMessageBodySchemaAsset`
     *
     * @return bool
     */
    public function hasMessageBodySchema();

    /**
     * Get message body asset.
     * Call `getBody` to get to the contents.
     *
     * @return ApiAsset|null
     */
    public function getMessageBodyAsset();

    /**
     * Get message body schema asset.
     * Call `getBody` to get to the contents.
     *
     * @return ApiAsset|null
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