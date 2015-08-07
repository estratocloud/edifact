---
layout: default
title: Segments
permalink: /usage/segments/
api: Segment
---

At the moment, the `Segment` class is pretty dumb, but it is slightly easier than plain arrays.

You can get the name of the segment:

~~~php
$name = $segment->getName();
~~~

And then you can access the content of the segment like so:

~~~php
# Access simple string data
$segment->getElement(0);

# Or nested array content
$segment->getElement(1)[1];
~~~

If you just want all of the segments data you can retrieve it like so:

~~~php
foreach ($segment->getAllElements() as $element) {
}
~~~
