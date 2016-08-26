<?php

namespace Hmaus\Reynaldo\Elements;

interface Element {

    /**
     * Returns the refract element name, e.g. "resource"
     * @return string
     */
    public function getName();

    /**
     * Return contents of the element
     * @return array|string
     */
    public function getContent();
}
