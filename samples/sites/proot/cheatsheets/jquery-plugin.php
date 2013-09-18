<?php

$output = '
This optional plugin adds shorthand functions for working with Proot like you\'re used to in jQuery. These jQuery functions map to the [Proot JS library](?category=cheatsheets&id=js-library). Remember to include the plugin in your `app.json` to use it:

#### `app.json`
	{
		...

		"tools": [
			"plugins/jquery.proot.js",
			
			...
		]
	}

';

// Documentation per namespace
$aliases = array(

		'View handling' => array(
			'fetch(type, view, content)' => 'proot.views.life.fetch(this, type, view, content)',
			'create(type, view, content)' => 'proot.views.life.create(this, type, view, content)',
			'dispose(content)' => 'proot.views.life.dispose(this, content)',
		),

		'Lifetime methods' => array(
			'prepare(content)' => 'proot.views.life.run(this, "prepare", "default", content)',
			'render(content)' => 'proot.views.life.run(this, "render", "default", content)',
			'update(content)' => 'proot.views.life.run(this, "update", "default", content)',
			'stash(content)' => 'proot.views.life.run(this, "stash", "reverse", content)',
			'revive(content)' => 'proot.views.life.run(this, "revive", "default", content)',
			'unrender(content)' => 'proot.views.life.run(this, "unrender", "reverse", content)',
			'kill(content)' => 'proot.views.life.run(this, "kill", "reverse", content)',
		),

		'DOM' => array(
			'detect(element)' => 'proot.dom.detect($(this[0]))',
			'extract(element)' => 'proot.dom.extract($(this[0]))',
			'populate(content, secondaryContent, tertiaryContent)' => 'proot.dom.populate($(this), content, secondaryContent, tertiaryContent)',
			'distribute(content)' => 'proot.dom.distribute($(this), content)',
		),

		'Element status' => array(
			'ebabled()' => 'p.dom.is.ebabled($this[0]))',
			'disabled()' => 'p.dom.is.disabled($this[0]))',
			'hidden()' => 'this.is(\':hidden\')',
			'visible()' => '!this.hidden()',
		),

		'Changing element status' => array(
			'enable()' => '',
			'disable()' => '',
		),

		'Simple helpers' => array(
			'isEmpty()' => 'bl.is.empty(p.dom.extract(this))',
			'outerHtml()' => 'p.html.raw(this[0])',
		),

);



// List Proot lib's namespaces
foreach ($aliases as $key => $value) {
$output .= '
## '.$key.'
<table>
';

	foreach ($value as $key2 => $value2) {
		$output .= '<tr><td><code>$(element).'.$key2.'</code></td><td class="discreet">'.$value2.'</td></tr>';
	}

$output .= '
</table>
';

}


$output = Markdown($output);
echo $output;
?>