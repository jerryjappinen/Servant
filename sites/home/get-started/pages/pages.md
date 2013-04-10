
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



## Supported formats

### Plain text (.txt) and Markdown (.md)

Plain text files are treated as &ndash; a popular, simple syntax that resembles regular text. All files with either `.txt` or `.md` extension are treated as Markdown.

Here's an example of the simple syntax:

	## Supported formats

	### Plain text (.txt) and Markdown (.md)

	Plain text files are treated as &ndash; a popular format for writing titles, subtitles...

	- Here's a list item
	- Here's another item

See all the details of Markdown at <a href="http://daringfireball.net/projects/markdown/">daringfireball.net</a>.



### HTML (.html)

HTML files work as-is. [Links and images will be maniupulated](links-and-images) like with all formats, but other than that, you'll get what you write.

If you're not very familiar with HTML but would like to use it, [HTML Dog's beginner tutorial](http://www.htmldog.com/guides/html/beginner/) is a great way to get started.



### Textile (.textile)

<a href="http://en.wikipedia.org/wiki/Textile_%28markup_language%29/">Textile</a> is another syntax that maintains plain text readability. Textile is similar to Markdown, but offers more options while sacrificing the simplicity of its rival.



## Links and images


