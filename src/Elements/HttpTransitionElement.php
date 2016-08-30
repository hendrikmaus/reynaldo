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
        $element = $this->getAttribute('hrefVariables');

        if (!$element) {
            $element = new HrefVariablesElement([]);
        }

        return $element;
    }
}