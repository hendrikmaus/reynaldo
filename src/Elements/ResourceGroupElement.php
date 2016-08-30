<?php

namespace Hmaus\Reynaldo\Elements;

class ResourceGroupElement extends BaseElement implements ApiElement, ApiResourceGroup
{
    public function getResources()
    {
        return $this->getElementsByType(ResourceElement::class);
    }
}