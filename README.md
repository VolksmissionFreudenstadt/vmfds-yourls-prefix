# Prefix replacement
Yourls plugin to expand urls prefixed with a certain keyword

## Examples
- `http://short.url/prefix-foo-bar` could be turned into `http://my.long.url/whatever/foo-bar`
- `http://short.url/date-161231` could be turned into `http://my.long.url/events/2016-12-31`
- `http://short.url/just-a-title` could be turned into `http://my.long.url/just/?next=a&word=title`

## Configuration
All configuration takes place in the plugin.yaml file.

## Processing
Processing will take part in four stages:

1. The url keyword will be passed through a number of definable text replacements. These can be found in the `replacements` section in plugin.yaml. Each element in the list there takes two configurations: `find` and `replace`.
2. The correct configuration will be determined by matching the start of the url keyword to one of the `prefix` definitions under `prefixes` in plugin.yaml. The prefix will then be cut off from the keyword.
3. The rest of the keyword will be processed according to the further configuration for this prefix:
  1. If the prefix has a `split` configuration set, the delimiter defined there will be used to split the keywords into parts.
  2. If the prefix has a `subparts` section, all elements listed there will be used as `start: length` parameters for PHP's `substr()` function and a list of parts will be created accordingly.
  3. If nothing like the above is defined, the keyword will remain one single part.
4. A long url will be constructed by inserting the above keyword or its parts into the prefix's `url` through PHP's `sprintf()` function.

Have a look at the enclosed plugin.yaml for some examples.

## Author
Christoph Fischer (@potofcoffee, chris@toph.de) for Volksmission Freudenstadt (@VolksmissionFreudenstadt, http://www.volksmission-freudenstadt.de)
