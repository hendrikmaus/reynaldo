<?php

namespace Hmaus\Reynaldo\Tests\Parser;

use Hmaus\Reynaldo\Elements\AssetElement;
use Hmaus\Reynaldo\Elements\DataStructureCategoryElement;
use Hmaus\Reynaldo\Elements\DataStructureElement;
use Hmaus\Reynaldo\Elements\HrefVariablesElement;
use Hmaus\Reynaldo\Elements\HttpTransactionElement;
use Hmaus\Reynaldo\Elements\HttpTransitionElement;
use Hmaus\Reynaldo\Elements\MasterCategoryElement;
use Hmaus\Reynaldo\Elements\ResourceElement;
use Hmaus\Reynaldo\Elements\ResourceGroupElement;
use Hmaus\Reynaldo\Parser\Parser;
use Hmaus\Reynaldo\Parser\RefractParser;
use Hmaus\Reynaldo\Value\HrefVariable;

class RefractParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Determine test cases to run.
     * To add a new test, simply write the test method and add its name to `$testCases`.
     *
     * @return array [method name to call; parser to use]
     */
    public function dataProvider()
    {
        $testCases = [
            'SimplestApi' => '01. Simplest API',
            'ResourceAndActions' => '02. Resource and Actions',
            'NamedResourceAndActions' => '03. Named Resource and Actions',
            'GroupingResources' => '04. Grouping Resources',
            'Responses' => '05. Responses',
            'Requests' => '06. Requests',
            'Parameters' => '07. Parameters',
            'Attributes' => '08. Attributes',
            'AdvancedAttributes' => '09. Advanced Attributes',
            'DataStructures' => '10. Data Structures',
            'ResourceModel' => '11. Resource Model',
            'AdvancedAction' => '12. Advanced Action',
            'NamedEndpoints' => '13. Named Endpoints',
            'JsonSchema' => '14. JSON Schema',
            'AdvancedJsonSchema' => '15. Advanced JSON Schema',
            'RealWorldApi' => 'Real World API'
        ];

        $tests = [];

        foreach ($testCases as $test => $fixture) {
            $tests[] = [$test, $this->getParser(), $this->getFixtureAsArray($fixture)];
        }

        return $tests;
    }

    /**
     * @return Parser
     * @throws \Exception
     */
    private function getParser()
    {
        return new RefractParser();
    }

    /* --------------------- HELPERS --------------------- */

    /**
     * Load a fixture and return decoded json (assoc array)
     *
     * @param string $name filename without path
     * @return array
     * @throws \Exception
     */
    private function getFixtureAsArray($name)
    {
        $path = sprintf('%s/../fixtures/%s.md.%s.json', __DIR__, $name, 'refract');
        if (!file_exists($path)) {
            throw new \Exception(sprintf('Fixture file %s could not be found', $path));
        }

        return json_decode(file_get_contents($path), true);
    }

    /**
     * Actual test runner.
     *
     * @dataProvider dataProvider
     * @param string $testName
     * @param Parser $parser
     * @param array $fixture
     */
    public function testFixtures($testName, Parser $parser, array $fixture)
    {
        $this->$testName($parser, $fixture);
    }

    public function SimplestApi(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();
        $this->assertNotEmpty($api);
        $this->assertInstanceOf(MasterCategoryElement::class, $api);
        $this->assertSame(['FORMAT' => '1A'], $api->getApiMetaData());

        $this->assertSame('The Simplest API', $api->getApiTitle());
        $this->assertContains('This is one of the simplest', $api->getApiDocumentDescription());

        $resourceGroups = $api->getResourceGroups();
        $this->assertInternalType('array', $resourceGroups);

        /** @var ResourceGroupElement $resourceGroupOne */
        $resourceGroupOne = array_shift($resourceGroups);
        $this->assertInstanceOf(ResourceGroupElement::class, $resourceGroupOne);
        $this->assertEmpty($resourceGroupOne->getTitle());

        $resources = $resourceGroupOne->getResources();
        $this->assertInternalType('array', $resources);

        /** @var ResourceElement $resourceOne */
        $resourceOne = array_shift($resources);
        $this->assertEmpty($resourceOne->getTitle());
        $this->assertSame('/message', $resourceOne->getHref());

        $transitions = $resourceOne->getTransitions();
        $this->assertInternalType('array', $transitions);
        $transitionOne = array_shift($transitions);
        $this->assertInstanceOf(HttpTransitionElement::class, $transitionOne);

        $transactions = $transitionOne->getHttpTransactions();
        /** @var HttpTransactionElement $transactionOne */
        $transactionOne = array_shift($transactions);
        $this->assertInstanceOf(HttpTransactionElement::class, $transactionOne);

        $request = $transactionOne->getHttpRequest();
        $this->assertSame('GET', $request->getMethod());

        $response = $transactionOne->getHttpResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertInternalType('array', $response->getHeaders());
        $this->assertSame(['Content-Type' => 'text/plain'], $response->getHeaders());

        $assets = $response->getContent();
        /** @var AssetElement $asset */
        $asset = array_shift($assets);
        $this->assertSame('text/plain', $asset->getContentType());
        $this->assertSame("Hello World!\n", $asset->getBody());
    }

    public function ResourceAndActions(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        $this->assertSame('Resource and Actions API', $api->getApiTitle());

        /** @var ResourceGroupElement $resourceGroup */
        $resourceGroup = $api->getResourceGroups()[0];
        /** @var ResourceElement $resource */
        $resource = $resourceGroup->getResources()[0];
        $this->assertContains('This is our [resource]', $resource->getCopyText());

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[1]; // go straight to the second one, asserting the first is redundant
        $this->assertContains('OK, let\'s add another action', $transition->getCopyText());
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[0];
        $request = $transaction->getHttpRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('text/plain', $request->getContentType());
        $messageBodyAsset = $request->getMessageBodyAsset();
        $this->assertInstanceOf(AssetElement::class, $messageBodyAsset);
        $this->assertSame("All your base are belong to us.\n", $messageBodyAsset->getContent());

        $response = $transaction->getHttpResponse();
        $this->assertEmpty($response->getContent());
        $this->assertSame(204, $response->getStatusCode());

    }

    public function NamedResourceAndActions(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceGroupElement $resourceGroup */
        $resourceGroup = $api->getResourceGroups()[0];
        /** @var ResourceElement $resource */
        $resource = $resourceGroup->getResources()[0];
        $this->assertSame('My Message', $resource->getTitle());

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        $this->assertSame('Retrieve a Message', $transition->getTitle());

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[1];
        $this->assertSame('Update a Message', $transition->getTitle());
    }

    public function GroupingResources(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        $resourceGroups = $api->getResourceGroups();
        $this->assertCount(2, $resourceGroups);

        $this->assertSame('Messages', array_shift($resourceGroups)->getTitle());
        $this->assertSame('Users', array_shift($resourceGroups)->getTitle());
    }

    public function Responses(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceElement $resource */
        $resource = $api->getResourceGroups()[0]->getResources()[0];

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[0];
        $response = $transaction->getHttpResponse();
        $this->assertSame(
            ['Content-Type' => 'text/plain', 'X-My-Message-Header' => '42'],
            $response->getHeaders()
        );
        $this->assertSame("Hello World!\n", $response->getMessageBodyAsset()->getBody());

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[1];
        $response = $transaction->getHttpResponse();
        $this->assertSame(
            ['Content-Type' => 'application/json', 'X-My-Message-Header' => '42'],
            $response->getHeaders()
        );

        // do *not* change the formatting
        $body = '{ "message": "Hello World!" }
';
        $this->assertSame($body, $response->getMessageBodyAsset()->getBody());
    }

    public function Requests(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceElement $resource */
        $resource = $api->getResourceGroups()[0]->getResources()[0];

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[0];
        $request = $transaction->getHttpRequest();
        $this->assertSame('Plain Text Message', $request->getTitle());
        $this->assertSame(
            ['Accept' => 'text/plain'],
            $request->getHeaders()
        );

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[1];
        $request = $transaction->getHttpRequest();
        $this->assertSame('JSON Message', $request->getTitle());
        $this->assertSame(
            ['Accept' => 'application/json'],
            $request->getHeaders()
        );

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[1];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[0];
        $request = $transaction->getHttpRequest();
        $this->assertSame('Update Plain Text Message', $request->getTitle());
        $this->assertSame(
            ['Content-Type' => 'text/plain'],
            $request->getHeaders()
        );
        $this->assertSame("All your base are belong to us.\n", $request->getMessageBodyAsset()->getBody());

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[1];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[1];
        $request = $transaction->getHttpRequest();
        $this->assertSame('Update JSON Message', $request->getTitle());
        $this->assertSame(
            ['Content-Type' => 'application/json'],
            $request->getHeaders()
        );
        $body = '{ "message": "All your base are belong to us." }
';
        $this->assertSame($body, $request->getMessageBodyAsset()->getBody());
    }

    public function Parameters(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceGroupElement $resourceGroup */
        $resourceGroup = $api->getResourceGroups()[0];
        $resources = $resourceGroup->getResources();
        $this->assertCount(2, $resources);

        /** My Message Resource */
        /** @var ResourceElement $resource */
        $resource = $resources[0];
        $this->assertSame('/message/{id}', $resource->getHref());

        $hrefVariablesElement = $resource->getHrefVariablesElement();
        $this->assertInstanceOf(HrefVariablesElement::class, $hrefVariablesElement);
        $hrefVariableObjects = $hrefVariablesElement->getAllVariables();

        /** @var HrefVariable $hrefVariable */
        $hrefVariable = array_shift($hrefVariableObjects);
        $this->assertSame('id', $hrefVariable->name);
        $this->assertSame(1, $hrefVariable->example);
        $this->assertSame(null, $hrefVariable->default);
        $this->assertSame('number', $hrefVariable->dataType);
        $this->assertSame('optional', $hrefVariable->required);
        $this->assertSame('An unique identifier of the message.', $hrefVariable->description);

        /** All My Messages */
        /** @var ResourceElement $resource */
        $resource = $resources[1];
        $this->assertSame('/messages{?limit,type}', $resource->getHref());

        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        $hrefVariablesElement = $transition->getHrefVariablesElement();
        $this->assertInstanceOf(HrefVariablesElement::class, $hrefVariablesElement);
        $hrefVariableObjects = $hrefVariablesElement->getAllVariables();

        /** @var HrefVariable $hrefVariable */
        $hrefVariable = array_shift($hrefVariableObjects);
        $this->assertSame('limit', $hrefVariable->name);
        $this->assertSame(null, $hrefVariable->example);
        $this->assertSame('20', $hrefVariable->default);
        $this->assertSame('number', $hrefVariable->dataType);
        $this->assertSame('optional', $hrefVariable->required);
        $this->assertSame('The maximum number of results to return.', $hrefVariable->description);
        $this->assertEmpty($hrefVariable->values);

        /** @var HrefVariable $hrefVariable */
        $hrefVariable = array_shift($hrefVariableObjects);
        $this->assertSame('type', $hrefVariable->name);
        $this->assertSame('unread', $hrefVariable->example);
        $this->assertSame('all', $hrefVariable->default);
        $this->assertSame('string', $hrefVariable->dataType);
        $this->assertSame('optional', $hrefVariable->required);
        $this->assertSame('The type of messages to return.', $hrefVariable->description);
        $this->assertEquals(['all', 'unread', 'read'], $hrefVariable->values);
    }

    public function Attributes(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceGroupElement $resourceGroup */
        $resourceGroup = $api->getResourceGroups()[0];
        /** @var ResourceElement $resource */
        $resource = $resourceGroup->getResources()[0];
        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[0];
        $response = $transaction->getHttpResponse();

        $dataStructure = $response->getDataStructure();
        $this->assertInstanceOf(DataStructureElement::class, $dataStructure);

        $messageBodyAsset = $response->getMessageBodyAsset();
        $body = '{
    "id": "250FF",
    "created": 1415203908,
    "percent_off": 25,
    "redeem_by": null
}
';
        $this->assertSame($body, $messageBodyAsset->getBody());

        $messageBodySchemaAsset = $response->getMessageBodySchemaAsset();
        $body = '{
  "$schema": "http://json-schema.org/draft-04/schema#",
  "type": "object",
  "properties": {
    "id": {
      "type": "string"
    },
    "created": {
      "type": "number",
      "description": "Time stamp"
    },
    "percent_off": {
      "type": "number",
      "description": "A positive integer between 1 and 100 that represents the discount\nthe coupon will apply.\n"
    },
    "redeem_by": {
      "type": "number",
      "description": "Date after which the coupon can no longer be redeemed"
    }
  },
  "required": [
    "id"
  ]
}';
        $this->assertSame($body, $messageBodySchemaAsset->getBody());
        $this->assertSame('application/schema+json', $messageBodySchemaAsset->getContentType());
    }

    public function AdvancedAttributes(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceGroupElement $resourceGroup */
        $resourceGroup = $api->getResourceGroups()[0];

        /** @var ResourceElement $resource */
        $resource = $resourceGroup->getResources()[0];
        $dataStructure = $resource->getDataStructure();
        $this->assertInstanceOf(DataStructureElement::class, $dataStructure);
        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[0];
        $response = $transaction->getHttpResponse();

        // assert that the data structure on the response references the structure defined inside the resource
        $resourceDataStructure = $resource->getDataStructure();
        $responseDataStructure = $response->getDataStructure();
        $this->assertSame(
            $responseDataStructure->getContent()[0]['element'],
            $resourceDataStructure->getContent()[0]->getMetaData()['id']
        );
        $messageBodyAsset = $response->getMessageBodyAsset();
        $body = '{
  "id": "250FF",
  "created": 1415203908,
  "percent_off": 25,
  "redeem_by": 0
}';
        $this->assertSame($body, $messageBodyAsset->getBody());
        $this->assertNotNull($response->hasMessageBodySchema());
    }

    public function DataStructures(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        $this->assertInstanceOf(DataStructureCategoryElement::class, $api->getDataStructureCategory());

        // Asserting the rest is done by a previous test already.
    }

    public function ResourceModel(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceGroupElement $resourceGroup */
        $resourceGroup = $api->getResourceGroups()[0];
        /** @var ResourceElement $resource */
        $resource = $resourceGroup->getResources()[0];
        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[0];
        $response = $transaction->getHttpResponse();
        $messageBodyAsset = $response->getMessageBodyAsset();
        $body = '{
  "class": [ "message" ],
  "properties": {
        "message": "Hello World!"
  },
  "links": [
        { "rel": "self" , "href": "/message" }
  ]
}
';
        $this->assertSame($body, $messageBodyAsset->getBody());
    }

    public function AdvancedAction(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceGroupElement $resourceGroup */
        $resourceGroup = $api->getResourceGroups()[0];
        /** @var ResourceElement $resource */
        $resource = $resourceGroup->getResources()[0];

        $this->assertCount(3, $resource->getTransitions());
    }

    public function NamedEndpoints(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceGroupElement $resourceGroup */
        $resourceGroup = $api->getResourceGroups()[0];

        $this->assertCount(2, $resourceGroup->getResources());
    }

    public function JsonSchema(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        /** @var ResourceGroupElement $resourceGroup */
        $resourceGroup = $api->getResourceGroups()[0];
        /** @var ResourceElement $resource */
        $resource = $resourceGroup->getResources()[0];

        // test schema for *response*
        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[0];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[0];
        $response = $transaction->getHttpResponse();
        $this->assertTrue($response->hasMessageBody());
        $this->assertTrue($response->hasMessageBodySchema());

        // test schema for *request*
        /** @var HttpTransitionElement $transition */
        $transition = $resource->getTransitions()[1];
        /** @var HttpTransactionElement $transaction */
        $transaction = $transition->getHttpTransactions()[0];
        $request = $transaction->getHttpRequest();
        $this->assertTrue($request->hasMessageBody());
        $this->assertTrue($request->hasMessageBodySchema());
    }

    public function AdvancedJsonSchema(Parser $parser, $fixture)
    {
        // re-uses the basic json schema test case
        $this->JsonSchema($parser, $fixture);
    }

    public function RealWorldApi(Parser $parser, $fixture)
    {
        $result = $parser->parse($fixture);
        $api = $result->getApi();

        $this->assertNotEmpty($api->getApiTitle());
        $this->assertNotEmpty($api->getApiMetaData());
        $this->assertNotEmpty($api->getApiDocumentDescription());

        foreach ($api->getResourceGroups() as $resourceGroup) {
            $this->assertNotEmpty($resourceGroup->getTitle());
            $this->assertNotEmpty($resourceGroup->getCopyText());
            $this->assertTrue($resourceGroup->hasClass('resourceGroup'));

            foreach ($resourceGroup->getResources() as $resource) {
                $this->assertNotEmpty($resource->getTitle());
                $this->assertNotEmpty($resource->getHref());
                $this->assertNotEmpty($resource->getCopyText());

                foreach ($resource->getTransitions() as $transition) {
                    $this->assertNotEmpty($transition->getTitle());
                    $this->assertNotEmpty($transition->getCopyText());

                    foreach ($transition->getHttpTransactions() as $httpTransaction) {
                        $this->assertNotEmpty($httpTransaction->getHttpRequest());
                        $this->assertNotEmpty($httpTransaction->getHttpRequest()->getMethod());
                        $this->assertNotEmpty($httpTransaction->getHttpResponse());
                        $this->assertNotEmpty($httpTransaction->getHttpResponse()->getStatusCode());

                        // in this example fixture, we only ever have HTTP 200's with header on the response
                        if ($httpTransaction->getHttpResponse()->getStatusCode() === 200) {
                            $this->assertNotEmpty($httpTransaction->getHttpResponse()->getHeaders());
                        }
                    }
                }
            }
        }
    }
}
