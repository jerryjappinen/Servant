
# HAML pages

#### File extension: `.haml`

**HAML** is a templating language built to make it easier to write PHP into HTML. It has a proprietary syntax that matches PHP.

Read more about HAML on [haml.info](http://haml.info/). This PHP version uses MtHaml and the PHP-specific details are summed up on [MtHaml's page](https://github.com/arnaud-lb/MtHaml).



## Basic example

##### pages/some-category/some-page.haml

	%h1 This is HAML

	%ul#users
		-foreach($servant->constants()->formats('templates') as $formats)
			-foreach($formats as $format)
				%li.format
					= $format
