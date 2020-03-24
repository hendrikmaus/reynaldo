# Reynaldo
Turn API Blueprint Refract Parse Result (Drafter's output) into a traversable PHP data structure.

[![codecov.io](http://codecov.io/github/hendrikmaus/reynaldo/coverage.svg?branch=master)](http://codecov.io/github/hendrikmaus/reynaldo?branch=master)
[![Code Climate](https://img.shields.io/codeclimate/github/kabisaict/flow.svg)](https://codeclimate.com/github/hendrikmaus/reynaldo)

## What is Reynaldo?

It should ease the processing of [Drafter](https://github.com/apiaryio/drafter) refract output.

You read your Drafter parse result, either JSON or YAML, you convert that to a PHP assoc array,
e.g. `json_decode($parseresult, true)` in PHP and pass it into `\Hmaus\Reynaldo\Parser\RefractParser::parse`.

Out comes an easily traversable object.

You can find a little example in `/example`.

```php
// load file and json_decode as assoc array into $apiDescription

$parser = new RefractParser();
$parseResult = $parser->parse($apiDescription);
$api = $parseResult->getApi();

// try to get the API title `$api->getApiTitle();`
// or the document description in markdown `$api->getApiDocumentDescription();`

foreach ($parseResult->getApi()->getResourceGroups() as $apiResourceGroup) {

    foreach ($apiResourceGroup->getResources() as $apiResource) {
    
        foreach ($apiResource->getTransitions() as $apiStateTransition) {
        
            foreach ($apiStateTransition->getHttpTransactions() as $apiHttpTransaction) {
                // inspect `$apiHttpTransaction->getHttpRequest()`, `$apiHttpTransaction->getHttpResponse()`
            }
        }
    }
}
```

## Requirements

* PHP 7.0 or greater

## Installation

The recommended way to install is by using [composer](https://getcomposer.org):

```bash
$ composer require hmaus/reynaldo
```

This will install the PHP package with your application.  

## License

Reynaldo is licensed under the MIT License - see the 
[LICENSE](https://github.com/hendrikmaus/reynaldo/blob/master/LICENSE) file for details.
