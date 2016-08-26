<?php

namespace Hmaus\Reynaldo\Elements;

class ParseResult implements Element {
    public function getName() {
        return 'parseResult';
    }

    /**
     * Return contents of the element
     * @return array|string
     */
    public function getContent()
    {
        return [];
    }
}
