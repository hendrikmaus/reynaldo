<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiParseResult extends ApiElement
{
    /**
     * @return ApiDescriptionRoot
     */
    public function getApi();
}