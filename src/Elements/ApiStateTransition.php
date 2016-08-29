<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiStateTransition
{
    /**
     * @return ApiHttpTransaction[]
     */
    public function getHttpTransactions();

    /**
     * @return ApiHrefVariables|null
     */
    public function getHrefVariablesElement();
}