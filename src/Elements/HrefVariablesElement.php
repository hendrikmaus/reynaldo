<?php

namespace Hmaus\Reynaldo\Elements;

use Hmaus\Reynaldo\Value\HrefVariable;

class HrefVariablesElement extends BaseElement implements ApiElement, ApiHrefVariables
{
    public function getAllVariables()
    {
        $variables = [];

        /** @var MemberElement $member */
        foreach ($this->getElementsByType(MemberElement::class) as $member) {
            $memberContent = $member->getContent();

            $hrefVariable = new HrefVariable();
            $hrefVariable->dataType = $memberContent['value']['element'];
            $hrefVariable->description = $member->getMetaData()['description'];
            $hrefVariable->name = $memberContent['key']['content'];

            $typeAttributes = $member->getAttribute('typeAttributes');
            if ($typeAttributes) {
                $hrefVariable->required = $typeAttributes[0];
            } else {
                $hrefVariable->required = 'optional';
            }

            if (isset($memberContent['value']['content'])) {
                $hrefVariable->example = $memberContent['value']['content'];
            }

            if (isset($memberContent['value']['attributes'])) {
                if (isset($memberContent['value']['attributes']['default'])) {
                    $hrefVariable->default = $memberContent['value']['attributes']['default'];
                }
            }

            $variables[] = $hrefVariable;
        }

        return $variables;
    }
}