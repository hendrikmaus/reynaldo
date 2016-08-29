<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiAsset
{
    /**
     * Get content type, e.g. text/plain
     *
     * @return string|null
     */
    public function getContentType();

    /**
     * Get content of the assets' body
     *
     * @return string|null
     */
    public function getBody();
}