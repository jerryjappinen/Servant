
# Pages

Each text document in your site's folder will be shown as a page on your site. **Just creating a new `.txt` file will result in a new page**, but you can also write files in [HTML](HTML), [Textile](textile), [Markdown](text-and-markdown), [Wiki markup](wiki-markup), RST, [PHP](PHP), HAML, [Twig](twig) or Jade. Phew!

All files are converted and served to your users as HTML automatically. For example, your site's folder could look like this:

##### sites/my-site/
	about.txt
	documentation.textile
	examples.php
	features.html
	get-started.md

Pages can contain things like titles, subtitles, lists, links and images. [Themes](templates-and-themes) will change the look of these elements, so your pages can look very different if you choose a new theme.



## Links & images

Pointing to other documents with links, or adding images to a page is really easy. Different formats allow you to write links and images in different ways, but the URLs work the same way for every article. Here are some examples in [Markdown](text-and-markdown).

### Relative URLs

##### sites/my-site/welcome.md
	[Get in touch](contact-us)
	[See where we are](contact-us/map)

### Root-relative URLs

##### sites/my-site/contact-us/map.md
	[Back to home page](/)
	[Welcome page on the root level](/welcome)
	[See where we are](/contact-us/map)
