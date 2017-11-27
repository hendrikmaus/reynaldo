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
            $memberMetadata = $member->getMetaData();

            $hrefVariable = new HrefVariable();
            $hrefVariable->description = $memberMetadata['description']['content'];
            $hrefVariable->name = $memberContent['key']['content'];

            $typeAttributes = $member->getAttribute('typeAttributes');
            if ($typeAttributes['content']) {
                $hrefVariable->required = $typeAttributes['content'][0]['content'];
            } else {
                $hrefVariable->required = 'optional';
            }


            $dataType = $memberMetadata['title']['content'];
            if ($dataType === 'enum' && isset($memberContent['value']['content'])) {
                $hrefVariable->dataType = $memberContent['value']['content'][0]['element'];
                $hrefVariable->values = array_map(function($v) {
                    return is_object($v) ? $v->content : $v['content'];
                }, $memberContent['value']['content']);

                if (isset($memberContent['value']['attributes']['samples'])) {
                    $hrefVariable->example = $memberContent['value']['attributes']['samples'][0][0]['content'];
                }

                if (isset($memberContent['value']['attributes']['default'])) {
                    $hrefVariable->default = $memberContent['value']['attributes']['default'][0]['content'];
                }
            } else {
                $hrefVariable->dataType = $dataType;
                $hrefVariable->values = [];

                if (isset($memberContent['value']['content'])) {
                    $hrefVariable->example = is_array($memberContent['value']['content']) ? $memberContent['value']['content']['content'] : $memberContent['value']['content'];
                }

                if (isset($memberContent['value']['attributes']['default'])) {
                    $hrefVariable->default = $memberContent['value']['attributes']['default']['content'];
                }
            }

            $variables[] = $hrefVariable;
        }

        return $variables;
    }
}