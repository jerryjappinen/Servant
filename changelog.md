
# Servant changelog

## 0.2.0 June 22, 2014

Dedicated `ServantManifest` class for reading the manifest JSON. All items (except `sitemap`) now support values defined per page node. It's now possible to define language, icons and splash images for specific categories or pages. The value for root node (`"/"` or `""`) is used as the global default, or the first value if that does not exist.

Some changes to keys used in manifest:

- `pageDescriptions` is **gone**
- `pageTemplates` is **gone**
- `pageOrder` is now `sitemap`
- `name` is now `siteName`
- `assets` is **gone**, replaced by `scripts` and `stylesheets`

All keys are treated as case-insensitive, and both plural and singular forms are accepted.


### Upgrade guide

- Update `settings.json` to use the new keys and shared setting items
- If you've hacked `html` template, migrate changes to the new version
- If you're using `js-vars`, some variables have changed

As always, import your custom assets, pages, templates and actions.
