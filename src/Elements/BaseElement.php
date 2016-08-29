<?php

namespace Hmaus\Reynaldo\Elements;

class BaseElement // todo implement a builder interface to hide methods from the outside e.g. addContentElement etc
{
    /**
     * @var array|null
     */
    protected $meta;

    /**
     * @var array|null
     */
    protected $attributes;

    /**
     * @var mixed
     */
    protected $content;

    public function __construct(array $element)
    {
        $this->meta = isset($element['meta']) ? $element['meta'] : null;
        $this->attributes = isset($element['attributes']) ? $element['attributes'] : null;
        $this->content = isset($element['content']) ? $element['content'] : null;
    }

    /**
     * Add content element to the current content
     *
     * This method has to be used sequentially while querying the recursive
     * element tree. It will replace the raw content element one by one, top to bottom.
     *
     * Example:
     *
     * - copy element
     * - raw array element (1)
     * - raw array element (2)
     *
     * Calling `addContentElement` with a resource element:
     *
     * - copy element
     * - resource element
     * - raw array element (2)
     *
     * As you can see `raw array element (1)` was replaced with `resource element`
     * So *you* need to make sure that *you* turned the raw array element into the resource element.
     *
     * @param BaseElement $element
     */
    public function addContentElement(BaseElement $element)
    {
        $newContent = [];
        $rawElements = [];
        $content = $this->getContent();

        if ($this->hasCopy()) {
            $newContent[] = array_shift($content);
        }

        foreach ($content as $resourceGroup) {
            if (!is_array($resourceGroup)) {
                $newContent[] = array_shift($content);
            } else {
                $rawElements[] = $resourceGroup;
            }
        }

        array_shift($rawElements);
        $newContent[] = $element;

        $this->content = array_merge($newContent, $rawElements);
    }

    /**
     * Useful helper when creating elements that have api elements in their attributes
     *
     * @param string $attributeName
     * @param BaseElement $element
     */
    public function replaceAttributeWithElement($attributeName, BaseElement $element)
    {
        $this->attributes[$attributeName] = $element;
    }

    /**
     * Simple getter for the content of the current element
     *
     * @return mixed|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Check if the first content element is a copy element
     *
     * @api
     * @return bool
     */
    public function hasCopy()
    {
        return !empty($this->getCopyText());
    }

    /**
     * Get the copy text of the current element; raw markdown likely
     *
     * @api
     * @return string|null
     */
    public function getCopyText()
    {
        $content = $this->getContent();
        $copy = array_shift($content);

        if (!is_array($copy)) {
            return null;
        }

        if ($copy['element'] !== 'copy') {
            return null;
        }

        return $copy['content'];
    }

    /**
     * Query content of current element (single level) for elements of given type
     *
     * @param string $type Fully Qualified Class Name, e.g. ResourceElement::class
     * @return BaseElement[]
     */
    public function getElementsByType($type)
    {
        $elements = [];
        $content = $this->getContent();

        foreach ($content as $element) {
            if ($element instanceof $type) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    /**
     * Get a given attribute by name
     *
     * @param string $name
     * @return mixed|null
     */
    public function getAttribute($name)
    {
        if (!isset($this->attributes[$name])) {
            return null;
        }

        return $this->attributes[$name];
    }

    /**
     * Check whether or not this element has a given class
     *
     * @param string $className
     * @return bool
     */
    public function hasClass($className)
    {
        foreach ($this->meta['classes'] as $classInMeta) {
            if ($classInMeta === $className) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Meta data sitting on the element, e.g. classes
     *
     * @api
     * @return array|null
     */
    public function getMetaData()
    {
        return $this->meta;
    }

    /**
     * Get title of the element, may be an empty string
     *
     * @api
     * @return string
     */
    public function getTitle()
    {
        return $this->getMetaData()['title'];
    }
}