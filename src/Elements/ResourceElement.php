<?php

namespace Hmaus\Reynaldo\Elements;

class ResourceElement extends BaseElement
{
    /**
     * @return null|string
     */
    public function getHref()
    {
        return $this->getAttribute('href');
    }

    /**
     * @return HttpTransitionElement[]
     */
    public function getTransitions()
    {
        return $this->getElementsByType(HttpTransitionElement::class);
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

    /**
     * @return DataStructureElement
     */
    public function getDataStructure()
    {
        return $this->getElementsByType(DataStructureElement::class)[0];
    }
}