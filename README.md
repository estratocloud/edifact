# edifact

A PHP library to parse and serialize UN/EDIFACT messages.

Full documentation is available at http://metroplex-systems.github.io/edifact/  
PHPDoc API documentation is also available at [http://metroplex-systems.github.io/edifact/api/](http://metroplex-systems.github.io/edifact/api/namespaces/Metroplex.Edifact.html)  

[![release](https://poser.pugx.org/metroplex-systems/edifact/version.svg)](https://packagist.org/packages/metroplex-systems/edifact)
[![build](https://travis-ci.org/metroplex-systems/edifact.svg?branch=master)](https://travis-ci.org/metroplex-systems/edifact)
[![coverage](https://codecov.io/gh/metroplex-systems/edifact/graph/badge.svg)](https://codecov.io/gh/metroplex-systems/edifact)


Quick Examples
--------------

Read an EDI message from a file
```php
$message = \Metroplex\Edifact\Message::fromFile("/tmp/order.edi");

foreach ($message->getAllSegments() as $segment) {
    echo $segment->getSegmentCode() . "\n";
}
```

Create an EDI message
```php
$message = new \Metroplex\Edifact\Message;

$message->addSegment(new Segment("QTY", ["21", "8"]));

echo $message->serialize() . "\n";
```

_Read more at http://metroplex-systems.github.io/edifact/_  


Changelog
---------
A [Changelog](CHANGELOG.md) has been available since the beginning of time
