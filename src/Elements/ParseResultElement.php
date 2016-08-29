<?php

namespace Hmaus\Reynaldo\Elements;

class ParseResultElement extends BaseElement implements ApiElement, ApiParseResult
{
    public function getApi()
    {
        return $this->getElementsByType(MasterCategoryElement::class)[0];
    }
}
