
# Templates

Templates decide the basic structure for your site, i.e. how pages are rendered. They dynamically generate HTML headers, meta tags, menus etc.

Multiple templates are available under `templates/` (each directory is a template). Simply editing files in a template's directory is enough to edit the template in use. A template can be split into multiple files, which are autoloaded when a template is used. The output of all the included files is used as template content.

For each page a template is picked, either the global default or the one defined in settings.



## Variables available for scripting

The following variables are available to you when you're writing dynamic templates in *PHP* or any other supported scripting language.

Variable    | Description                        | Read more                                              |
----------- | ---------------------------------- | ------------------------------------------------------ |
`$servant`  | Main services provided by Servant. | [ServantMain](/docs/components/main)         |
`$template` | The current template object.       | [ServantTemplate](/docs/components/template) |

Each template is passed zero, one or more pieces of arbitrary content. The stock **actions** pass body content *and* a [page object](/docs/components/page) to the template they use. Template content can be accessed like this:

	$content = $template->content();      // First piece of content if available
	$content = $template->content(0);     // Same as above
	$page = $template->content(1);        // Second piece of content if available

Templates can be nested, and you can very effortlessly create very customized templates by simply throwing a couple of `.php` files at Servant.

	echo '<iframe>'.$template->nest('mainmenu', $page).$content.'</iframe>';
