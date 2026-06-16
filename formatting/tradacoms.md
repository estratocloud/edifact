---
layout: default
title: TRADACOMS
permalink: /formatting/tradacoms/
api: Control-Tradacoms
---

If you need to work with [TRADACOMS](https://en.wikipedia.org/wiki/TRADACOMS) formats then this library can help, simply use the `Tradacoms` characters class:

~~~php
$characters = new \Estrato\Edifact\Control\Tradacoms();
$serializer = new \Estrato\Edifact\Serializer($characters);
$ediMessageContent = $serializer->serialize(...$segments);
~~~

You can also use it for parsing messages:

~~~php
$characters = new \Estrato\Edifact\Control\Tradacoms();
$parser = new \Estrato\Edifact\Parser();
$segments = $parser->parse($ediMessageContent, $characters);
foreach ($segments as $segment) {
~~~

If you try to use any of the `with*()` methods on the `Tradacoms` class they'll throw a `BadMethodException` as TRADACOMS does not support using different characters than the specification states.
