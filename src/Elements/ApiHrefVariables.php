<?php

namespace Hmaus\Reynaldo\Elements;

use Hmaus\Reynaldo\Value\HrefVariable;

interface ApiHrefVariables extends ApiElement
{
    /**
     * Get all variables as objects
     *
     * @return HrefVariable[]
     */
    public function getAllVariables();
}