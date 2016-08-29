<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiElement
{
    /**
     * Get title/name of the element
     *
     * @return string can be an empty string
     */
    public function getTitle();

    /**
     * Get copy text of the element; markdown
     *
     * @return string|null
     */
    public function getCopyText();
}