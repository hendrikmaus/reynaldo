<?php

namespace Hmaus\Reynaldo\Elements;

class ResourceGroupElement extends BaseElement
{
    /**
     * Get all resources inside this group
     *
     * @return ResourceElement[]
     */
    public function getResources()
    {
        return $this->getElementsByType(ResourceElement::class);
    }
}