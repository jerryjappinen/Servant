
# Templates

Templates decide the basic structure for your site, i.e. how pages are rendered. They dynamically generate HTML (headers, meta tags, menus etc.).

Multiple templates are available under `templates/`. Simply editing these files is enough to edit the templates in use.

For each page a template is picked, either the global default or the one defined in settings.



## Writing templates

The following variables are available to you when you're writing dynamic templates in *PHP* or any of the other supported scripting languages.

Variable    | Description                        | Read more                                              |
----------- | ---------------------------------- | ------------------------------------------------------ |
`$servant`  | Main services provided by Servant. | [ServantMain](/docs/components/main)         |
`$template` | This template.                     | [ServantTemplate](/docs/components/template) |
`$action`   | The current action.                | [ServantAction](/docs/components/action)     |
`$page`     | The current page.                  | [ServantPage](/docs/components/page)         |
