<?php

namespace Hmaus\Reynaldo\Elements;

class HttpTransactionElement extends BaseElement implements ApiElement, ApiHttpTransaction
{
    public function getHttpRequest()
    {
        return $this->getFirstElementByType(HttpRequestElement::class);
    }

    public function getHttpResponse()
    {
        return $this->getFirstElementByType(HttpResponseElement::class);
    }
}