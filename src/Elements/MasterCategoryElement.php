<?php

namespace Hmaus\Reynaldo\Elements;

class MasterCategoryElement extends BaseElement implements ApiElement, ApiDescriptionRoot
{
    public function getApiTitle()
    {
        return $this->getTitle();
    }

    public function getApiMetaData()
    {
        $meta = [];

        foreach ($this->attributes['meta'] as $member) {
            $meta[$member['content']['key']['content']] = $member['content']['value']['content'];
        }

        return $meta;
    }

    public function getResourceGroups()
    {
        return $this->getElementsByType(ResourceGroupElement::class);
    }

    public function getApiDocumentDescription()
    {
        return $this->getCopyText();
    }

    public function getDataStructureCategory()
    {
        $elements = $this->getElementsByType(DataStructureCategoryElement::class);

        if (!$elements) {
            return null;
        }

        return $elements[0];
    }

}