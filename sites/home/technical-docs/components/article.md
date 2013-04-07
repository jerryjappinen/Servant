
# Article component

- Articles have IDs.
- Articles are sorted into nested categories, based on site's directory structure.
- Only the root level can have less than two articles.



### Properties with public getters

<table>
	<tr>
		<th scope="col">Property name</th>
		<th scope="col">Description</th>
		<th scope="col">Return values</th>
	</tr>

	<tr>
		<th scope="row"><code>id()</code></th>
		<td>File or directory name-based identifier of the article. IDs are unique within one level in article hierarchy.</td>
		<td>String</td>
	</tr>

	<tr>
		<th scope="row"><code>index()</code></th>
		<td>The index number of the article on its level. Articles are sorted alphabetically by ID, and the index number of the first </td>
		<td>Integer</td>
	</tr>

	<tr>
		<th scope="row"><code>level()</code></th>
		<td></td>
		<td></td>
	</tr>

	<tr>
		<th scope="row"><code>name()</code></th>
		<td></td>
		<td></td>
	</tr>

	<tr>
		<th scope="row"><code>output()</code></th>
		<td></td>
		<td></td>
	</tr>

	<tr>
		<th scope="row"><code>path($format)</code></th>
		<td>Default, takes an optional <code>$format</code> parameter</td>
		<td>Path to the article file. <a href="/backend/source-code/paths/">Formattable</a>.</td>
	</tr>

	<tr>
		<th scope="row"><code>parents()</code></th>
		<td>List possible parent nodes in <code>tree()</code>. <code>parents(0)</code> will return the direct parent if one exists.</td>
		<td>List of parent node IDs (reversed order).</td>
	</tr>

	<tr>
		<th scope="row"><code>siblings()</code></th>
		<td>Lists all articles on the same level, but not the article itself.</td>
		<td>List of sitemap node IDs.</td>
	</tr>

	<tr>
		<th scope="row"><code>site()</code></th>
		<td>Site</td>
		<td><a href="site">ServantSite object</a>.</td>
	</tr>

	<tr>
		<th scope="row"><code>tree()</code></th>
		<td></td>
		<td></td>
	</tr>

	<tr>
		<th scope="row"><code>type()</code></th>
		<td></td>
		<td></td>
	</tr>

</table>

To learn how to read this table, read the [introduction to ServantObject](/technical-docs/about/servant-objects).
