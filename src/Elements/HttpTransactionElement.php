<?php

namespace Hmaus\Reynaldo\Elements;

class HttpTransactionElement extends BaseElement implements ApiElement, ApiHttpTransaction
{
    public function getHttpRequest()
    {
        return $this->getElementsByType(HttpRequestElement::class)[0];
    }

    public function getHttpResponse()
    {
        return $this->getElementsByType(HttpResponseElement::class)[0];
    }
}