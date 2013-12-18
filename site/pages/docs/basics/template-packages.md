
# Templates

Templates decide the basic structure for your site (i.e. into what context pages are rendered). They dynamically generate HTML (headers, meta data, menus, footers etc.). Multiple templates are available under the `templates/` directory. Simply adding, removing and modifying these files is enough to edit the templates in use.

A template is always picked for a site, either the global default or the one defined in settings. In absence of any templates, page content is rendered directly.



## Writing templates

The following variables are available to you when you're writing dynamic templates in *PHP* or any of the other supported scripting languages.

Variable    | Description                        | Read more                                              |
----------- | ---------------------------------- | ------------------------------------------------------ |
`$servant`  | Main services provided by Servant. | [ServantMain](/docs/components/main)         |
`$template` | This template.                     | [ServantTemplate](/docs/components/template) |
`$action`   | The current action.                | [ServantAction](/docs/components/action)     |
`$page`     | The current page.                  | [ServantPage](/docs/components/page)         |
