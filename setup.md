---
layout: default
title: Setup
permalink: /setup/
---

All classes are in the `Metroplex\Edifact` namespace.

The easiest way of working with EDI messages is using the [Message](../usage/reading-messages/) class:

~~~php
require_once __DIR__ . "vendor/autoload.php";

use Metroplex\Edifact\Message;

$edifact = new Message;
~~~
