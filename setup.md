---
layout: default
title: Setup
permalink: /setup/
---

All classes are in the `Estrato\Edifact` namespace.

The easiest way of working with EDI messages is using the [Message](../api/classes/Estrato-Edifact-Message.html) class:

~~~php
require_once __DIR__ . "/vendor/autoload.php";

use Estrato\Edifact\Message;

$edifact = new Message();
~~~
