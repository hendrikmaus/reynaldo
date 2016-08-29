<?php

namespace Hmaus\Reynaldo\Elements;

class MasterCategoryElement extends BaseElement
{
    /**
     * @return string
     */
    public function getApiTitle()
    {
        return $this->getTitle();
    }

    /**
     * Returns meta data defined at the top of the document
     *
     * E.g.: for API Blueprint, one will find the format `FORMAT: 1A`
     * @return array|null Key value pairs
     */
    public function getApiMetaData()
    {
        $meta = [];

        foreach ($this->attributes['meta'] as $member) {
            $meta[$member['content']['key']['content']] = $member['content']['value']['content'];
        }

        return $meta;
    }

    /**
     * Get all resource groups
     *
     * @return ResourceGroupElement[]
     */
    public function getResourceGroups()
    {
        return $this->getElementsByType(ResourceGroupElement::class);
    }

    /**
     * Get raw markdown string of everything above the first api description element
     *
     * @return null|string
     */
    public function getApiDocumentDescription()
    {
        return $this->getCopyText();
    }

    /**
     * Get the category holding all the data structures inside the document
     *
     * @return DataStructureCategoryElement|null
     */
    public function getDataStructureCategory()
    {
        $elements = $this->getElementsByType(DataStructureCategoryElement::class);

        if (!$elements) {
            return null;
        }

        return $elements[0];
    }

}