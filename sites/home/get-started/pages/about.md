
# Pages

Each text document in your site's folder will be shown as a page on your site, so creating pages are very easy to create in Servant: just creating a new `.txt` file will suffice, but you can make it as simple or complex as you like.

You can create files in **plain text, HTML, Textile or Markdown**. Advanced users can write dynamic pages in *PHP*. All files are converted and served to your users as HTML automatically. For example, your site's folder could look like this:

##### sites/my-site/
	about.txt
	documentation.textile
	examples.php
	features.html
	get-started.md

Pages can contain things like titles, subtitles, lists, links and images. [Themes](templates-and-themes) will change the look of these elements, so a site can look very different by changing the theme it's using without changing the content.



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
