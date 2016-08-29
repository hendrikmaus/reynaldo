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
        return $this->getAttribute('hrefVariables');
    }

    public function getDataStructure()
    {
        return $this->getElementsByType(DataStructureElement::class)[0];
    }
}