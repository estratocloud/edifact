---
layout: default
title: Reading Messages
permalink: /usage/reading-messages/
api: Message
---

Most of the time, you'll probably receive your EDI message as either a file or a string, there are 2 methods available to create a message from these:

~~~php
$message = Message::fromFile("/tmp/order.edi");

$message = Message::fromString($ediMessageContents);
~~~

Once you have your message, you can get all the segments like so:

~~~php
foreach ($message->getAllSegments() as $segment) {
}
~~~

Or if you only want segments using a particular name:

~~~php
foreach ($message->getSegments("LIN") as $segment) {
}
~~~

And sometimes you only want one segment:

~~~php
$segment = $message->getSegment("BGM");
~~~

See what you can do with [Segments here](../segments)
