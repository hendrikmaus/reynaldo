<?php

namespace Hmaus\Reynaldo\Elements;

interface ApiDescriptionRoot
{
    /**
     * Main API Title at the top of the description document
     *
     * @return string can be an empty string
     */
    public function getApiTitle();

    /**
     * Returns meta data defined at the top of the document
     *
     * E.g.: for API Blueprint, one will find the format `FORMAT: 1A`
     * @return array|null Key value pairs
     */
    public function getApiMetaData();

    /**
     * Get raw markdown string of everything above the first api description element
     *
     * @return string|null
     */
    public function getApiDocumentDescription();

    /**
     * Get all resource groups
     *
     * @return ApiResourceGroup[]
     */
    public function getResourceGroups();

    /**
     * Get the category holding all the data structures inside the document
     *
     * @return ApiDataStructures|null
     */
    public function getDataStructureCategory();
}