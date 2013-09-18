
# Article component

- Articles have IDs.
- Articles are sorted into nested categories, based on site's directory structure.
- Only the root level can have less than two articles.



### Properties with public getters

Property name   | Description | Return values
--------------- | ----------- | -------------
`id()`          | File or directory name-based identifier of the article. IDs are unique within one level in article hierarchy. | String
`index()`       | The index number of the article on its level. Articles are sorted alphabetically by ID, and the index number of the first article is `0`. | Integer
`level()`       |  | 
`name()`        |  | 
`output()`      |  | 
`path($format)` | Path to the article file. [Formattable](/technical-docs/about/paths/). | String.
`parents()`     | List possible parent nodes in `tree()`. `parents(0)` will return the direct parent if one exists. | List of parent node IDs (reversed order).
`siblings()`    | Lists all articles on the same level, but not the article itself. | List of sitemap node IDs.
`site()`        |  | [ServantSite object](site)
`tree()`        |  | 
`type()`        |  | 

To learn how to read this table, read the [introduction to ServantObject](/technical-docs/about/servant-objects).
