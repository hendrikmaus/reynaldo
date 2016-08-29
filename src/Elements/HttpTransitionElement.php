<?php

namespace Hmaus\Reynaldo\Elements;

class HttpTransitionElement extends BaseElement implements ApiElement, ApiStateTransition
{
    public function getHttpTransactions()
    {
        return $this->getElementsByType(HttpTransactionElement::class);
    }

    public function getHrefVariablesElement()
    {
        return $this->getAttribute('hrefVariables');
    }
}