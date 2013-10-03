
# Page component

- A page has an ID.
- Pages are sorted into nested categories, based on site's directory structure.



### Properties with public getters

Property name   | Description | Return values
--------------- | ----------- | -------------
`id()`          | File or directory name-based identifier of the page. IDs are unique within one level in page hierarchy. | String
`index()`       | The index number of the page on its level. Pages are sorted alphabetically by ID, and the index number of the first page is `0`. | Integer
`level()`       |  | 
`name()`        |  | 
`output()`      |  | 
`path($format)` | Path to the page file. [Formattable](/technical-docs/about/paths/). | String.
`parentTree()`  | List possible parent IDs in `tree()`. | List of parent parent IDs.
`siblings()`    | Lists all pages on the same level, including the page itself. | List of sitemap node IDs.
`site()`        |  | [ServantSite object](site)
`tree()`        |  | 
`type()`        |  | 

To learn how to read this table, read the [introduction to ServantObject](/technical-docs/about/servant-objects).
