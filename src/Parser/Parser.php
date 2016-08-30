<?php

namespace Hmaus\Reynaldo\Parser;

use Hmaus\Reynaldo\Elements\ApiParseResult;

interface Parser
{
    /**
     * Parse API Description to PHP data structure
     *
     * @param array $description
     * @return ApiParseResult
     */
    public function parse(array $description);
}