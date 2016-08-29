<?php

namespace Hmaus\Reynaldo\Parser;

use Hmaus\Reynaldo\Elements\ParseResultElement;

interface Parser
{
    /**
     * Parse API Description to PHP data structure
     *
     * @param array $description
     * @return ParseResultElement
     */
    public function parse(array $description);
}