<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiStateTransition extends ApiElement 
{
    /**
     * @return ApiHttpTransaction[]
     */
    public function getHttpTransactions();

    /**
     * @return ApiHrefVariables
     */
    public function getHrefVariablesElement();
}