<?php

namespace Hmaus\Reynaldo\Tests\Elements;
use Hmaus\Reynaldo\Elements\ParseResult;

class ParseResultTest extends \PHPUnit_Framework_TestCase {
    public function testParseResultCanBeCreated() {
        $parseReslt = new ParseResult();
        $this->assertInstanceOf(ParseResult::class, $parseReslt);
    }
}
