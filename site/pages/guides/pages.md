
# Pages

Each text document under `site/pages/` will be shown as a page on your site. **Just creating a new `.txt` file will result in a new page**, but you can also write files in [Markdown](/docs/template-formats/text-and-markdown), [HTML](/docs/template-formats/HTML), [PHP](/docs/template-formats/PHP), or any of the other [supported formats](/docs/template-formats).

All files are converted and served to your users as HTML automatically. For example, your site's folder could look like this:



##### site/
	pages/
		about.txt
		documentation.textile
		examples.php
		features.html
		get-started.md
	assets/

Pages can contain things like subtitles, links and images. [Assets](assets) decide how they look on the final site.



## Links & images

Pointing to other documents with links, or adding images to a page is really easy. Defining links and images can be done in different ways depending on the file format, but the URLs always work in the same way.

Here are some examples in [Markdown](/docs/template-formats/text-and-markdown).

### Relative URLs

##### site/pages/welcome.md
	[Get in touch](contact-us)
	[See where we are](contact-us/map)

### Root-relative URLs

##### site/pages/contact-us/map.md
	[Back to home page](/)
	[Welcome page on the root level](/welcome)
	[See where we are](/contact-us/map)
