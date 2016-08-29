<?php

use Hmaus\Reynaldo\Parser\RefractParser;

require __DIR__ . '/../vendor/autoload.php';

// Get an api description as php assoc array
$apiDescription = json_decode(
    file_get_contents(__DIR__ . '/../tests/fixtures/Real World API.md.refract.json'),
    true
);

// Get a new parser instance, make sure you have the class available
$parser = new RefractParser();

// Parse the api description, out comes a ParseResult object
$parseResult = $parser->parse($apiDescription);
$api = $parseResult->getApi();

echo "\n";
echo 'API title' . "\n";
echo '---------' . "\n";
echo $api->getApiTitle();

echo "\n\n";

echo 'API description' . "\n";
echo '---------------' . "\n";
echo $api->getApiDocumentDescription();

foreach ($api->getResourceGroups() as $resourceGroup) {

    echo "Resource group: " . $resourceGroup->getTitle() . "\n";

    foreach ($resourceGroup->getResources() as $resource) {
        echo "\t" . 'Resource: ' . $resource->getTitle() . ' [' . $resource->getHref() . ']' . "\n";

        foreach ($resource->getTransitions() as $transition) {
            foreach ($transition->getHttpTransactions() as $httpTransaction) {
                $request = $httpTransaction->getHttpRequest();
                $response = $httpTransaction->getHttpResponse();

                if ($response->hasMessageBody()) {
                    // print the body, etc
                }
            }
        }
    }
}
