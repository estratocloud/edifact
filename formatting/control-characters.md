---
layout: default
title: Control Characters
permalink: /formatting/control-characters/
api: Control-CharactersInterface
---

Whenever you parse or serialize messages by default this library will either use the UNA segment from the file you're parsing, or the [default characters](https://www.gs1.org/sites/default/files/docs/eancom/ean02s4/part2/debmul/051.htm).  
If you want to produce a file using custom characters, you can override them like so:

~~~php

$characters = (new \Estrato\Edifact\Control\Characters())
    ->withComponentSeparator(":")
    ->withDataSeparator("+")
    ->withDecimalPoint(".")
    ->withEscapeCharacter("?")
    ->withSegmentTerminator("'")
    ->withReservedSpace(" ");

$serializer = new \Estrato\Edifact\Serializer($characters);
$ediMessageContent = $serializer->serialize(...$segments);
~~~

You can do the same while parsing (although it will normally be ignored and the UNA segment's specification will be respected), but if you have a bad file that doesn't have a UNA segment and still uses different settings from the default then you provide your own control characters like so:

~~~php
$characters = (new \Estrato\Edifact\Control\Characters())
    ->withSegmentTerminator("^");

$parser = new \Estrato\Edifact\Parser();
$segments = $parser->parse($ediMessageContent, $characters);
foreach ($segments as $segment) {
~~~
