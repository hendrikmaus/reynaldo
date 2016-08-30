<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiResourceGroup extends ApiElement
{
    /**
     * Get all resources inside this group
     *
     * @return ApiResource[]
     */
    public function getResources();
}