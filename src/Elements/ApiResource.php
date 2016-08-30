<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiResource
{
    /**
     * @return null|string
     */
    public function getHref();

    /**
     * @return ApiStateTransition[]
     */
    public function getTransitions();

    /**
     * @return ApiHrefVariables|null
     */
    public function getHrefVariablesElement();

    /**
     * @return ApiDataStructure
     */
    public function getDataStructure();
}