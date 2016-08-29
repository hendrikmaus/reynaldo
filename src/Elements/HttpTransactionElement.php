<?php

namespace Hmaus\Reynaldo\Elements;

class HttpTransactionElement extends BaseElement
{
    /**
     * @return HttpRequestElement
     */
    public function getHttpRequest()
    {
        return $this->getElementsByType(HttpRequestElement::class)[0];
    }

    /**
     * @return HttpResponseElement
     */
    public function getHttpResponse()
    {
        return $this->getElementsByType(HttpResponseElement::class)[0];
    }
}