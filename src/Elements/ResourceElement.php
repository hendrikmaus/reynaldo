<?php

namespace Hmaus\Reynaldo\Elements;

class ResourceElement extends BaseElement implements ApiElement, ApiResource
{
    public function getHref()
    {
        return $this->getAttribute('href');
    }

    public function getTransitions()
    {
        return $this->getElementsByType(HttpTransitionElement::class);
    }

    public function getHrefVariablesElement()
    {
        $element = $this->getAttribute('hrefVariables');

        if (!$element) {
            $element = new HrefVariablesElement([]);
        }

        return $element;
    }

    public function getDataStructure()
    {
        return $this->getFirstElementByType(DataStructureElement::class);
    }
}