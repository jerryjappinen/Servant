
# Page component

- A page has an ID.
- Pages are sorted into nested categories, based on site's directory structure.



### Properties with public getters

Property name   | Description
--------------- | -----------
`children`	| List all pages considered to be childpages.
`id`			| Last value of `tree`. IDs are unique within one level in page hierarchy.
`index`		| The ordinal number of the page on its level. The index numbering starts from `0`.
`isHome`		|
`isMaster`	|
`level`		|
`name`		|
`output`		|
`pages`		|
`parentTree`	| List possible parent IDs in `tree`.
`readPath`	|
`siblings`	| Lists all pages on the same level, including the page itself.
`tree`		|
`type`		|
`scripts`		|
`stylesheets`	|
`template`	| Path to the page file. [Formattable](/technical-docs/about/paths/).

All properties are accessed via getters. To learn how to read this table, read the [introduction to ServantObject](/technical-docs/about/servant-objects).
