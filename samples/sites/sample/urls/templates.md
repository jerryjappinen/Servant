
# URLs in templates

Servant automatically manipulates URLs in HTML, Markdown etc. (`href` and `src` attributes). This is done to make sure that they consistently point to the same place as expected on the site. This page gives you an idea of what's going on.

These are the basic rules:

- Absolute URLs are never touched.
- HTML: relative `href` points to a sibling article on the same level.
- HTML: relative `src` points to a file in the same directory.
- HTML: root-relative `href` or `src` (starts with a `/`) points to a file/article in the root directory/level of the site.

### Relative URLs



##### Relative link URL on this article

<pre>
	&lt;!-- Original --&gt;
	&lt;a href=&quot;foo.jpg&quot;&gt;

	&lt;!-- Result --&gt;
	&lt;a href="foo.jpg"&gt;
</pre>

##### Relative image URL on this article

<pre>
	&lt;!-- Original --&gt;
	&lt;img src=&quot;foo.jpg&quot;&gt;

	&lt;!-- Result --&gt;
	&lt;img src="foo.jpg"&gt;
</pre>



### Root-relative URLs

##### Root-relative link URL on this article

<pre>
	&lt;!-- Original --&gt;
	&lt;a href=&quot;/foo.jpg&quot;&gt;

	&lt;!-- Result --&gt;
	&lt;a href="/foo.jpg"&gt;
</pre>

##### Root-relative image URL on this article

<pre>
	&lt;!-- Original --&gt;
	&lt;img src=&quot;/foo.jpg&quot;&gt;

	&lt;!-- Result --&gt;
	&lt;img src="/foo.jpg"&gt;
</pre>



### URLs to actions

##### **Advanced**: link to an action

<pre>
	&lt;!-- Original --&gt;
	&lt;a href=&quot;//search&quot;&gt;

	&lt;!-- Result --&gt;
	&lt;a href="//search"&gt;
</pre>

##### **Advanced**: `src` URL to an action

<pre>
	&lt;!-- Original --&gt;
	&lt;a href=&quot;//imagescale/subdir\/foo.jpg/200/400&quot;&gt;

	&lt;!-- Result --&gt;
	&lt;a href="//imagescale/subdir\/foo.jpg/200/400"&gt;
</pre>
