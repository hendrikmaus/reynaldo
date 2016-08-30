<?php

namespace Hmaus\Reynaldo\Elements;

use Hmaus\Reynaldo\Value\HrefVariable;

interface ApiHrefVariables
{
    /**
     * Get all variables as objects
     *
     * @return HrefVariable[]
     */
    public function getAllVariables();
}