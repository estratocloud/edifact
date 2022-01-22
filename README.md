# edifact

A PHP library to parse and serialize UN/EDIFACT messages.

Full documentation is available at http://estratocloud.github.io/edifact/  
PHPDoc API documentation is also available at [http://estratocloud.github.io/edifact/api/](http://estratocloud.github.io/edifact/api/namespaces/Estrato.Edifact.html)  

[![release](https://poser.pugx.org/estrato/edifact/version.svg)](https://packagist.org/packages/estrato/edifact)
[![build](https://github.com/estratocloud/edifact/workflows/buildcheck/badge.svg?branch=main)](https://github.com/estratocloud/edifact/actions/workflows/buildcheck.yml?query=branch%3Amain)
[![coverage](https://codecov.io/gh/estratocloud/edifact/graph/badge.svg)](https://codecov.io/gh/estratocloud/edifact)


Quick Examples
--------------

Read an EDI message from a file
```php
$message = \Estrato\Edifact\Message::fromFile("/tmp/order.edi");

foreach ($message->getAllSegments() as $segment) {
    echo $segment->getSegmentCode() . "\n";
}
```

Create an EDI message
```php
$message = new \Estrato\Edifact\Message;

$message->addSegment(new Segment("QTY", ["21", "8"]));

echo $message->serialize() . "\n";
```

_Read more at http://estratocloud.github.io/edifact/_  


Changelog
---------
A [Changelog](CHANGELOG.md) has been available since the beginning of time
