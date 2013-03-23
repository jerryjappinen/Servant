
# Output

In most cases you'll just want to have a `$action['output']` include your output by the end of your script. If you set an array or other types of data as the output, Proot will do deliver this output as a sensible response based on the content type you set.



## Output types

### Strings, integers, arrays

Strings and integers are printed as is. Arrays are converted into some meaningful output, if possible (works best with JSON).



### Images

Proot will output a new GIF, JPG or PNG image from a GD image resource.

#### `output.php`
    $file = $p['apps'].$app['id'].'/images/foo.jpg';

    $action['content type'] = 'jpg';
	$action['output'] = imagecreatefromjpeg($file);


### Files

To output a file from the file system, write the path of the file as `output` and set `file output` to either `'redirect'` or `'read file'`. The former will simply forward the user to the file, which is very fast, but also changes the URL to point to the actual file. The latter will print out the contents of the file without changing the URL, which takes a bit more time. Examples:

#### `action.php`

    $action['file output'] = 'redirect';
    $action['output'] = $p['apps'].$app['id'].'/images/foo.jpg';

    $action['file output'] = 'redirect';
    $action['output'] = $p['apps'].$app['id'].'/data/raw/proot-web.psd';

    $action['file output'] = 'read file';
    $action['output'] = $p['apps'].$app['id'].'/assets/bar.css';

`$action['file output']` defaults to `false`.



## Errors

If you cannot create a meaningful response to the user, you should respond with error messages. They are literally strings you append to the `$errors` array. You should try to catch errors in `validation.php`, and leave `output.php` for success case.

The format is simple: status code followed by the message. Ignore capital casing for the start and period at the end. Proot will handle delivering the error messages to the user in a good context-specific format.

#### `validations.php`
	if (!is_dir($p['apps'].$app['id'].'/images/'.$input['gallery'])) {
    	$errors[] = '400 '.$input['gallery'].' is not a gallery';
	}

In the example, above, the user has requested a specific record object, but has not provided the obligatory ID parameter. As the type has been selected, it can be used to create a better error message.
