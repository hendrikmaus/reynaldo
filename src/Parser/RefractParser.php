<?php

namespace Hmaus\Reynaldo\Parser;

use Hmaus\Reynaldo\Elements\ApiParseResult;
use Hmaus\Reynaldo\Elements\AssetElement;
use Hmaus\Reynaldo\Elements\BaseElement;
use Hmaus\Reynaldo\Elements\DataStructureCategoryElement;
use Hmaus\Reynaldo\Elements\DataStructureElement;
use Hmaus\Reynaldo\Elements\HrefVariablesElement;
use Hmaus\Reynaldo\Elements\HttpRequestElement;
use Hmaus\Reynaldo\Elements\HttpResponseElement;
use Hmaus\Reynaldo\Elements\HttpTransactionElement;
use Hmaus\Reynaldo\Elements\HttpTransitionElement;
use Hmaus\Reynaldo\Elements\MasterCategoryElement;
use Hmaus\Reynaldo\Elements\MemberElement;
use Hmaus\Reynaldo\Elements\ObjectElement;
use Hmaus\Reynaldo\Elements\ParseResultElement;
use Hmaus\Reynaldo\Elements\ResourceElement;
use Hmaus\Reynaldo\Elements\ResourceGroupElement;

class RefractParser implements Parser
{
    /**
     * @var ApiParseResult
     */
    private $parseResult;

    /**
     * Map api elements to php classes
     * @var array
     */
    private $elementMap = [
        'resource' => ResourceElement::class,
        'transition' => HttpTransitionElement::class,
        'httpTransaction' => HttpTransactionElement::class,
        'httpRequest' => HttpRequestElement::class,
        'httpResponse' => HttpResponseElement::class,
        'asset' => AssetElement::class,
        'hrefVariables' => HrefVariablesElement::class,
        'member' => MemberElement::class,
        'dataStructure' => DataStructureElement::class,
        'object' => ObjectElement::class,
    ];

    public function parse(array $description)
    {
        $this->parseResult = new ParseResultElement($description);
        $this->iterate($description['content']);

        return $this->parseResult;
    }

    /**
     * Iterate given content array and call `process` for every element inside
     *
     * @param array $content
     * @param null|BaseElement $parent
     */
    private function iterate(array $content, $parent = null)
    {
        $iterator = isset($content['content']) ? $content['content'] : $content;
        foreach ($iterator as $element) {
            if (!is_string($element)) {
                $this->process($element, $parent);
            }
        }
    }

    /**
     * Create php classes using raw element data; called recursively
     *
     * @param array $element
     * @param null|BaseElement $parent
     */
    private function process(array $element, $parent = null)
    {
        $apiElement = $element['element'];

        if (isset($this->elementMap[$apiElement])) {
            $this->processElement(
                $element,
                $this->elementMap[$apiElement],
                $parent
            );
        }

        if ($element['element'] === 'category') {
            // todo extend the element map to check for classes, then merge processCategory into processElement
            $this->processCategory($element, $parent);
        }
    }

    /**
     * Create new element from raw data and add it to its parent
     *
     * @param array $element Raw element data
     * @param string $class FQCN to use for creating an element
     * @param BaseElement $parent parent element to add the newly created one to
     * @param null|string $replaceAttribute usually, the new element will be added to the content
     *                                          of the given parent. There are some cases, hrefVariables for example,
     *                                          where the attributes actually contain elements as well.
     *                                          To add the new element to the attributes, pass the name of the
     *                                          key inside of attributes that should be replaced
     */
    private function processElement(array $element, $class, BaseElement $parent, $replaceAttribute = null)
    {
        $apiElement = new $class($element);

        if (!$replaceAttribute) {
            $parent->addContentElement($apiElement);
        } else {
            $parent->replaceAttributeWithElement($replaceAttribute, $apiElement);
        }

        if (isset($element['attributes'])) {
            foreach ($element['attributes'] as $attributeName => $attributeValue) {
                if (!isset($this->elementMap[$attributeName])) {
                    continue;
                }

                $this->processElement(
                    $attributeValue,
                    $this->elementMap[$attributeName],
                    $apiElement,
                    $attributeName
                );
            }
        }

        if (!isset($element['content'])) {
            return;
        }

        if (!is_array($element['content'])) {
            return;
        }

        $this->iterate($element['content'], $apiElement);
    }

    /**
     * Helper to create different php classes from element `category` which carries its actual meaning in its classes
     *
     * @param array $element
     * @param null|BaseElement $parent
     */
    private function processCategory(array $element, $parent = null)
    {
        if ($this->isMasterCategory($element)) {
            $this->createCategory($element, MasterCategoryElement::class, $this->parseResult);
        }

        if ($this->isResourceGroup($element)) {
            $this->createCategory($element, ResourceGroupElement::class, $parent);
        }

        if ($this->isDataStructureCategory($element)) {
            $this->createCategory($element, DataStructureCategoryElement::class, $parent);
        }
    }

    /**
     * Detect master category
     *
     * @param array $element
     * @return bool
     */
    private function isMasterCategory(array $element)
    {
        return $this->hasClass('api', $element);
    }

    /**
     * Class query helper
     *
     * @param string $className
     * @param array $element
     * @return bool
     */
    private function hasClass($className, array $element)
    {
        if (!isset($element['meta']['classes']['content'])) {
            return in_array($className, $element['meta']['classes']);
        }

        return $className === $element['meta']['classes']['content'][0]['content'];
    }

    /**
     * Sub-helper for `processCategory`
     *
     * @param array $element
     * @param string $className
     * @param BaseElement $parent
     */
    private function createCategory(array $element, $className, BaseElement $parent)
    {
        $newElement = new $className($element);
        $parent->addContentElement($newElement);
        $this->iterate($element['content'], $newElement);
    }

    /**
     * Detect resource group
     *
     * @param array $element
     * @return bool
     */
    private function isResourceGroup(array $element)
    {
        return $this->hasClass('resourceGroup', $element);
    }

    /**
     * Detect data structures group
     *
     * @param array $element
     * @return bool
     */
    private function isDataStructureCategory(array $element)
    {
        return $this->hasClass('dataStructures', $element);
    }
}