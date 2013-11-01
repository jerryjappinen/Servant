
# Pages

Each text document in your site's `content/` folder will be shown as a page on your site. **Just creating a new `.txt` file will result in a new page**, but you can also write files in other formats like [HTML](/technical-docs/template-formats/HTML), [Markdown](/technical-docs/template-formats/text-and-markdown), [PHP](/technical-docs/template-formats/PHP), or [HAML](/technical-docs/template-formats/twig).

All files are converted and served to your users as HTML automatically. For example, your site's folder could look like this:

##### site/
	content/
		about.txt
		documentation.textile
		examples.php
		features.html
		get-started.md
	theme/

Pages can contain things like titles, subtitles, lists, links and images. The [theme](theme) will decide the look of these elements on the final site.



## Links & images

Pointing to other documents with links, or adding images to a page is really easy. Defining links and images can be done in different ways depending on the file format, but the URLs always work in the same way.

Here are some examples in [Markdown](/technical-docs/template-formats/text-and-markdown).

### Relative URLs

##### site/content/welcome.md
	[Get in touch](contact-us)
	[See where we are](contact-us/map)

### Root-relative URLs

##### site/content/contact-us/map.md
	[Back to home page](/)
	[Welcome page on the root level](/welcome)
	[See where we are](/contact-us/map)
