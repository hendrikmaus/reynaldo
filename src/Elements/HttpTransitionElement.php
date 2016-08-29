<?php

namespace Hmaus\Reynaldo\Elements;

class HttpTransitionElement extends BaseElement
{
    /**
     * @return HttpTransactionElement[]
     */
    public function getHttpTransactions()
    {
        return $this->getElementsByType(HttpTransactionElement::class);
    }

    /**
     * @return null|HrefVariablesElement
     */
    public function getHrefVariablesElement()
    {
        if (!isset($this->attributes['hrefVariables'])) {
            return null;
        }

        return $this->attributes['hrefVariables'];
    }
}