<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiHttpTransaction extends ApiElement
{
    /**
     * @return ApiHttpRequest
     */
    public function getHttpRequest();

    /**
     * @return ApiHttpResponse
     */
    public function getHttpResponse();
}