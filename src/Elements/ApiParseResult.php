<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiParseResult
{
    /**
     * @return ApiDescriptionRoot
     */
    public function getApi();
}