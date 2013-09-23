
# Page component

- Pages have IDs.
- Pages are sorted into nested categories, based on site's directory structure.
- Only the root level can have less than two pages.



### Properties with public getters

Property name   | Description | Return values
--------------- | ----------- | -------------
`id()`          | File or directory name-based identifier of the page. IDs are unique within one level in page hierarchy. | String
`index()`       | The index number of the page on its level. Pages are sorted alphabetically by ID, and the index number of the first page is `0`. | Integer
`level()`       |  | 
`name()`        |  | 
`output()`      |  | 
`path($format)` | Path to the page file. [Formattable](/technical-docs/about/paths/). | String.
`parents()`     | List possible parent nodes in `tree()`. `parents(0)` will return the direct parent if one exists. | List of parent node IDs (reversed order).
`siblings()`    | Lists all pages on the same level, but not the page itself. | List of sitemap node IDs.
`site()`        |  | [ServantSite object](site)
`tree()`        |  | 
`type()`        |  | 

To learn how to read this table, read the [introduction to ServantObject](/technical-docs/about/servant-objects).
