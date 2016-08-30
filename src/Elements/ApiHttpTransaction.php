<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiHttpTransaction
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