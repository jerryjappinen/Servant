
# Template scripting

Each directory under `templates/` is a directory. A template can be split into multiple files, which are autoloaded when a template is used. The output of all the included files is used as template content.



### Variables

The following variables are available to you when you're writing dynamic **templates** in *PHP* or any other supported scripting language.

Variable    | Description                        | Read more                                              |
----------- | ---------------------------------- | ------------------------------------------------------ |
`$servant`  | Main services provided by Servant. | [ServantMain](/docs/components/main)         |
`$template` | This template.                     | [ServantTemplate](/docs/components/template) |
`$action`   | The current action.                | [ServantAction](/docs/components/action)     |
`$page`     | The current page.                  | [ServantPage](/docs/components/page)         |
