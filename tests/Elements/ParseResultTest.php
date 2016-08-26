<?php

namespace Hmaus\Reynaldo\Tests\Elements;
use Hmaus\Reynaldo\Elements\ParseResult;

class ParseResultTest extends \PHPUnit_Framework_TestCase {
    public function testParseResultCanBeCreated() {
        $parseResult = new ParseResult();
        $this->assertInstanceOf(ParseResult::class, $parseResult);

        return $parseResult;
    }

    /**
     * @depends testParseResultCanBeCreated
     * @param ParseResult $parseResult
     * @return ParseResult
     */
    public function testParseResultElementKnowsItsName(ParseResult $parseResult)
    {
        $this->assertSame('parseResult', $parseResult->getName());

        return $parseResult;
    }

    /**
     * @depends testParseResultElementKnowsItsName
     * @param ParseResult $parseResult
     */
    public function testParseResultCanReturnContents(ParseResult $parseResult)
    {
        $this->assertSame([], $parseResult->getContent());
    }
}
