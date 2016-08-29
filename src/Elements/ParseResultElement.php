<?php

namespace Hmaus\Reynaldo\Elements;

class ParseResultElement extends BaseElement
{
    /**
     * Return the parsed API description
     *
     * @return MasterCategoryElement
     * @throws \Exception
     */
    public function getApi()
    {
        return $this->getElementsByType(MasterCategoryElement::class)[0];
    }
}
