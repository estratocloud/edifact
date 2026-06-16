---
layout: default
title: Segments
permalink: /usage/segments/
api: Segments-SegmentInterface
---

The `Segment` class is a fairly light structure that holds the underlying data from the EDIFACT file.  
When serializing you can create it like so:

```php
$segment = new Segment("UNH", 123456, ["INVRPT", "D", "96A", "UN", "EAN004"]);
```

And when parsing you can get the name of the segment:

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

If you just want all of the segment's data you can retrieve it like so:

~~~php
foreach ($segment->getAllElements() as $element) {
}
~~~
