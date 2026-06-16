---
layout: default
title: Custom Segments
permalink: /usage/custom-segments/
api: Segments-FactoryInterface
---

A common pattern for working with EDIFACT messages is to have some custom value objects that represent the segments. This library supports this by allowed a custom factory to be provided:

~~~php
<?php

use Estrato\Edifact\Control\CharactersInterface;
use Estrato\Edifact\Segments\FactoryInterface;
use Estrato\Edifact\Segments\Segment;
use Estrato\Edifact\Segments\SegmentInterface;

$factory = new class Factory implements FactoryInterface {
    public function createSegment(
        CharactersInterface $characters,
        string $name,
        mixed ...$elements,
    ): SegmentInterface {
        if ($name === "DTM") {
            return new \Acme\Edifact\Date($name, ...$elements);
        }

        return new \Estrato\Edifact\Segments\Segment($name, ...$elements);
    }
}();

$parser = new \Estrato\Edifact\Parser($factory);
$segments = $parser->parse($ediMessageContent);
foreach ($segments as $segment) {
    # For any DTM segments we'll now have our custom `\Acme\Edifact\Date` instance
~~~
