---
layout: default
title: Writing Messages
permalink: /usage/writing-messages/
api: Message
---

You'll want to be acquainted with [Segments](../segments) before you try to write any messages.

The easiest way to create EDI messages is using the `Message` class:

~~~php
$message = new Message;
~~~

After that you can add individual segments, or multiple segments at once:

~~~php
$message->addSegment($segment);

$message->addSegments($segments);
~~~

Once you have all your segments added, you can creating your EDI message content like so:

~~~php
$ediMessageContent = $message->serialize();
~~~

---

Alternatively if you already have all your segments ready you can create a an instance, and then cast it to a string to serialize:

~~~php
$ediMessageContent = (string) Message::fromSegments($segments);
~~~
