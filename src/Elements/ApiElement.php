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

    /**
     * Get element meta data in their raw format, e.g. classes, title
     *
     * @return array|null
     */
    public function getMetaData();
}