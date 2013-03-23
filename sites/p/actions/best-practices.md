
# Best practices

### Actions are *not* app-specific

While there's nothing that prevents you from writing actions tailored to a specific app, you should aim to write generic scripts that can serve multiple client application. Many web apps do more or less the same things behind the scenes, and writing generic backend code in Proot lets you share code between your client applications.



### Use global variables

Use [gloabal backend variables](?category=actions&id=global-variables) in your PHP code (everything listed before `$action` is available to your script). Examples:

- Always point to locations in the file system via `$paths` (or `$p`).
- The name of the current app is `$app['id']`.
- `$constants` includes things like names of specific files, lists of content types, common regular expressions, app package contents etc.

The correct way to dynamically refer to the app manifest file, for example, is `$p['apps'].$app['id'].'/'.$constants['app package']['manifest']`.
