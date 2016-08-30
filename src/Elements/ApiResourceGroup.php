<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiResourceGroup
{
    /**
     * Get all resources inside this group
     *
     * @return ApiResource[]
     */
    public function getResources();
}